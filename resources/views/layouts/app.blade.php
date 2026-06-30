<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shuttl - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-all duration-200; }
        .sidebar-link.active { @apply text-white bg-teal-700; }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 min-h-screen bg-gray-900 border-r border-gray-800/60 flex flex-col fixed left-0 top-0">

        <!-- Logo + name -->
        <div class="flex items-center gap-3 px-5 h-16 border-b border-gray-800/60">
            <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl" class="w-20 h-20 object-contain">
        </div>

        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">
            @if(auth()->user()->isAdmin())
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2">Admin Panel</p>

                <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.players') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('admin.players') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.66 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0zm7 8a7 7 0 00-14 0"/>
                    </svg>
                    Manage Players
                </a>

                <a href="{{ route('admin.events') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('admin.events') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Manage Events
                </a>

                <a href="{{ route('admin.featured.create') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('admin.featured.*') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Featured Tournament
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('dashboard') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>

                <a href="{{ route('events.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('events.*') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Events
                </a>

                <a href="{{ route('calendar') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('calendar') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Calendar
                </a>

                <a href="{{ route('history') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('history') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Play History
                </a>

                <a href="{{ route('profile') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                        {{ request()->routeIs('profile') ? 'bg-teal-600/15 text-teal-400' : 'text-gray-400 hover:text-white hover:bg-gray-800/60' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>
            @endif
        </nav>

        <!-- User info + logout -->
        <div class="px-3 py-4 border-t border-gray-800/60">
            <div class="flex items-center gap-3 px-2 mb-2">
                <div class="w-9 h-9 rounded-full bg-teal-600 flex items-center justify-center text-sm font-semibold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate leading-tight">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-red-900/10 transition-all duration-150">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <main class="ml-64 flex-1 p-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-teal-900 border border-teal-600 text-teal-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-900 border border-red-600 text-red-300 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>