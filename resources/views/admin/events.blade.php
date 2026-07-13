@extends('layouts.app')
@section('title', 'Manage Events')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Events</h1>
    <p class="text-gray-400 mt-1">View, search, and manage all events.</p>
</div>

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <form method="GET" action="{{ route('admin.events') }}" class="flex w-full flex-col gap-3 sm:flex-row">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Search by name or location..."
               class="w-full max-w-md bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
        <select name="type" onchange="this.form.submit()"
                class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 sm:w-44">
            <option value="">All Types</option>
            <option value="tournament" {{ $type == 'tournament' ? 'selected' : '' }}>Tournament</option>
            <option value="quick_play" {{ $type == 'quick_play' ? 'selected' : '' }}>Quick Play</option>
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
                <tr class="text-white hover:bg-gray-800/50">
                    <td class="px-6 py-4 break-words">
                        <span>{{ $event->name }}</span>
                        @if($event->is_featured)
                            <span class="ml-2 text-xs bg-teal-900 text-teal-300 px-2 py-0.5 rounded-full">Featured</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 capitalize break-words">{{ str_replace('_', ' ', $event->type) }}</td>
                    <td class="px-6 py-4 text-gray-400 break-words">{{ $event->location }}</td>
                    <td class="px-6 py-4 text-gray-400 break-words">
                        {{ $event->start_date->format('M d') }} – {{ $event->end_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.events.status', $event) }}">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()"
                                    class="bg-gray-800 border border-gray-700 text-white rounded-lg px-2 py-1 text-xs">
                                <option value="open" {{ $event->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="ongoing" {{ $event->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $event->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $event->organizer->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.events.delete', $event) }}"
                              onsubmit="return confirm('Are you sure you want to delete this event?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Delete</button>
                        </form>
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
