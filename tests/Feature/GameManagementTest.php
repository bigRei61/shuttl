<?php

use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('allows a host to schedule a singles game with approved players', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);

    $this->actingAs($host)
        ->post(route('games.store', $event), [
            'format' => 'singles',
            'scheduled_at' => '2026-07-20 10:00:00',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertRedirect(route('events.show', $event));

    $game = Game::with('gamePlayers')->first();

    expect($game)->not->toBeNull()
        ->and($game->format)->toBe('singles')
        ->and($game->competitive_type)->toBe('competitive')
        ->and($game->status)->toBe('scheduled')
        ->and($game->gamePlayers)->toHaveCount(2)
        ->and($game->gamePlayers->where('team_side', 1)->pluck('player_id')->all())->toBe([$players[0]->id])
        ->and($game->gamePlayers->where('team_side', 2)->pluck('player_id')->all())->toBe([$players[1]->id]);
});

it('allows a host to schedule a doubles game with approved players', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(4);

    $this->actingAs($host)
        ->post(route('games.store', $event), [
            'format' => 'doubles',
            'scheduled_at' => '2026-07-20 11:00:00',
            'team_one_players' => [$players[0]->id, $players[1]->id],
            'team_two_players' => [$players[2]->id, $players[3]->id],
        ])
        ->assertRedirect(route('events.show', $event));

    $game = Game::with('gamePlayers')->first();

    expect($game)->not->toBeNull()
        ->and($game->format)->toBe('doubles')
        ->and($game->gamePlayers)->toHaveCount(4)
        ->and($game->gamePlayers->pluck('role')->unique()->values()->all())->toBe(['doubles_partner']);
});

it('forbids non-hosts from creating games', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $otherPlayer = User::factory()->create();

    $this->actingAs($otherPlayer)
        ->post(route('games.store', $event), [
            'format' => 'singles',
            'scheduled_at' => '2026-07-20 10:00:00',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertForbidden();

    expect(Game::count())->toBe(0)
        ->and($host->exists)->toBeTrue();
});

it('validates game player assignment rules', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $unapprovedPlayer = User::factory()->create();

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'singles',
            'scheduled_at' => '2026-07-20 10:00:00',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[0]->id],
        ])
        ->assertInvalid(['team_two_players']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'doubles',
            'scheduled_at' => '2026-07-20 10:00:00',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertInvalid(['team_one_players', 'team_two_players']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'singles',
            'scheduled_at' => '2026-07-20 10:00:00',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$unapprovedPlayer->id],
        ])
        ->assertInvalid(['team_one_players']);

    expect(Game::count())->toBe(0);
});

it('allows a host to record final scores and computes the winning side', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $game = scheduledGame($event, $players);

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 19, 'team2_score' => 21],
                ['team1_score' => 21, 'team2_score' => 17],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    $game->refresh();

    expect($game->status)->toBe('completed')
        ->and($game->winning_side)->toBe(1)
        ->and($game->team1_sets_won)->toBe(2)
        ->and($game->team2_sets_won)->toBe(1)
        ->and($game->played_at)->not->toBeNull()
        ->and($game->setScores)->toHaveCount(3);
});

it('forbids non-hosts from recording results', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $game = scheduledGame($event, $players);
    $otherPlayer = User::factory()->create();

    $this->actingAs($otherPlayer)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
            ],
        ])
        ->assertForbidden();

    expect($game->fresh()->status)->toBe('scheduled')
        ->and($host->exists)->toBeTrue();
});

it('shows games to players without management controls', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $game = scheduledGame($event, $players);
    $game->setScores()->createMany([
        ['set_number' => 1, 'team1_score' => 21, 'team2_score' => 18],
        ['set_number' => 2, 'team1_score' => 21, 'team2_score' => 19],
    ]);
    $game->update([
        'status' => 'completed',
        'winning_side' => 1,
        'team1_sets_won' => 2,
        'team2_sets_won' => 0,
        'played_at' => now(),
    ]);

    $this->actingAs($players[0])
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee($players[0]->name)
        ->assertSee($players[1]->name)
        ->assertSee('Set 1 21-18')
        ->assertSee('2-0')
        ->assertDontSee('Schedule Game')
        ->assertDontSee('Record final score');

    expect($host->exists)->toBeTrue();
});

/**
 * @return array{0: Event, 1: User, 2: Collection<int, User>}
 */
function eventWithApprovedPlayers(int $playerCount): array
{
    $host = User::factory()->create();
    $players = User::factory()->count($playerCount)->create();
    $event = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Summer Cup',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-20',
        'status' => 'open',
    ]);

    $event->players()->attach($host->id, ['status' => 'approved', 'responded_at' => now()]);

    foreach ($players as $player) {
        $event->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    }

    return [$event, $host, $players];
}

function scheduledGame(Event $event, $players): Game
{
    $game = $event->games()->create([
        'format' => 'singles',
        'competitive_type' => 'competitive',
        'scheduled_at' => '2026-07-20 10:00:00',
        'status' => 'scheduled',
    ]);

    $game->gamePlayers()->createMany([
        ['player_id' => $players[0]->id, 'team_side' => 1, 'role' => 'singles'],
        ['player_id' => $players[1]->id, 'team_side' => 2, 'role' => 'singles'],
    ]);

    return $game;
}
