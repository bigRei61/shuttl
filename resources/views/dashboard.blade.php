@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">
        Hello, {{ auth()->user()->name }} 👋
    </h1>
    <p class="text-gray-400 mt-1">Welcome back to Shuttl.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Current Rating</p>
        <p class="text-3xl font-bold text-teal-400 mt-1">
            {{ number_format(auth()->user()->rating_value, 2) }}
        </p>
        <p class="text-gray-600 text-xs mt-1">RR Points</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Matches Played</p>
        <p class="text-3xl font-bold text-white mt-1">
            {{ auth()->user()->matches_played }}
        </p>
        <p class="text-gray-600 text-xs mt-1">Total games</p>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <p class="text-gray-400 text-sm">Member Since</p>
        <p class="text-3xl font-bold text-white mt-1">
            {{ auth()->user()->created_at->format('Y') }}
        </p>
        <p class="text-gray-600 text-xs mt-1">{{ auth()->user()->created_at->format('M d, Y') }}</p>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('events.index') }}"
           class="bg-gray-900 border border-gray-800 hover:border-teal-700 rounded-xl p-6 transition-colors duration-200">
            <p class="text-teal-400 font-semibold">Browse Events →</p>
            <p class="text-gray-500 text-sm mt-1">Join a tournament or quick play session</p>
        </a>
        <a href="{{ route('history') }}"
           class="bg-gray-900 border border-gray-800 hover:border-teal-700 rounded-xl p-6 transition-colors duration-200">
            <p class="text-teal-400 font-semibold">View Play History →</p>
            <p class="text-gray-500 text-sm mt-1">See your past matches and rating changes</p>
        </a>
    </div>
</div>
@endsection