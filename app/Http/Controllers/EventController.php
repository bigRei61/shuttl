<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Game;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('organizer')
            ->orderByDesc('is_featured')
            ->orderByDesc('start_date')
            ->get();

        $casualGames = Game::with(['gamePlayers.player', 'event'])
            ->whereHas('event', fn($q) => $q->where('type', 'quick_play'))
            ->latest()
            ->take(3)
            ->get();

        return view('events.index', compact('events', 'casualGames'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:tournament,quick_play',
            'location'   => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        Event::create([
            'organizer_id' => auth()->id(),
            'name'         => $request->name,
            'type'         => $request->type,
            'location'     => $request->location,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'status'       => 'open',
            'is_featured'  => false,
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['organizer', 'players', 'games.gamePlayers.player', 'games.setScores']);
        $isJoined = $event->players->contains(auth()->id());

        return view('events.show', compact('event', 'isJoined'));
    }

    public function join(Event $event)
    {
        if ($event->status !== 'open') {
            return back()->with('error', 'This event is no longer accepting registrations.');
        }

        if ($event->players->contains(auth()->id())) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $event->players()->attach(auth()->id());

        return back()->with('success', 'You have successfully registered for this event.');
    }
}