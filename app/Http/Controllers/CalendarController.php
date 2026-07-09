<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $events = Event::query()
            ->select(['id', 'organizer_id', 'name', 'type', 'location', 'start_date', 'end_date', 'status'])
            ->whereIn('status', ['open', 'ongoing'])
            ->whereDate('end_date', '>=', today())
            ->where(function ($query) use ($user) {
                $query->whereBelongsTo($user, 'organizer')
                    ->orWhereHas('approvedPlayers', fn ($players) => $players->whereKey($user->id));
            })
            ->orderBy('start_date')
            ->orderBy('name')
            ->get()
            ->map(fn (Event $event): array => [
                'id' => $event->id,
                'title' => $event->name,
                'location' => $event->location,
                'start_date' => $event->start_date->toDateString(),
                'end_date' => $event->end_date->toDateString(),
                'url' => route('events.show', $event),
                'role' => (int) $event->organizer_id === (int) $user->id ? 'host' : 'joined',
                'status' => $event->status,
                'type' => $event->type,
            ]);

        return view('calendar', [
            'events' => $events,
            'roleColors' => [
                'joined' => '#4EDFCE',
                'host' => '#EA7632',
            ],
        ]);
    }
}
