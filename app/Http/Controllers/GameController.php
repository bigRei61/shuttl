<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordGameResultRequest;
use App\Http\Requests\StoreGameRequest;
use App\Models\Event;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function create(Event $event): RedirectResponse
    {
        return redirect()->route('events.show', $event);
    }

    public function store(StoreGameRequest $request, Event $event): RedirectResponse
    {
        DB::transaction(function () use ($event, $request): void {
            $game = $event->games()->create([
                'format' => $request->validated('format'),
                'competitive_type' => 'competitive',
                'scheduled_at' => $request->validated('scheduled_at'),
                'status' => 'scheduled',
            ]);

            $role = $request->validated('format') === 'singles' ? 'singles' : 'doubles_partner';

            foreach ($request->teamOnePlayers() as $playerId) {
                $game->gamePlayers()->create([
                    'player_id' => $playerId,
                    'team_side' => 1,
                    'role' => $role,
                ]);
            }

            foreach ($request->teamTwoPlayers() as $playerId) {
                $game->gamePlayers()->create([
                    'player_id' => $playerId,
                    'team_side' => 2,
                    'role' => $role,
                ]);
            }
        });

        return redirect()->route('events.show', $event)
            ->with('success', 'Game scheduled successfully.');
    }

    public function show(Game $game): RedirectResponse
    {
        return redirect()->route('events.show', $game->event);
    }

    public function recordResult(RecordGameResultRequest $request, Game $game): RedirectResponse
    {
        DB::transaction(function () use ($game, $request): void {
            $teamOneSetsWon = 0;
            $teamTwoSetsWon = 0;

            $game->setScores()->delete();

            foreach ($request->setScores() as $index => $setScore) {
                $game->setScores()->create([
                    'set_number' => $index + 1,
                    'team1_score' => $setScore['team1_score'],
                    'team2_score' => $setScore['team2_score'],
                ]);

                if ($setScore['team1_score'] > $setScore['team2_score']) {
                    $teamOneSetsWon++;
                } else {
                    $teamTwoSetsWon++;
                }
            }

            $game->update([
                'winning_side' => $teamOneSetsWon > $teamTwoSetsWon ? 1 : 2,
                'team1_sets_won' => $teamOneSetsWon,
                'team2_sets_won' => $teamTwoSetsWon,
                'played_at' => now(),
                'status' => 'completed',
            ]);
        });

        $game->loadMissing('event');

        return redirect()->route('events.show', $game->event)
            ->with('success', 'Game result recorded successfully.');
    }
}
