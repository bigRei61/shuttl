<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;

class TournamentController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $tournaments = Event::query()
            ->where('type', 'tournament')
            ->with(['organizer', 'approvedPlayers'])
            ->withCount(['approvedPlayers', 'games'])
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('organizer_id', $user->id)
                        ->orWhereHas('approvedPlayers', fn ($players) => $players->whereKey($user->id));
                });
            })
            ->orderByDesc('start_date')
            ->get();

        return view('tournament', compact('tournaments'));
    }
}
