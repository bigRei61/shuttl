<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class AdminController extends Controller
{
    public function index()
    {
        $today = today()->toDateString();

        $stats = [
            'total_players' => User::where('role', 'player')->count(),
            'active_events' => Event::count(),
            'featured_count' => Event::featured()->count(),
            'ongoing_events' => Event::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ----- PLAYERS -----

    public function players(Request $request)
    {
        $search = $request->input('search');

        $players = User::withTrashed()
            ->where('role', 'player')
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

    public function deactivatePlayer(User $user): RedirectResponse
    {
        if (! $user->trashed()) {
            $user->delete();
        }

        return back()->with('success', 'Player deactivated successfully.');
    }

    public function activatePlayer(User $user): RedirectResponse
    {
        if ($user->trashed()) {
            $user->restore();
        }

        return back()->with('success', 'Player activated successfully.');
    }

    public function deletePlayer(User $user): RedirectResponse
    {
        return $this->deactivatePlayer($user);
    }

    // ----- EVENTS -----

    public function events(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $lifecycle = $request->input('lifecycle');
        $lifecycle = in_array($lifecycle, ['ongoing', 'open', 'closed'], true) ? $lifecycle : null;
        $today = today();
        $todayDate = $today->toDateString();

        $events = Event::withTrashed()
            ->with(['organizer' => fn ($query) => $query->withTrashed()])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->where(function ($query) use ($lifecycle, $todayDate) {
                match ($lifecycle) {
                    'ongoing' => $query->where('start_date', '<=', $todayDate)
                        ->where('end_date', '>=', $todayDate),
                    'open' => $query->where('start_date', '>', $todayDate),
                    'closed' => $query->where('end_date', '<', $todayDate),
                    default => $query->where('end_date', '>=', $todayDate),
                };
            })
            ->orderByRaw(
                'case when start_date <= ? and end_date >= ? then 0 when start_date > ? then 1 else 2 end',
                [$todayDate, $todayDate, $todayDate],
            )
            ->orderByDesc('is_featured')
            ->orderBy('start_date')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.events', compact('events', 'lifecycle', 'search', 'today', 'type'));
    }

    public function deactivateEvent(Event $event): RedirectResponse
    {
        if (! $event->trashed()) {
            $event->delete();
        }

        return back();
    }

    public function activateEvent(Event $event): RedirectResponse
    {
        if ($event->trashed()) {
            $event->restore();
        }

        if ($event->end_date->lt(today())) {
            $event->update(['end_date' => today()->toDateString()]);
        }

        return back();
    }

    public function deleteEvent(Event $event): RedirectResponse
    {
        return $this->deactivateEvent($event);
    }

    // ----- FEATURED TOURNAMENTS -----

    public function createFeatured()
    {
        $hosts = User::where('role', 'player')->orderBy('name')->get();

        return view('admin.featured-create', compact('hosts'));
    }

    public function storeFeatured(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:2',
            'description' => 'nullable|string',
            'host_id' => 'required|exists:users,id',
            'photo' => ['nullable', File::image()->max('4mb')],
        ], [
            'end_date.after_or_equal' => 'End date must be on or after the start date.',
        ]);

        $event = Event::create([
            'organizer_id' => $request->host_id,
            'name' => $request->name,
            'type' => 'tournament',
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_participants' => $request->max_participants,
            'description' => $request->description,
            'status' => 'open',
            'is_featured' => true,
            'photo_path' => $request->file('photo')?->store('event-photos', 'public'),
        ]);

        $event->players()->syncWithoutDetaching([
            $request->host_id => ['status' => 'approved', 'responded_at' => now()],
        ]);

        return redirect()->route('admin.events')
            ->with('success', 'Featured tournament created successfully.');
    }
}
