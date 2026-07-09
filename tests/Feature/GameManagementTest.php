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
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertRedirect(route('events.show', $event));

    $game = Game::with('gamePlayers')->first();

    expect($game)->not->toBeNull()
        ->and($game->format)->toBe('singles')
        ->and($game->competitive_type)->toBe('competitive')
        ->and($game->scheduled_at)->toBeNull()
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
            'team_one_players' => [$players[0]->id, $players[1]->id],
            'team_two_players' => [$players[2]->id, $players[3]->id],
        ])
        ->assertRedirect(route('events.show', $event));

    $game = Game::with('gamePlayers')->first();

    expect($game)->not->toBeNull()
        ->and($game->format)->toBe('doubles')
        ->and($game->scheduled_at)->toBeNull()
        ->and($game->gamePlayers)->toHaveCount(4)
        ->and($game->gamePlayers->pluck('role')->unique()->values()->all())->toBe(['doubles_partner']);
});

it('forbids non-hosts from creating games', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $otherPlayer = User::factory()->create();

    $this->actingAs($otherPlayer)
        ->post(route('games.store', $event), [
            'format' => 'singles',
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
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[0]->id],
        ])
        ->assertInvalid(['team_two_players']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'doubles',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertInvalid(['team_one_players', 'team_two_players']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'singles',
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

it('validates badminton set and match score rules', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $game = scheduledGame($event, $players);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 24, 'team2_score' => 20],
                ['team1_score' => 21, 'team2_score' => 18],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores.0.team1_score' => 'Margin must be exactly 2 between 22–29.']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 31, 'team2_score' => 29],
                ['team1_score' => 21, 'team2_score' => 18],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores.0.team1_score' => 'No set score can exceed 30.']);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores' => "Match can't end 1-0 — third set required unless 2 sets already won."]);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
                ['team1_score' => 21, 'team2_score' => 17],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores' => 'Match must end once a side wins 2 sets.']);

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 30, 'team2_score' => 29],
                ['team1_score' => 22, 'team2_score' => 20],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    $game->refresh();

    expect($game->status)->toBe('completed')
        ->and($game->team1_sets_won)->toBe(2)
        ->and($game->team2_sets_won)->toBe(0);
});

it('updates singles ratings with a larger reward for an upset', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $players[0]->update(['rating_value' => 400, 'matches_played' => 0]);
    $players[1]->update(['rating_value' => 1200, 'matches_played' => 0]);
    $game = scheduledGame($event, $players);

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    $winner = $players[0]->fresh();
    $loser = $players[1]->fresh();
    $ratingChanges = $game->fresh()->ratingChanges()->get()->keyBy('player_id');

    expect($winner->rating_value)->toBe('431.68')
        ->and($winner->matches_played)->toBe(1)
        ->and($loser->rating_value)->toBe('1168.32')
        ->and($loser->matches_played)->toBe(1)
        ->and($ratingChanges)->toHaveCount(2)
        ->and((float) $ratingChanges[$winner->id]->delta)->toBe(31.68)
        ->and((float) $ratingChanges[$loser->id]->delta)->toBe(-31.68);
});

it('updates doubles ratings from average team ratings with equal teammate deltas', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(4);
    $players[0]->update(['rating_value' => 1200, 'matches_played' => 0]);
    $players[1]->update(['rating_value' => 1600, 'matches_played' => 0]);
    $players[2]->update(['rating_value' => 1400, 'matches_played' => 0]);
    $players[3]->update(['rating_value' => 1400, 'matches_played' => 0]);
    $game = scheduledDoublesGame($event, $players);

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    $ratingChanges = $game->fresh()->ratingChanges()->get()->keyBy('player_id');

    expect($players[0]->fresh()->rating_value)->toBe('1216.00')
        ->and($players[1]->fresh()->rating_value)->toBe('1616.00')
        ->and($players[2]->fresh()->rating_value)->toBe('1384.00')
        ->and($players[3]->fresh()->rating_value)->toBe('1384.00')
        ->and($players[0]->fresh()->matches_played)->toBe(1)
        ->and($players[1]->fresh()->matches_played)->toBe(1)
        ->and($players[2]->fresh()->matches_played)->toBe(1)
        ->and($players[3]->fresh()->matches_played)->toBe(1)
        ->and((float) $ratingChanges[$players[0]->id]->delta)->toBe(16.0)
        ->and((float) $ratingChanges[$players[1]->id]->delta)->toBe(16.0)
        ->and((float) $ratingChanges[$players[2]->id]->delta)->toBe(-16.0)
        ->and((float) $ratingChanges[$players[3]->id]->delta)->toBe(-16.0);
});

it('replaces prior rating changes when a final score is corrected', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $players[0]->update(['rating_value' => 400, 'matches_played' => 0]);
    $players[1]->update(['rating_value' => 1200, 'matches_played' => 0]);
    $game = scheduledGame($event, $players);

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    expect($players[0]->fresh()->rating_value)->toBe('431.68')
        ->and($players[0]->fresh()->matches_played)->toBe(1)
        ->and($players[1]->fresh()->rating_value)->toBe('1168.32')
        ->and($players[1]->fresh()->matches_played)->toBe(1)
        ->and($game->fresh()->ratingChanges)->toHaveCount(2);
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
        ->assertSee('SET 1 (21-18)')
        ->assertSee('SET 2 (21-19)')
        ->assertSee('2-0')
        ->assertDontSee('10:00am')
        ->assertDontSee('Schedule Game')
        ->assertDontSee('Record Final Score');

    expect($host->exists)->toBeTrue();
});

