<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Shuttl</title>
    <link href="{{ asset('landing/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .header-logo img { width:120px }
        .events-list { max-width:1000px; margin:28px auto; }
        .event-card { background:#fff; border:1px solid #e6eef0; padding:16px; border-radius:8px; margin-bottom:12px }
        body { background:#f8fafc; font-family:Inter, sans-serif; }
    </style>
</head>
<body>
    <header class="header-section">
        <div class="container">
            <a class="header-logo" href="{{ route('landing') }}">
                <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl">
            </a>
            <div class="user-panel">
                @auth
                    <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
                @else
                    <a href="{{ route('login') }}">Login</a> / <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="events-list">
        <div class="container">
            <h1>Events</h1>
            @if($events->isEmpty())
                <p>No events available.</p>
            @else
                @foreach($events as $event)
                    <div class="event-card">
                        <h3>{{ $event->name }}</h3>
                        <p>{{ $event->location }} — {{ $event->start_date?->toFormattedDateString() ?? 'TBD' }}</p>
                        <p>{{ Illuminate\Support\Str::limit($event->description, 160) }}</p>
                        <a href="{{ route('events.show', $event) }}">View</a>
                    </div>
                @endforeach
            @endif
        </div>
    </main>
</body>
</html>
