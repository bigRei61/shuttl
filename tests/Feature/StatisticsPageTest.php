<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('starts new players at the configured starting rating', function () {
    $user = User::factory()->create();

    expect($user->fresh()->rating_value)->toBe('400.00');
});

it('shows the statistics page for an authenticated user', function () {
    $user = User::create([
        'name' => 'Test Player',
        'email' => 'player@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
        'rating_value' => 1200,
        'matches_played' => 0,
    ]);

    $this->actingAs($user)
        ->get('/history')
        ->assertOk()
        ->assertSee('Statistics')
        ->assertSee('Matches Played')
        ->assertSee('Recent Matches')
        ->assertSee('grid-template-columns: minmax(220px, .75fr) minmax(0, 2.25fr)', false)
        ->assertSee('--recent-visible-count: 1', false);
});

it('shows opponent, set scores, and rounded rating change for recent matches', function () {
    $host = User::factory()->create();
    $player = User::factory()->create(['name' => 'James Player', 'rating_value' => 384.4, 'matches_played' => 1]);
    $opponent = User::factory()->create(['name' => 'Alex Opponent', 'rating_value' => 415.6, 'matches_played' => 1]);
    $event = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Rated Night',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-20',
        'status' => 'open',
    ]);
    $game = $event->games()->create([
        'format' => 'singles',
        'competitive_type' => 'competitive',
        'winning_side' => 1,
        'team1_sets_won' => 2,
        'team2_sets_won' => 0,
        'played_at' => '2026-07-20 18:00:00',
        'status' => 'completed',
    ]);

    $game->gamePlayers()->createMany([
        ['player_id' => $opponent->id, 'team_side' => 1, 'role' => 'singles'],
        ['player_id' => $player->id, 'team_side' => 2, 'role' => 'singles'],
    ]);
    $game->setScores()->createMany([
        ['set_number' => 1, 'team1_score' => 21, 'team2_score' => 13],
        ['set_number' => 2, 'team1_score' => 21, 'team2_score' => 12],
    ]);
    $game->ratingChanges()->create([
        'player_id' => $player->id,
        'rating_before' => 400,
        'rating_after' => 384.4,
        'delta' => -15.6,
    ]);

    $this->actingAs($player)
        ->get('/history')
        ->assertOk()
        ->assertSee('Rated Night')
        ->assertSee('James Player (384) vs Alex Opponent (416)')
        ->assertDontSee('Rated Night vs Alex Opponent')
        ->assertSee('(13-21, 12-21) 0-2')
        ->assertSeeInOrder(['-16', 'Loss'])
        ->assertSee('Loss')
        ->assertSee('-16')
        ->assertDontSee('-15.60')
        ->assertDontSee('-16.00')
        ->assertSee('result-rating-pill loss', false)
        ->assertSee('--recent-visible-count: 1', false)
        ->assertDontSee('history-list is-scrollable', false);
});

it('scrolls recent matches after four matches', function () {
    $host = User::factory()->create();
    $player = User::factory()->create();
    $opponent = User::factory()->create();
    $event = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Busy Week',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-20',
        'status' => 'open',
    ]);

    foreach (range(1, 5) as $index) {
        $game = $event->games()->create([
            'format' => 'singles',
            'competitive_type' => 'competitive',
            'winning_side' => 1,
            'team1_sets_won' => 2,
            'team2_sets_won' => 0,
            'played_at' => now()->subDays($index),
            'status' => 'completed',
        ]);

        $game->gamePlayers()->createMany([
            ['player_id' => $player->id, 'team_side' => 1, 'role' => 'singles'],
            ['player_id' => $opponent->id, 'team_side' => 2, 'role' => 'singles'],
        ]);
        $game->setScores()->createMany([
            ['set_number' => 1, 'team1_score' => 21, 'team2_score' => 13],
            ['set_number' => 2, 'team1_score' => 21, 'team2_score' => 12],
        ]);
    }

    $this->actingAs($player)
        ->get('/history')
        ->assertOk()
        ->assertSee('history-list is-scrollable', false)
        ->assertSee('--recent-visible-count: 4', false)
        ->assertSee('max-height: calc(var(--recent-row-height) * 4)', false);
});
