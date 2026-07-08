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
            ->with(['organizer', 'approvedPlayers'])
            ->withCount(['approvedPlayers', 'games'])
            ->whereIn('status', ['open', 'ongoing'])
            ->whereDate('end_date', '>=', today())
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->whereBelongsTo($user, 'organizer')
                        ->orWhereHas('approvedPlayers', fn ($players) => $players->whereKey($user->id));
                });
            })
            ->orderBy('start_date')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('tournament', compact('tournaments'));
    }
}
