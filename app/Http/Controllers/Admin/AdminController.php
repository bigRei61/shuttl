<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_players' => User::where('role', 'player')->count(),
            'total_events'  => Event::count(),
            'featured_count'=> Event::featured()->count(),
            'active_events' => Event::whereIn('status', ['open', 'ongoing'])->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ----- PLAYERS -----

    public function players(Request $request)
    {
        $search = $request->input('search');

        $players = User::where('role', 'player')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.players', compact('players', 'search'));
    }

    public function deletePlayer(User $user)
    {
        $user->delete();
        return back()->with('success', 'Player removed successfully.');
    }

    // ----- EVENTS -----

    public function events(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $events = Event::with('organizer')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderByDesc('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('admin.events', compact('events', 'search', 'type'));
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted successfully.');
    }

    public function updateEventStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:open,ongoing,completed',
        ]);

        $event->update(['status' => $request->status]);

        return back()->with('success', 'Event status updated.');
    }

    // ----- FEATURED TOURNAMENTS -----

    public function createFeatured()
    {
        return view('admin.featured-create');
    }

    public function storeFeatured(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'location'         => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:2',
            'description'      => 'required|string',
        ], [
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
        ]);

        Event::create([
            'organizer_id'     => auth()->id(),
            'name'             => $request->name,
            'type'             => 'tournament',
            'location'         => $request->location,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'max_participants' => $request->max_participants,
            'description'      => $request->description,
            'status'           => 'open',
            'is_featured'      => true,
        ]);

        return redirect()->route('admin.events')
            ->with('success', 'Featured tournament created successfully.');
    }
}