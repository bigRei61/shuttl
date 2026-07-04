<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class PlayHistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $completedGames = Game::whereHas('gamePlayers', function ($query) use ($user) {
                $query->where('player_id', $user->id);
            })
            ->where('status', 'completed')
            ->with(['event', 'gamePlayers.player'])
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

            return [
                'id' => $game->id,
                'event' => $game->event?->name ?? 'Private Match',
                'format' => ucfirst(str_replace('_', ' ', $game->format)),
                'played_at' => $game->played_at ? $game->played_at->format('M d, Y') : 'TBD',
                'result' => $isWin ? 'Win' : 'Loss',
                'score' => $game->team1_sets_won . '-' . $game->team2_sets_won,
            ];
        });

        return view('history', compact('user', 'stats', 'recentMatches'));
    }
}
