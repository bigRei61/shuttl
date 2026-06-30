@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<h2 class="text-2xl font-bold text-white mb-2">Create account</h2>
<p class="text-gray-400 text-sm mb-8">Join the Shuttl community</p>

<form method="POST" action="{{ route('register.post') }}" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm text-gray-400 mb-1">Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}"
               class="w-full bg-gray-800 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-700' }}
                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="John Doe">
        @error('name')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }}
                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="you@example.com">
        @error('email')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full bg-gray-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }}
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="••••••••">
            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full bg-gray-800 border border-gray-700
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="••••••••">
        </div>
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-1">Phone Number</label>
        <input type="text" name="phone" value="{{ old('phone') }}"
               class="w-full bg-gray-800 border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-700' }}
                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="+63 912 345 6789">
        @error('phone')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Gender</label>
            <select name="gender"
                    class="w-full bg-gray-800 border {{ $errors->has('gender') ? 'border-red-500' : 'border-gray-700' }}
                           text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
                <option value="">Select</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('date_of_birth') ? 'border-red-500' : 'border-gray-700' }}
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
            @error('date_of_birth')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <button type="submit"
            class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3 rounded-lg transition-colors duration-200 mt-2">
        Create Account
    </button>

    <p class="text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300">Sign in</a>
    </p>
</form>
@endsection