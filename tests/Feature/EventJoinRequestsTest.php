<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores a player created event with a photo and approves the creator as host', function () {
    Storage::fake('public');

    $player = User::factory()->create();
    $photo = UploadedFile::fake()->createWithContent(
        'event.png',
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=')
    );

    $this->actingAs($player)
        ->post(route('events.store'), [
            'name' => 'Sunday Doubles Cup',
            'type' => 'tournament',
            'location' => 'MTDY Badminton Court',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-11',
            'description' => 'This should be ignored for players.',
            'max_participants' => 32,
            'photo' => $photo,
        ])
        ->assertRedirect(route('events.index'));

    $event = Event::first();

    expect($event->organizer_id)->toBe($player->id)
        ->and($event->photo_path)->not->toBeNull()
        ->and($event->photoUrl())->toContain('/storage/event-photos/')
        ->and($event->description)->toBeNull()
        ->and($event->max_participants)->toBeNull()
        ->and($event->players()->whereKey($player->id)->first()->pivot->status)->toBe('approved');

    Storage::disk('public')->assertExists($event->photo_path);
});

it('shows events hosted by the player even without an approved pivot row', function () {
    $player = User::factory()->create();
    $hostedEvent = Event::create([
        'organizer_id' => $player->id,
        'name' => 'Hosted Cup',
        'type' => 'tournament',
        'location' => 'Court 3',
        'start_date' => '2026-07-12',
        'end_date' => '2026-07-12',
        'status' => 'open',
    ]);

    $this->actingAs($player)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee($hostedEvent->name)
        ->assertSee(route('events.show', $hostedEvent), false);
});

it('requires host approval before a player joins an event', function () {
    $host = User::factory()->create();
    $player = User::factory()->create();
    $event = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Club Night',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-10',
        'status' => 'open',
    ]);

    $this->actingAs($player)
        ->post(route('events.join', $event))
        ->assertSessionHas('success');

    expect($event->players()->whereKey($player->id)->first()->pivot->status)->toBe('pending');

    $this->actingAs($host)
        ->put(route('events.join-requests.approve', [$event, $player]))
        ->assertSessionHas('success');

    expect($event->fresh()->players()->whereKey($player->id)->first()->pivot->status)->toBe('approved');
});

it('shows only active events on the events page for players', function () {
    $player = User::factory()->create();
    $activeOpenEvent = Event::create([
        'organizer_id' => $player->id,
        'name' => 'Active Open Cup',
        'type' => 'tournament',
        'location' => 'Court 3',
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(2)->toDateString(),
        'status' => 'open',
    ]);
    $activeOngoingEvent = Event::create([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Active Ongoing Cup',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->toDateString(),
        'status' => 'ongoing',
    ]);
    $oldEvent = Event::create([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Old Cup',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => now()->subDays(3)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
        'status' => 'open',
    ]);
    $completedEvent = Event::create([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Archived Cup',
        'type' => 'quick_play',
        'location' => 'Court 5',
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(2)->toDateString(),
        'status' => 'completed',
    ]);

    $this->actingAs($player)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee($activeOpenEvent->name)
        ->assertSee($activeOngoingEvent->name)
        ->assertDontSee($oldEvent->name)
        ->assertDontSee($completedEvent->name);
});
