<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows approved joined and hosted tournaments for players', function () {
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

    $approvedTournament->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pendingTournament->players()->attach($player->id, ['status' => 'pending']);
    $rejectedTournament->players()->attach($player->id, ['status' => 'rejected', 'responded_at' => now()]);
    $approvedQuickPlay->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);

    $this->actingAs($player)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Approved Cup')
        ->assertSee(route('events.show', $approvedTournament), false)
        ->assertSee('Hosted Cup')
        ->assertSee(route('events.show', $hostedTournament), false)
        ->assertDontSee('Pending Cup')
        ->assertDontSee('Rejected Cup')
        ->assertDontSee('Open Cup')
        ->assertDontSee('Approved Quick Play');

    expect($unjoinedTournament->exists)->toBeTrue();
});

it('shows all tournament events to admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $tournament = createTournament(['name' => 'Admin Visible Cup']);
    $quickPlay = createTournament([
        'name' => 'Admin Hidden Quick Play',
        'type' => 'quick_play',
    ]);

    $this->actingAs($admin)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Admin Visible Cup')
        ->assertDontSee('Admin Hidden Quick Play');

    expect($quickPlay->exists)->toBeTrue()
        ->and($tournament->exists)->toBeTrue();
});

function createTournament(array $attributes = []): Event
{
    return Event::create(array_merge([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Tournament',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-10',
        'status' => 'open',
    ], $attributes));
}