it('shows host game controls without a scheduled at field', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    scheduledGame($event, $players);

    $this->actingAs($host)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee('Schedule Game')
        ->assertSee('Record Final Score')
        ->assertSee('syncPlayerSlots', false)
        ->assertSee('syncUnavailablePlayerOptions', false)
        ->assertSee('is-player-unavailable', false)
        ->assertSee('validateFinalScoreForm', false)
        ->assertSee('Scores cannot be negative.', false)
        ->assertSee('pendingClearValue', false)
        ->assertSee('data-event-exit-transition-link', false)
        ->assertSee('Select Singles Player')
        ->assertDontSee('>Singles Player</option>', false)
        ->assertDontSee('>Doubles Player</option>', false)
        ->assertDontSee('Required player')
        ->assertDontSee('Doubles partner')
        ->assertDontSee('Record final score')
        ->assertDontSee('Scheduled At');
});

it('shows final score errors inside the score form', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);
    $game = scheduledGame($event, $players);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 24, 'team2_score' => 20],
                ['team1_score' => 21, 'team2_score' => 18],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores.0.team1_score' => 'Margin must be exactly 2 between 22–29.']);

    $this->actingAs($host)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee('score-form-error', false)
        ->assertSee('Margin must be exactly 2 between 22–29.')
        ->assertDontSee('<li>Margin must be exactly 2 between 22–29.</li>', false);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->put(route('games.result', $game), [
            'record_game_id' => $game->id,
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
                ['team1_score' => 21, 'team2_score' => 17],
            ],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['set_scores' => 'Match must end once a side wins 2 sets.']);

    $this->actingAs($host)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee('score-form-error', false)
        ->assertSee('Match must end once a side wins 2 sets.')
        ->assertDontSee('<li>Match must end once a side wins 2 sets.</li>', false);
});

it('shows game assignment errors beside each team heading', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2);

    $this->actingAs($host)
        ->from(route('events.show', $event))
        ->post(route('games.store', $event), [
            'format' => 'doubles',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertRedirect(route('events.show', $event))
        ->assertInvalid(['team_one_players', 'team_two_players']);

    $this->actingAs($host)
        ->get(route('events.show', $event))
        ->assertSuccessful()
        ->assertSee('Select Doubles Player')
        ->assertDontSee('>Doubles Player</option>', false)
        ->assertDontSee('>Doubles Partner</option>', false)
        ->assertSee('Team 1 must have 2 player(s) for this format.')
        ->assertSee('Team 2 must have 2 player(s) for this format.')
        ->assertSee('manage-error', false)
        ->assertSee('background: #fdecea', false)
        ->assertDontSee('<li>Team 1 must have 2 player(s) for this format.</li>', false)
        ->assertDontSee('<li>Team 2 must have 2 player(s) for this format.</li>', false);
});

it('records quick play game results without rating changes', function () {
    [$event, $host, $players] = eventWithApprovedPlayers(2, ['type' => 'quick_play']);
    $players[0]->update(['rating_value' => 700, 'matches_played' => 3]);
    $players[1]->update(['rating_value' => 900, 'matches_played' => 4]);

    $this->actingAs($host)
        ->post(route('games.store', $event), [
            'format' => 'singles',
            'team_one_players' => [$players[0]->id],
            'team_two_players' => [$players[1]->id],
        ])
        ->assertRedirect(route('events.show', $event));

    $game = Game::firstOrFail();

    expect($game->competitive_type)->toBe('unranked');

    $this->actingAs($host)
        ->put(route('games.result', $game), [
            'set_scores' => [
                ['team1_score' => 21, 'team2_score' => 18],
                ['team1_score' => 21, 'team2_score' => 19],
            ],
        ])
        ->assertRedirect(route('events.show', $event));

    expect($game->fresh()->ratingChanges)->toHaveCount(0)
        ->and($players[0]->fresh()->rating_value)->toBe('700.00')
        ->and($players[0]->fresh()->matches_played)->toBe(3)
        ->and($players[1]->fresh()->rating_value)->toBe('900.00')
        ->and($players[1]->fresh()->matches_played)->toBe(4);
});

/**
 * @return array{0: Event, 1: User, 2: Collection<int, User>}
 */
function eventWithApprovedPlayers(int $playerCount, array $attributes = []): array
{
    $host = User::factory()->create();
    $players = User::factory()->count($playerCount)->create();
    $event = Event::create(array_merge([
        'organizer_id' => $host->id,
        'name' => 'Summer Cup',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-20',
        'status' => 'open',
    ], $attributes));

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

function scheduledDoublesGame(Event $event, Collection $players): Game
{
    $game = $event->games()->create([
        'format' => 'doubles',
        'competitive_type' => 'competitive',
        'status' => 'scheduled',
    ]);

    $game->gamePlayers()->createMany([
        ['player_id' => $players[0]->id, 'team_side' => 1, 'role' => 'doubles_partner'],
        ['player_id' => $players[1]->id, 'team_side' => 1, 'role' => 'doubles_partner'],
        ['player_id' => $players[2]->id, 'team_side' => 2, 'role' => 'doubles_partner'],
        ['player_id' => $players[3]->id, 'team_side' => 2, 'role' => 'doubles_partner'],
    ]);

    return $game;
}
