@extends('layouts.app')
@section('title', 'Add Featured Tournament')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Add Featured Tournament</h1>
    <p class="text-gray-400 mt-1">Create a Shuttl-sponsored tournament. This will be marked as featured.</p>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-xl p-8 max-w-2xl">
    <form method="POST" action="{{ route('admin.featured.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-400 mb-1">Tournament Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-700' }}
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="e.g. MTDY 2026 Championships">
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('location') ? 'border-red-500' : 'border-gray-700' }}
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="e.g. MTDY Badminton Court">
            @error('location')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                       class="w-full bg-gray-800 border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-700' }}
                              text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
                @error('start_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full bg-gray-800 border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-700' }}
                              text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
                @error('end_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Number of Participants</label>
            <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="2"
                   class="w-full bg-gray-800 border {{ $errors->has('max_participants') ? 'border-red-500' : 'border-gray-700' }}
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="e.g. 16">
            @error('max_participants')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">Description</label>
            <textarea name="description" rows="4"
                      class="w-full bg-gray-800 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-700' }}
                             text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                      placeholder="Describe the tournament, prizes, rules, etc.">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200">
                Create Featured Tournament
            </button>
            <a href="{{ route('admin.events') }}"
               class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection