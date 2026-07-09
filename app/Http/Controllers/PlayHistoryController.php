<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Contracts\View\View;

class PlayHistoryController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $completedGames = Game::query()
            ->whereHas('gamePlayers', fn ($query) => $query->where('player_id', $user->id))
            ->where('status', 'completed')
            ->with([
                'event',
                'gamePlayers.player',
                'ratingChanges',
                'setScores',
            ])
            ->latest('played_at')
            ->get();

        $stats = [
            'matches_played' => $completedGames->count(),
            'singles_played' => 0,
            'doubles_played' => 0,
            'singles_wins' => 0,
            'doubles_wins' => 0,
        ];

        foreach ($completedGames as $game) {
            $playerEntry = $game->gamePlayers->firstWhere('player_id', $user->id);

            if (! $playerEntry) {
                continue;
            }

            $isWin = $game->winning_side !== null && (int) $game->winning_side === (int) $playerEntry->team_side;
            $isDoubles = in_array($game->format, ['doubles', 'mixed_doubles'], true);

            if ($isDoubles) {
                $stats['doubles_played']++;
                if ($isWin) {
                    $stats['doubles_wins']++;
                }
            } else {
                $stats['singles_played']++;
                if ($isWin) {
                    $stats['singles_wins']++;
                }
            }
        }

        $stats['singles_winrate'] = $stats['singles_played'] > 0
            ? round(($stats['singles_wins'] / $stats['singles_played']) * 100, 1)
            : 0;

        $stats['doubles_winrate'] = $stats['doubles_played'] > 0
            ? round(($stats['doubles_wins'] / $stats['doubles_played']) * 100, 1)
            : 0;

        $recentMatches = $completedGames->take(6)->map(function ($game) use ($user) {
            $playerEntry = $game->gamePlayers->firstWhere('player_id', $user->id);
            $isWin = $game->winning_side !== null && (int) $game->winning_side === (int) $playerEntry?->team_side;
            $ratingDelta = $game->ratingChanges->firstWhere('player_id', $user->id)?->delta;
            $roundedRatingDelta = $ratingDelta === null ? null : (int) round((float) $ratingDelta);
            $playerTeamSide = (int) $playerEntry?->team_side ?: 1;

            return [
                'id' => $game->id,
                'event' => $game->event?->name ?? 'Private Match',
                'player' => $this->teamPlayerLabels($game, $playerTeamSide),
                'opponent' => $this->teamPlayerLabels($game, $playerTeamSide === 1 ? 2 : 1),
                'format' => ucfirst(str_replace('_', ' ', $game->format)),
                'played_at' => $game->played_at ? $game->played_at->format('M d, Y') : 'TBD',
                'result' => $isWin ? 'Win' : 'Loss',
                'score' => $this->matchScore($game, $playerTeamSide),
                'score_detail' => $this->setScoreSummary($game, $playerTeamSide),
                'rating_delta' => $roundedRatingDelta,
                'rating_delta_label' => $roundedRatingDelta === null ? null : ($roundedRatingDelta === 0 ? '0' : sprintf('%+d', $roundedRatingDelta)),
                'rating_delta_trend' => match (true) {
                    $roundedRatingDelta === null => null,
                    $roundedRatingDelta > 0 => 'positive',
                    $roundedRatingDelta < 0 => 'negative',
                    default => 'neutral',
                },
            ];
        });

        return view('history', compact('user', 'stats', 'recentMatches'));
    }

    private function teamPlayerLabels(Game $game, int $teamSide): string
    {
        $players = $game->gamePlayers
            ->where('team_side', $teamSide)
            ->map(fn (GamePlayer $gamePlayer): ?string => $this->playerRatingLabel($game, $gamePlayer))
            ->filter()
            ->implode(' & ');

        return $players !== '' ? $players : 'Team TBD';
    }

    private function playerRatingLabel(Game $game, GamePlayer $gamePlayer): ?string
    {
        if (! $gamePlayer->player) {
            return null;
        }

        $rating = $game->ratingChanges->firstWhere('player_id', $gamePlayer->player_id)?->rating_after
            ?? $gamePlayer->player->rating_value;

        return $gamePlayer->player->name.' ('.number_format(round((float) $rating), 0).')';
    }

    private function matchScore(Game $game, int $playerTeamSide): string
    {
        $playerSetsWon = $playerTeamSide === 2 ? $game->team2_sets_won : $game->team1_sets_won;
        $opponentSetsWon = $playerTeamSide === 2 ? $game->team1_sets_won : $game->team2_sets_won;

        return $playerSetsWon.'-'.$opponentSetsWon;
    }

    private function setScoreSummary(Game $game, int $playerTeamSide): string
    {
        if ($game->setScores->isEmpty()) {
            return $this->matchScore($game, $playerTeamSide);
        }

        $setScores = $game->setScores
            ->map(function ($setScore) use ($playerTeamSide): string {
                $playerScore = $playerTeamSide === 2 ? $setScore->team2_score : $setScore->team1_score;
                $opponentScore = $playerTeamSide === 2 ? $setScore->team1_score : $setScore->team2_score;

                return $playerScore.'-'.$opponentScore;
            })
            ->implode(', ');

        return "({$setScores}) ".$this->matchScore($game, $playerTeamSide);
    }
}
