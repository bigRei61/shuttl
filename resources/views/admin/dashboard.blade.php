@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
    <p class="text-gray-400 mt-1">Manage players, events, and featured tournaments.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Total Players</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_players'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Total Events</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $stats['total_events'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Featured Tournaments</p>
        <p class="text-3xl font-bold text-teal-400 mt-1">{{ $stats['featured_count'] }}</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Active Events</p>
        <p class="text-3xl font-bold text-white mt-1">{{ $stats['active_events'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('admin.players') }}"
       class="bg-gray-900 border border-gray-800 hover:border-teal-700 rounded-xl p-6 transition-colors duration-200">
        <p class="text-teal-400 font-semibold">Manage Players →</p>
        <p class="text-gray-500 text-sm mt-1">View and search all registered players</p>
    </a>
    <a href="{{ route('admin.events') }}"
       class="bg-gray-900 border border-gray-800 hover:border-teal-700 rounded-xl p-6 transition-colors duration-200">
        <p class="text-teal-400 font-semibold">Manage Events →</p>
        <p class="text-gray-500 text-sm mt-1">View, search, and edit all events</p>
    </a>
    <a href="{{ route('admin.featured.create') }}"
       class="bg-gray-900 border border-teal-700 rounded-xl p-6 transition-colors duration-200">
        <p class="text-teal-400 font-semibold">+ Add Featured Tournament →</p>
        <p class="text-gray-500 text-sm mt-1">Create a Shuttl-sponsored tournament</p>
    </a>
</div>
@endsection