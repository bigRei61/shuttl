<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordGameResultRequest;
use App\Http\Requests\StoreGameRequest;
use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
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
                'competitive_type' => $event->type === 'quick_play' ? 'unranked' : 'competitive',
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
            $game->loadMissing(['gamePlayers.player', 'ratingChanges.player']);
            $this->reverseRatingChanges($game);

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

            $game->refresh()->loadMissing('gamePlayers.player');
            $this->applyRatingChanges($game);
        });

        $game->loadMissing('event');

        return redirect()->route('events.show', $game->event)
            ->with('success', 'Game result recorded successfully.');
    }

    private function reverseRatingChanges(Game $game): void
    {
        foreach ($game->ratingChanges as $ratingChange) {
            $player = $ratingChange->player;

            if (! $player) {
                continue;
            }

            $player->forceFill([
                'rating_value' => round(max(0, (float) $player->rating_value - (float) $ratingChange->delta), 2),
                'matches_played' => max(0, (int) $player->matches_played - 1),
            ])->save();
        }

        $game->ratingChanges()->delete();
        $game->unsetRelation('ratingChanges');
    }

    private function applyRatingChanges(Game $game): void
    {
        $game->loadMissing('event');

        if ($game->competitive_type !== 'competitive' || $game->event?->type === 'quick_play' || $game->winning_side === null) {
            return;
        }

        $playersByTeam = $game->gamePlayers->groupBy('team_side');
        $teamOnePlayers = $playersByTeam->get(1, collect())->pluck('player')->filter()->values();
        $teamTwoPlayers = $playersByTeam->get(2, collect())->pluck('player')->filter()->values();

        if ($teamOnePlayers->isEmpty() || $teamTwoPlayers->isEmpty()) {
            return;
        }

        $teamOneExpected = $this->expectedScore(
            $this->teamRating($teamOnePlayers),
            $this->teamRating($teamTwoPlayers),
        );

        $teamTwoExpected = 1 - $teamOneExpected;

        $this->applyTeamRatingDelta($game, $teamOnePlayers, $this->ratingDelta((int) $game->winning_side === 1, $teamOneExpected));
        $this->applyTeamRatingDelta($game, $teamTwoPlayers, $this->ratingDelta((int) $game->winning_side === 2, $teamTwoExpected));
    }

    /**
     * @param  Collection<int, User>  $players
     */
    private function teamRating(Collection $players): float
    {
        return (float) $players->avg(fn (User $player): float => (float) $player->rating_value);
    }

    private function expectedScore(float $rating, float $opponentRating): float
    {
        return 1 / (1 + (10 ** (($opponentRating - $rating) / User::RATING_SCALE)));
    }

    private function ratingDelta(bool $won, float $expectedScore): float
    {
        return round(User::RATING_K_FACTOR * (($won ? 1 : 0) - $expectedScore), 2);
    }

    /**
     * @param  Collection<int, User>  $players
     */
    private function applyTeamRatingDelta(Game $game, Collection $players, float $delta): void
    {
        foreach ($players as $player) {
            $ratingBefore = (float) $player->rating_value;
            $ratingAfter = round(max(0, $ratingBefore + $delta), 2);

            $game->ratingChanges()->create([
                'player_id' => $player->id,
                'rating_before' => $ratingBefore,
                'rating_after' => $ratingAfter,
                'delta' => round($ratingAfter - $ratingBefore, 2),
            ]);

            $player->forceFill([
                'rating_value' => $ratingAfter,
                'matches_played' => (int) $player->matches_played + 1,
            ])->save();
        }
    }
}
