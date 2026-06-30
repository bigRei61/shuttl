@extends('layouts.app')
@section('title', 'Manage Players')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Players</h1>
    <p class="text-gray-400 mt-1">View and search all registered players.</p>
</div>

<form method="GET" action="{{ route('admin.players') }}" class="mb-6">
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Search by name or email..."
           class="w-full max-w-md bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
</form>

<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 text-gray-400 text-left">
            <tr>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Phone</th>
                <th class="px-6 py-3">Rating</th>
                <th class="px-6 py-3">Matches</th>
                <th class="px-6 py-3">Joined</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($players as $player)
                <tr class="text-white hover:bg-gray-800/50">
                    <td class="px-6 py-4">{{ $player->name }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $player->email }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $player->phone ?? '—' }}</td>
                    <td class="px-6 py-4 text-teal-400">{{ number_format($player->rating_value, 2) }}</td>
                    <td class="px-6 py-4">{{ $player->matches_played }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $player->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.players.delete', $player) }}"
                              onsubmit="return confirm('Are you sure you want to remove this player?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Remove</button>
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
    {{ $players->links() }}
</div>
@endsection