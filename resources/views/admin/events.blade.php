@extends('layouts.app')
@section('title', 'Manage Events')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Events</h1>
    <p class="text-gray-400 mt-1">View, search, and manage all events.</p>
</div>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <form method="GET" action="{{ route('admin.events') }}" class="flex w-full flex-col gap-3 sm:flex-row">
        <div class="relative w-full sm:w-72 lg:w-80">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Search by name or location..."
                   class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 pr-9 text-sm focus:outline-none focus:border-teal-500">
            @if(filled($search))
                <a href="{{ route('admin.events', array_filter(['type' => $type, 'lifecycle' => $lifecycle], fn ($value) => filled($value))) }}"
                   aria-label="Clear search"
                   class="absolute right-2 top-1/2 flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full text-gray-400 hover:bg-gray-800 hover:text-white">
                    x
                </a>
            @endif
        </div>
        <select name="type" onchange="this.form.submit()"
                class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 sm:w-40">
            <option value="">All Types</option>
            <option value="tournament" {{ $type == 'tournament' ? 'selected' : '' }}>Tournament</option>
            <option value="quick_play" {{ $type == 'quick_play' ? 'selected' : '' }}>Quick Play</option>
        </select>
        <select name="lifecycle" onchange="this.form.submit()"
                class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 sm:w-48">
            <option value="" {{ blank($lifecycle) ? 'selected' : '' }}>Ongoing and Open</option>
            <option value="ongoing" {{ $lifecycle == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="open" {{ $lifecycle == 'open' ? 'selected' : '' }}>Open</option>
            <option value="closed" {{ $lifecycle == 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </form>

    <a href="{{ route('admin.featured.create') }}"
       class="inline-flex w-full items-center justify-center bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors duration-200 sm:w-auto sm:flex-shrink-0">
        + Add Featured Tournament
    </a>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full table-fixed text-sm">
        <thead class="bg-gray-800 text-gray-400 text-left">
            <tr>
                <th class="w-[23%] px-6 py-3">Name</th>
                <th class="w-[11%] px-6 py-3">Type</th>
                <th class="w-[18%] px-6 py-3">Location</th>
                <th class="w-[14%] px-6 py-3">Dates</th>
                <th class="w-[14%] px-6 py-3">Status</th>
                <th class="w-[10%] px-6 py-3">Host</th>
                <th class="w-[10%] px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($events as $event)
                @php
                    $isClosed = $event->end_date->lt($today);
                    $isDeactivated = $event->trashed() || $isClosed;
                    $eventTypeLabel = str_replace('_', ' ', $event->type);
                    $eventDateRange = $event->start_date->format('M d').' - '.$event->end_date->format('M d, Y');
                    $eventHostName = $event->organizer->name ?? '-';
                    $eventStatusLabel = match (true) {
                        $event->start_date->lte($today) && $event->end_date->gte($today) => 'Ongoing',
                        $isClosed => 'Closed',
                        default => 'Open',
                    };
                    $eventStatusClass = match ($eventStatusLabel) {
                        'Open' => 'bg-green-950 text-green-300',
                        'Ongoing' => 'bg-yellow-950 text-yellow-300',
                        'Closed' => 'bg-red-950 text-red-300',
                        default => 'bg-gray-800 text-gray-300',
                    };
                @endphp

                <tr class="text-white hover:bg-gray-800/50 {{ $isDeactivated ? 'opacity-70' : '' }}">
                    <td class="px-6 py-4 overflow-hidden">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="min-w-0 truncate" title="{{ $event->name }}">{{ $event->name }}</span>
                            @if($event->is_featured)
                                <span class="flex-shrink-0 text-xs bg-teal-900 text-teal-300 px-2 py-0.5 rounded-full">Featured</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400 capitalize overflow-hidden">
                        <span class="block truncate" title="{{ $eventTypeLabel }}">{{ $eventTypeLabel }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 overflow-hidden">
                        <span class="block truncate" title="{{ $event->location }}">{{ $event->location }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 overflow-hidden">
                        <span class="block truncate" title="{{ $eventDateRange }}">{{ $eventDateRange }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $eventStatusClass }}">
                            {{ $eventStatusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 overflow-hidden">
                        <span class="block truncate" title="{{ $eventHostName }}">{{ $eventHostName }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($isDeactivated)
                            <form method="POST" action="{{ route('admin.events.activate', $event) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-400 hover:text-green-300 text-xs font-semibold">Activate</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.events.deactivate', $event) }}"
                                  onsubmit="return confirm('Deactivate this event?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Deactivate</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No results found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $events->links() }}
</div>
@endsection
