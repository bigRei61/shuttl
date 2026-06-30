<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Basic implementation: fetch upcoming games tied to the user or their events
        $userId = auth()->id();

        $games = Game::with('event')
            ->whereNotNull('scheduled_at')
            ->where(function ($q) use ($userId) {
                $q->whereHas('gamePlayers', fn($q2) => $q2->where('player_id', $userId))
                  ->orWhereHas('event', fn($q3) => $q3->where('organizer_id', $userId));
            })
            ->orderBy('scheduled_at')
            ->get();

        $events = $games->map(function ($game) {
            $scheduledAt = $game->scheduled_at;
            $endTime = $scheduledAt ? $scheduledAt->copy()->addHours(2) : null;

            return [
                'id' => $game->id,
                'title' => optional($game->event)->name ?? 'Match',
                'date' => $scheduledAt ? $scheduledAt->toDateString() : null,
                'time' => $scheduledAt ? $scheduledAt->format('g:ia') . ' - ' . $endTime->format('g:ia') : null,
                'location' => optional($game->event)->location,
                'event_id' => $game->event_id,
                'status' => $game->status ?? 'Upcoming',
            ];
        });

        return view('calendar', ['events' => $events]);
    }
}
