<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows active joined and hosted events for players', function () {
    $player = User::factory()->create();
    $approvedTournament = createTournament([
        'name' => 'Approved Cup',
    ]);
    $hostedTournament = createTournament([
        'organizer_id' => $player->id,
        'name' => 'Hosted Cup',
    ]);
    $pendingTournament = createTournament([
        'name' => 'Pending Cup',
    ]);
    $rejectedTournament = createTournament([
        'name' => 'Rejected Cup',
    ]);
    $unjoinedTournament = createTournament([
        'name' => 'Open Cup',
    ]);
    $approvedQuickPlay = createTournament([
        'name' => 'Approved Quick Play',
        'type' => 'quick_play',
    ]);
    $completedEvent = createTournament([
        'name' => 'Completed Cup',
        'status' => 'completed',
    ]);
    $pastEvent = createTournament([
        'name' => 'Past Cup',
        'start_date' => now()->subDays(3)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
    ]);

    $approvedTournament->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pendingTournament->players()->attach($player->id, ['status' => 'pending']);
    $rejectedTournament->players()->attach($player->id, ['status' => 'rejected', 'responded_at' => now()]);
    $approvedQuickPlay->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $completedEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pastEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);

    $this->actingAs($player)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Approved Cup')
        ->assertSee(route('events.show', $approvedTournament), false)
        ->assertSee('Hosted Cup')
        ->assertSee(route('events.show', $hostedTournament), false)
        ->assertSee('Approved Quick Play')
        ->assertSee(route('events.show', $approvedQuickPlay), false)
        ->assertDontSee('Pending Cup')
        ->assertDontSee('Rejected Cup')
        ->assertDontSee('Open Cup')
        ->assertDontSee('Completed Cup')
        ->assertDontSee('Past Cup');

    expect($hostedTournament->exists)->toBeTrue()
        ->and($unjoinedTournament->exists)->toBeTrue();
});

it('shows all active events to admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $tournament = createTournament(['name' => 'Admin Visible Cup']);
    $quickPlay = createTournament([
        'name' => 'Admin Visible Quick Play',
        'type' => 'quick_play',
    ]);
    $completedEvent = createTournament([
        'name' => 'Admin Hidden Completed Cup',
        'status' => 'completed',
    ]);
    $pastEvent = createTournament([
        'name' => 'Admin Hidden Past Cup',
        'start_date' => now()->subDays(3)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
    ]);

    $this->actingAs($admin)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Admin Visible Cup')
        ->assertSee('Admin Visible Quick Play')
        ->assertDontSee('Admin Hidden Completed Cup')
        ->assertDontSee('Admin Hidden Past Cup');

    expect($quickPlay->exists)->toBeTrue()
        ->and($tournament->exists)->toBeTrue()
        ->and($completedEvent->exists)->toBeTrue()
        ->and($pastEvent->exists)->toBeTrue();
});

function createTournament(array $attributes = []): Event
{
    return Event::create(array_merge([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Tournament',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(2)->toDateString(),
        'status' => 'open',
    ], $attributes));
}
