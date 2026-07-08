<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Shuttl</title>
    <link href="img/favicon.ico" rel="shortcut icon"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}"/>
    <style>
        .header-section {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .events-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            align-items: stretch;
        }
        .event-slide { min-width: 0; }
        .event-card {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
            overflow: hidden;
            background: #fff;
            border: 1px solid #d6dee7;
            border-radius: 8px;
            box-shadow: 0 16px 38px rgba(19, 19, 19, .07);
        }
        .review-cover {
            display: block;
            height: 210px;
            min-height: 210px;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .event-text {
            padding: 24px;
            flex: 1;
            min-width: 0;
            min-height: 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .event-text h4 {
            color: #131313;
            font-size: 21px;
            line-height: 1.35;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .event-text h4 a { color: #131313; display: block; overflow: hidden; text-decoration: none; text-overflow: ellipsis; white-space: nowrap; }
        .event-text h4 a:hover { color: #4EDFCE; }
        .event-text .ti-text { flex: 1; min-width: 0; }
        .event-text .ti-text ul { list-style: none; padding: 0; margin: 0; }
        .event-text .ti-text ul li { color: #878787; font-size: 13px; margin-bottom: 5px; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .event-text .ti-text ul li span { color: #131313; font-weight: 600; margin-right: 6px; }
        .event-description {
            font-size: 13px;
            color: #878787;
            margin: 12px 0 0;
            line-height: 1.55;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .featured-badge {
            position: absolute; top: 12px; left: 12px;
            background: #4EDFCE; color: #131313;
            font-size: 11px; font-weight: 700; letter-spacing: .5px;
            text-transform: uppercase; padding: 4px 12px; border-radius: 999px;
        }
        .status-badge {
            display: inline-block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; padding: 3px 10px;
            border-radius: 999px; margin-bottom: 10px;
        }
        .status-open { background: #e6fdf9; color: #0fa37f; }
        .status-ongoing { background: #fff3e0; color: #e07c3a; }
        .status-completed { background: #f0f0f0; color: #878787; }
        .btn-join {
            display: inline-flex; align-items: center; justify-content: center;
            background: transparent;
            border: 2px solid #4EDFCE; color: #131313;
            font-size: 13px; font-weight: 600; padding: 7px 20px;
            border-radius: 999px; cursor: pointer; transition: all .2s;
            text-decoration: none;
        }
        .btn-join:hover { background: #4EDFCE; }
        .event-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: auto;
        }
        .event-actions form { margin: 0; }
        .event-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 35px;
            padding: 7px 16px;
            border-radius: 999px;
            background: #eef2f6;
            color: #131313;
            font-size: 13px;
            font-weight: 700;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .event-pagination { margin-top: 32px; }
        .event-pagination .pagination { justify-content: center; margin-bottom: 0; }
        .event-pagination .page-link { color: #131313; border-color: #d6dee7; }
        .event-pagination .page-item.active .page-link { background: #4EDFCE; border-color: #4EDFCE; color: #131313; }
        .event-pagination .page-link:focus { box-shadow: 0 0 0 3px rgba(78, 223, 206, .18); }
        .casual-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 28px 20px;
            text-align: center; min-height: 240px;
        }
        .player-avatar {
            width: 64px; height: 64px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 700; margin: 0 auto 10px;
        }
        .versus { color: #4EDFCE; font-size: 20px; font-weight: 700; margin: 10px 0; }
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.72);
            padding: 24px;
        }
        .modal-overlay.is-open { display: flex; }
        .event-modal {
            width: min(720px, 100%);
            max-height: calc(100vh - 48px);
            overflow-y: auto;
            background: #fff;
            color: #131313;
            border: 1px solid rgba(78, 223, 206, 0.5);
            border-radius: 8px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.42);
        }
        .event-modal-header {
            padding: 24px 28px 14px;
            border-bottom: 1px solid #edf2f5;
        }
        .event-modal-header h3 {
            color: #131313;
            font-size: 26px;
            font-weight: 700;
            margin: 0;
        }
        .event-modal-body { padding: 24px 28px 28px; }
        .modal-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .modal-field { margin-bottom: 16px; }
        .modal-field label {
            display: block;
            color: #131313;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
        }
        .modal-field input,
        .modal-field select,
        .modal-field textarea {
            width: 100%;
            border: 1px solid #d6dee7;
            border-radius: 8px;
            color: #131313;
            background: #fff;
            font-size: 14px;
            padding: 12px 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .modal-field textarea { min-height: 112px; resize: vertical; }
        .modal-help {
            color: #878787;
            font-size: 12px;
            margin: -2px 0 8px;
        }
        .modal-field input:focus,
        .modal-field select:focus,
        .modal-field textarea:focus {
            border-color: #4EDFCE;
            box-shadow: 0 0 0 3px rgba(78, 223, 206, 0.18);
        }
        .modal-error {
            color: #ff205f;
            font-size: 12px;
            margin-top: 6px;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 8px;
        }
        .modal-cancel,
        .modal-submit {
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 24px;
            cursor: pointer;
            transition: all .2s;
        }
        .modal-cancel {
            background: #fff;
            border: 1px solid #d6dee7;
            color: #131313;
        }
        .modal-cancel:hover { border-color: #4EDFCE; }
        .modal-submit {
            background: #4EDFCE;
            border: 1px solid #4EDFCE;
            color: #131313;
        }
        .modal-submit:hover { filter: brightness(0.96); }
        @media (max-width: 640px) {
            .events-grid { grid-template-columns: 1fr; }
            .review-cover { height: 190px; min-height: 190px; }
            .event-text { padding: 20px; }
            .event-actions > * { width: 100%; }
            .event-actions .site-btn,
            .event-actions .btn-join,
            .event-actions .event-pill { text-align: center; }
            .event-actions form .btn-join { width: 100%; }
            .modal-grid { grid-template-columns: 1fr; gap: 0; }
            .event-modal-header,
            .event-modal-body { padding-left: 20px; padding-right: 20px; }
            .modal-actions { flex-direction: column-reverse; }
            .modal-cancel,
            .modal-submit { width: 100%; }
        }
        @media (min-width: 641px) and (max-width: 991px) {
            .events-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('partials.header')

    <!-- Page info -->
    <section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/1.png') }}">
        <div class="pi-content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 text-white">
                        <h2>Events</h2>
                        <p>Discover casual games, ranked matches, and local badminton events happening near you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events grid -->
    <section class="page-section tournament-page spad">
        <div class="container">

            @if(session('success'))
                <div style="background:#e6fdf9; border:1px solid #4EDFCE; color:#0fa37f; padding:12px 18px; border-radius:8px; margin-bottom:24px; font-size:14px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:#fdecea; border:1px solid #ff205f; color:#ff205f; padding:12px 18px; border-radius:8px; margin-bottom:24px; font-size:14px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <div class="tournament-title" style="margin:0;">Active Events</div>
                @auth
                    <button type="button" id="open-event-modal" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px; border:0;">+ Create Event</button>
                @endauth
            </div>

            @if($events->isEmpty())
                <p style="color:#878787; text-align:center; padding:60px 0;">No events available right now.</p>
            @else
                <div class="events-grid">
                    @foreach($events as $event)
                        @php
                            $participation = $event->players->firstWhere('id', auth()->id())?->pivot?->status;
                            $isHost = (int) $event->organizer_id === (int) auth()->id();
                        @endphp
                        <div class="event-slide">
                            <article class="event-card">
                                <a href="{{ route('events.show', $event) }}" class="review-cover" style="background-image: url('{{ $event->photoUrl() }}');">
                                    @if($event->is_featured)
                                        <div class="featured-badge">Featured</div>
                                    @endif
                                </a>
                                <div class="event-text">
                                    <div>
                                        <span class="status-badge status-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                                        <h4>
                                            <a href="{{ route('events.show', $event) }}">{{ $event->name }}</a>
                                        </h4>
                                    </div>
                                    <div class="ti-text">
                                        <ul>
                                            <li><span>Type:</span> {{ ucfirst(str_replace('_', ' ', $event->type)) }}</li>
                                            <li><span>Starts:</span> {{ $event->start_date->format('M d, Y') }}</li>
                                            <li><span>Ends:</span> {{ $event->end_date->format('M d, Y') }}</li>
                                            <li><span>Location:</span> {{ $event->location }}</li>
                                            <li><span>Host:</span> {{ $event->organizer->name ?? 'Shuttl' }}</li>
                                            <li><span>Players:</span> {{ $event->approved_players_count ?? 0 }} approved</li>
                                            @if($event->max_participants)
                                                <li><span>Slots:</span> {{ $event->max_participants }} participants</li>
                                            @endif
                                        </ul>
                                        @if($event->description)
                                            <p class="event-description">{{ Str::limit($event->description, 120) }}</p>
                                        @endif
                                    </div>
                                    <div class="event-actions">
                                        <a href="{{ route('events.show', $event) }}" class="site-btn btn-sm" style="font-size:13px; padding:7px 18px;">View Details</a>
                                        @if($isHost)
                                            <span class="event-pill">Host</span>
                                        @elseif($participation === 'approved')
                                            <span class="event-pill">Joined</span>
                                        @elseif($participation === 'pending')
                                            <span class="event-pill">Pending Approval</span>
                                        @elseif($event->status === 'open')
                                            <form method="POST" action="{{ route('events.join', $event) }}">
                                                @csrf
                                                <button type="submit" class="btn-join">Request to Join</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                @if(method_exists($events, 'hasPages') && $events->hasPages())
                    <div class="event-pagination">
                        {{ $events->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @endif
        </div>
    </section>

    @auth
        <div id="event-modal-overlay" class="modal-overlay" aria-hidden="true">
            <div class="event-modal" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
                <div class="event-modal-header">
                    <h3 id="event-modal-title">Create Event</h3>
                </div>
                <div class="event-modal-body">
                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf

                        @if(auth()->user()->isAdmin())
                            <div class="modal-field">
                                <label for="event-host-search">Event Host</label>
                                <p class="modal-help">Search by player name or email, then choose the host.</p>
                                <input id="event-host-search" type="search" autocomplete="off" placeholder="Search player name or email">
                                <select id="event-host-select" name="host_id" style="margin-top:8px;">
                                    <option value="">Select host</option>
                                    @foreach($hosts ?? collect() as $host)
                                        <option value="{{ $host->id }}" data-search="{{ Str::lower($host->name.' '.$host->email) }}" {{ old('host_id') == $host->id ? 'selected' : '' }}>
                                            {{ $host->name }} ({{ $host->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('host_id') <p class="modal-error">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div class="modal-field">
                            <label for="event_name">Event Name</label>
                            <input id="event_name" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Sunday Doubles Cup">
                            @error('name') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="{{ auth()->user()->isAdmin() ? 'modal-grid' : '' }}">
                            <div class="modal-field">
                                <label for="event_type">Event Type</label>
                                <select id="event_type" name="type">
                                    <option value="">Select type</option>
                                    <option value="tournament" {{ old('type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                                    <option value="quick_play" {{ old('type') == 'quick_play' ? 'selected' : '' }}>Quick Play</option>
                                </select>
                                @error('type') <p class="modal-error">{{ $message }}</p> @enderror
                            </div>

                            @if(auth()->user()->isAdmin())
                                <div class="modal-field">
                                    <label for="max_participants">Number of Participants</label>
                                    <input id="max_participants" type="number" name="max_participants" min="2" value="{{ old('max_participants') }}" placeholder="e.g. 16">
                                    @error('max_participants') <p class="modal-error">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="modal-field">
                            <label for="event_location">Location</label>
                            <input id="event_location" type="text" name="location" value="{{ old('location') }}" placeholder="e.g. MTDY Badminton Court">
                            @error('location') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="modal-grid">
                            <div class="modal-field">
                                <label for="start_date">Start Date</label>
                                <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date') <p class="modal-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="modal-field">
                                <label for="end_date">End Date</label>
                                <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date') <p class="modal-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if(auth()->user()->isAdmin())
                            <div class="modal-field">
                                <label for="event_description">Description</label>
                                <textarea id="event_description" name="description" placeholder="Describe the tournament, prizes, rules, etc.">{{ old('description') }}</textarea>
                                @error('description') <p class="modal-error">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div class="modal-field">
                            <label for="event_photo">Event Photo</label>
                            <input id="event_photo" type="file" name="photo" accept="image/*">
                            @error('photo') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="modal-actions">
                            <button type="button" id="cancel-event-modal" class="modal-cancel">Cancel</button>
                            <button type="submit" class="modal-submit">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <script>
    (function () {
        const overlay = document.getElementById('event-modal-overlay');
        const openButton = document.getElementById('open-event-modal');
        const cancelButton = document.getElementById('cancel-event-modal');

        if (!overlay || !openButton || !cancelButton) return;

        function openModal() {
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        openButton.addEventListener('click', openModal);
        cancelButton.addEventListener('click', closeModal);
        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) {
                closeModal();
            }
        });

        @if(isset($errors) && $errors->any())
            openModal();
        @endif
    })();

    (function () {
        const searchInput = document.getElementById('event-host-search');
        const hostSelect = document.getElementById('event-host-select');

        if (!searchInput || !hostSelect) return;

        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim().toLowerCase();

            Array.from(hostSelect.options).forEach(function (option, index) {
                if (index === 0) {
                    option.hidden = false;

                    return;
                }

                option.hidden = query !== '' && !option.dataset.search.includes(query);
            });
        });
    })();
    </script>

    <!-- Casual Matches -->
    <section class="review-section review-dark spad set-bg" data-setbg="{{ asset('landing/img/review-bg-2.jpg') }}">
        <div class="container">
            <div class="section-title text-white">
                <h2>Casual Matches</h2>
            </div>

            @if($casualGames->isEmpty())
                <p style="color:#9ca3af; text-align:center; padding:30px 0;">No casual matches recorded yet.</p>
            @else
                <div class="row text-white">
                    @foreach($casualGames as $game)
                        @php
                            $team1 = $game->gamePlayers->where('team_side', 1);
                            $team2 = $game->gamePlayers->where('team_side', 2);
                            $colors1 = ['#4EDFCE', '#2bb3a3'];
                            $colors2 = ['#ff205f', '#c9174c'];
                        @endphp
                        <div class="col-lg-4 col-md-6" style="margin-bottom:24px;">
                            <div class="casual-item">
                                <!-- Team 1 -->
                                <div>
                                    @foreach($team1 as $i => $gp)
                                        <div class="player-avatar" style="background: {{ $colors1[$i % 2] }}; color: #131313;">
                                            {{ strtoupper(substr($gp->player->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    <p style="font-weight:600; margin-bottom:0;">
                                        {{ $team1->map(fn($gp) => $gp->player->name)->implode(' & ') }}
                                    </p>
                                </div>

                                <div class="versus">vs</div>

                                <!-- Team 2 -->
                                <div>
                                    @foreach($team2 as $i => $gp)
                                        <div class="player-avatar" style="background: {{ $colors2[$i % 2] }}; color: #fff;">
                                            {{ strtoupper(substr($gp->player->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    <p style="font-weight:600; margin-bottom:0;">
                                        {{ $team2->map(fn($gp) => $gp->player->name)->implode(' & ') }}
                                    </p>
                                </div>

                                <!-- Meta -->
                                <div style="margin-top:14px; font-size:12px; color:#9ca3af; border-top:1px solid rgba(255,255,255,0.1); padding-top:12px;">
                                    <span style="text-transform:uppercase; letter-spacing:.5px;">{{ ucfirst(str_replace('_', ' ', $game->format)) }}</span>
                                    &nbsp;·&nbsp; {{ $game->event->name }}
                                    @if($game->status === 'completed')
                                        &nbsp;·&nbsp; <span style="color:#4EDFCE;">Completed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center pt-4">
                @auth
                    <a href="{{ route('events.index') }}" class="site-btn">Find a Match</a>
                @else
                    <a href="{{ route('login') }}" class="site-btn">Login to Join</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="{{ route('landing') }}">Home</a></li>
                <li><a href="{{ route('events.index') }}">Events</a></li>
                <li><a href="{{ route('login') }}">Calendar</a></li>
                <li><a href="{{ route('login') }}">Statistics</a></li>
                <li><a href="{{ route('login') }}">Tournament</a></li>
            </ul>
            <p class="copyright">Copyright &copy;{{ date('Y') }} Shuttl. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.marquee.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>
</html>
