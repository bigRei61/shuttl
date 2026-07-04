<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class EventController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $hosts = User::where('role', 'player')->orderBy('name')->get();

        $events = Event::with(['organizer', 'approvedPlayers'])
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('organizer_id', $user->id)
                        ->orWhereHas('approvedPlayers', fn ($players) => $players->whereKey($user->id));
                });
            })
            ->orderByDesc('start_date')
            ->get();

        $casualGames = Game::with(['gamePlayers.player', 'event'])
            ->whereHas('event', fn ($q) => $q->where('type', 'quick_play'))
            ->latest()
            ->take(3)
            ->get();

        return view('events.index', compact('events', 'casualGames', 'hosts'));
    }

    public function create()
    {
        $hosts = User::where('role', 'player')->orderBy('name')->get();

        return view('events.create', compact('hosts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:tournament,quick_play',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => [Rule::excludeIf(! auth()->user()->isAdmin()), 'nullable', 'string'],
            'max_participants' => [Rule::excludeIf(! auth()->user()->isAdmin()), 'nullable', 'integer', 'min:2'],
            'host_id' => [Rule::requiredIf(auth()->user()->isAdmin()), 'nullable', 'exists:users,id'],
            'photo' => ['nullable', File::image()->max('4mb')],
        ]);

        $hostId = auth()->user()->isAdmin() && $request->filled('host_id')
            ? (int) $validated['host_id']
            : auth()->id();

        $event = Event::create([
            'organizer_id' => $hostId,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'location' => $validated['location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'open',
            'is_featured' => false,
            'description' => $validated['description'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'photo_path' => $request->file('photo')?->store('event-photos', 'public'),
        ]);

        $event->players()->syncWithoutDetaching([
            $hostId => ['status' => 'approved', 'responded_at' => now()],
        ]);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event): View
    {
        $event->load([
            'organizer',
            'players',
            'pendingPlayers',
            'approvedPlayers',
            'games' => fn ($query) => $query->with(['gamePlayers.player', 'setScores'])
                ->orderByRaw("case status when 'ongoing' then 0 when 'scheduled' then 1 else 2 end")
                ->latest('scheduled_at'),
        ]);

        $participation = $event->players->firstWhere('id', auth()->id())?->pivot?->status;
        $isHost = (int) $event->organizer_id === (int) auth()->id();

        return view('events.show', compact('event', 'participation', 'isHost'));
    }

    public function join(Event $event): RedirectResponse
    {
        if ($event->status !== 'open') {
            return back()->with('error', 'This event is no longer accepting registrations.');
        }

        $existingStatus = $event->players()
            ->whereKey(auth()->id())
            ->first()?->pivot?->status;

        if ($existingStatus === 'approved') {
            return back()->with('error', 'You are already approved for this event.');
        }

        if ($existingStatus === 'pending') {
            return back()->with('error', 'Your join request is still pending host approval.');
        }

        $event->players()->syncWithoutDetaching([
            auth()->id() => ['status' => 'pending', 'responded_at' => null],
        ]);

        return back()->with('success', 'Your join request was sent to the host.');
    }

    public function approveJoinRequest(Event $event, User $user): RedirectResponse
    {
        if ((int) $event->organizer_id !== (int) auth()->id()) {
            abort(403);
        }

        $event->players()->updateExistingPivot($user->id, [
            'status' => 'approved',
            'responded_at' => now(),
        ]);

        return back()->with('success', "{$user->name}'s join request was approved.");
    }

    public function rejectJoinRequest(Event $event, User $user): RedirectResponse
    {
        if ((int) $event->organizer_id !== (int) auth()->id()) {
            abort(403);
        }

        $event->players()->updateExistingPivot($user->id, [
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', "{$user->name}'s join request was rejected.");
    }
}
