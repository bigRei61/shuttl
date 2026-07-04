@extends('layouts.app')
@section('title', $event->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('events.index') }}" class="text-sm text-teal-400 hover:text-teal-300">Back to tournaments</a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-6">
    <div class="space-y-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="h-72 bg-gray-800">
                <img src="{{ $event->photoUrl() }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
            </div>
            <div class="p-6">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-teal-900 text-teal-300">{{ str_replace('_', ' ', $event->type) }}</span>
                    <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gray-800 text-gray-300">{{ $event->status }}</span>
                    @if($participation)
                        <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gray-800 text-gray-300">{{ $participation }}</span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-white">{{ $event->name }}</h1>
                <p class="text-gray-400 mt-3">{{ $event->description ?: 'Games for this event will appear here once the host schedules or records them.' }}</p>

                <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Dates</dt>
                        <dd class="text-sm text-white mt-1">{{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}</dd>
                    </div>
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Location</dt>
                        <dd class="text-sm text-white mt-1">{{ $event->location }}</dd>
                    </div>
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <dt class="text-xs uppercase tracking-wide text-gray-500">Host</dt>
                        <dd class="text-sm text-white mt-1">{{ $event->organizer->name ?? 'Shuttl' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white">Games</h2>
                    <p class="text-sm text-gray-500">Ongoing and finished games for this event.</p>
                </div>
            </div>

            @if($event->games->isEmpty())
                <p class="text-gray-500 text-sm px-6 py-8">No games have been recorded for this event yet.</p>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($event->games as $game)
                        @php
                            $team1 = $game->gamePlayers->where('team_side', 1)->map(fn ($entry) => $entry->player->name)->implode(' & ');
                            $team2 = $game->gamePlayers->where('team_side', 2)->map(fn ($entry) => $entry->player->name)->implode(' & ');
                        @endphp
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-white font-semibold">{{ $team1 ?: 'Team 1 TBD' }} <span class="text-gray-500">vs</span> {{ $team2 ?: 'Team 2 TBD' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $game->format)) }} · {{ $game->scheduled_at?->format('M d, Y g:ia') ?? 'No schedule yet' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gray-800 text-gray-300">{{ $game->status }}</span>
                                    @if($game->status === 'completed')
                                        <p class="text-sm text-teal-300 mt-2">{{ $game->team1_sets_won }}-{{ $game->team2_sets_won }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <aside class="space-y-6">
        @if(! $participation && ! $isHost && $event->status === 'open')
            <form method="POST" action="{{ route('events.join', $event) }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                @csrf
                <h2 class="text-lg font-semibold text-white">Join Event</h2>
                <p class="text-sm text-gray-500 mt-1">Your request will be sent to the host for approval.</p>
                <button type="submit" class="mt-4 w-full bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold px-4 py-3 rounded-lg">Request to Join</button>
            </form>
        @endif

        @if($isHost)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h2 class="text-lg font-semibold text-white">Join Requests</h2>
                @if($event->pendingPlayers->isEmpty())
                    <p class="text-sm text-gray-500 mt-3">No pending requests.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach($event->pendingPlayers as $player)
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                                <p class="text-sm font-semibold text-white">{{ $player->name }}</p>
                                <p class="text-xs text-gray-500">{{ $player->email }}</p>
                                <div class="flex gap-2 mt-3">
                                    <form method="POST" action="{{ route('events.join-requests.approve', [$event, $player]) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white text-xs font-semibold px-3 py-2 rounded-lg">Accept</button>
                                    </form>
                                    <form method="POST" action="{{ route('events.join-requests.reject', [$event, $player]) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-gray-200 text-xs font-semibold px-3 py-2 rounded-lg">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h2 class="text-lg font-semibold text-white">Approved Players</h2>
            @if($event->approvedPlayers->isEmpty())
                <p class="text-sm text-gray-500 mt-3">No approved players yet.</p>
            @else
                <div class="mt-4 space-y-2">
                    @foreach($event->approvedPlayers as $player)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-700 flex items-center justify-center text-xs font-semibold text-white">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                            <div>
                                <p class="text-sm text-white">{{ $player->name }}</p>
                                @if((int) $player->id === (int) $event->organizer_id)
                                    <p class="text-xs text-teal-300">Host</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </aside>
</div>
@endsection
