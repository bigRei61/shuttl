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
    $photo = UploadedFile::fake()->image('event.jpg');

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

it('shows only approved joined events on the tournament page for players', function () {
    $player = User::factory()->create();
    $approvedEvent = Event::create([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Approved Cup',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-10',
        'status' => 'open',
    ]);
    $pendingEvent = Event::create([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Pending Cup',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-11',
        'end_date' => '2026-07-11',
        'status' => 'open',
    ]);

    $approvedEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pendingEvent->players()->attach($player->id, ['status' => 'pending']);

    $this->actingAs($player)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee('Approved Cup')
        ->assertDontSee('Pending Cup');
});
