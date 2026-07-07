<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name }} - Shuttl</title>

    <link href="{{ asset('landing/img/favicon.ico') }}" rel="shortcut icon"/>
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

        .event-manage-page {
            background: #f5f7fa;
        }

        .event-back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #737373;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
        }

        .event-back-link:hover {
            color: #131313;
        }

        .event-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 24px;
            align-items: start;
        }

        .event-panel {
            background: #fff;
            border: 1px solid #e4ebf0;
            border-radius: 8px;
            box-shadow: 0 12px 32px rgba(19, 19, 19, 0.06);
            overflow: hidden;
        }

        .event-panel-body {
            padding: 28px;
        }

        .event-cover {
            height: 320px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .event-cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(19,19,19,0.05) 0%, rgba(19,19,19,0.38) 100%);
        }

        .event-badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .event-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            background: #e6fdf9;
            color: #0f9c8c;
        }

        .event-badge.dark {
            background: #eef2f6;
            color: #596273;
        }

        .event-title {
            color: #131313;
            font-size: 34px;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .event-description {
            color: #5a6472;
            font-size: 15px;
            margin-bottom: 0;
        }

        .event-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 24px;
        }

        .event-meta-box {
            background: #f8fafc;
            border: 1px solid #eef2f6;
            border-radius: 8px;
            padding: 16px;
        }

        .event-meta-box span {
            display: block;
            color: #9a9a9a;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .event-meta-box strong {
            color: #131313;
            font-size: 14px;
            font-weight: 500;
        }

        .event-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 24px 28px;
            border-bottom: 1px solid #edf2f5;
        }

        .event-section-head h3 {
            color: #131313;
            font-size: 24px;
            margin: 0;
        }

        .event-section-head p {
            color: #878787;
            font-size: 13px;
            margin: 5px 0 0;
        }

        .game-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .game-row {
            padding: 24px 28px;
            border-bottom: 1px solid #edf2f5;
        }

        .game-row:last-child {
            border-bottom: 0;
        }

        .game-row-main {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
        }

        .game-title {
            color: #131313;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .game-vs {
            color: #b5b5b5;
            font-size: 12px;
            font-weight: 500;
            margin: 0 8px;
            text-transform: uppercase;
        }

        .game-meta {
            color: #878787;
            font-size: 13px;
            margin-bottom: 0;
        }

        .game-score {
            text-align: right;
            min-width: 120px;
        }

        .game-score strong {
            display: block;
            color: #0f9c8c;
            font-size: 20px;
            margin-top: 8px;
        }

        .game-sets {
            color: #878787;
            font-size: 12px;
            margin-top: 10px;
        }

        .game-sets span {
            color: #131313;
        }

        .host-form {
            margin-top: 18px;
            background: #f8fafc;
            border: 1px solid #eef2f6;
            border-radius: 8px;
            padding: 18px;
        }

        .host-form summary {
            color: #0f9c8c;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
        }

        .side-panel {
            position: sticky;
            top: 92px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .side-card {
            background: #fff;
            border: 1px solid #e4ebf0;
            border-radius: 8px;
            box-shadow: 0 10px 26px rgba(19, 19, 19, 0.05);
            padding: 24px;
        }

        .side-card h4 {
            color: #131313;
            font-size: 20px;
            margin-bottom: 6px;
        }

        .side-card p {
            color: #878787;
            font-size: 13px;
        }

        .manage-field {
            margin-bottom: 14px;
        }

        .manage-field label,
        .manage-field-title {
            display: block;
            color: #131313;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .manage-field input,
        .manage-field select {
            width: 100%;
            border: 1px solid #d6dee7;
            border-radius: 8px;
            color: #131313;
            background: #fff;
            font-size: 14px;
            padding: 11px 13px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .manage-field input:focus,
        .manage-field select:focus {
            border-color: #4EDFCE;
            box-shadow: 0 0 0 3px rgba(78, 223, 206, 0.18);
        }

        .score-grid {
            display: grid;
            grid-template-columns: 74px minmax(0, 1fr) minmax(0, 1fr);
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .score-grid span {
            color: #878787;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .player-list {
            list-style: none;
            margin: 14px 0 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .player-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4b5563;
            font-size: 13px;
        }

        .player-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #4EDFCE;
            color: #131313;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .request-card {
            background: #f8fafc;
            border: 1px solid #eef2f6;
            border-radius: 8px;
            padding: 14px;
            margin-top: 12px;
        }

        .request-card strong {
            color: #131313;
            display: block;
            font-size: 14px;
        }

        .request-card span {
            color: #878787;
            font-size: 12px;
        }

        .request-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .event-alert {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .event-alert.error {
            background: #fdecea;
            border: 1px solid #ff205f;
            color: #bf174a;
        }

        .event-empty {
            color: #878787;
            font-size: 14px;
            padding: 38px 28px;
            margin: 0;
        }

        @media (max-width: 991px) {
            .event-shell {
                grid-template-columns: 1fr;
            }

            .side-panel {
                position: static;
            }
        }

        @media (max-width: 767px) {
            .event-cover {
                height: 220px;
            }

            .event-title {
                font-size: 28px;
            }

            .event-meta-grid {
                grid-template-columns: 1fr;
            }

            .game-row-main {
                flex-direction: column;
            }

            .game-score {
                text-align: left;
            }

            .score-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('partials.header')

    <section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/1.png') }}">
        <div class="pi-content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-8 text-white">
                        <h2>{{ $event->name }}</h2>
                        <p>{{ $isHost ? 'Manage join requests, schedule games, and record final scores.' : 'View event details, scheduled games, and final scores.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="event-manage-page page-section spad">
        <div class="container">
            <a href="{{ route('events.index') }}" class="event-back-link"><i class="fa fa-long-arrow-left"></i> Back to events</a>

            @if($errors->any())
                <div class="event-alert error">
                    <strong>Please fix the highlighted game fields.</strong>
                    <ul style="margin:8px 0 0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="event-shell">
                <div>
                    <div class="event-panel" style="margin-bottom:24px;">
                        <div class="event-cover" style="background-image: url('{{ $event->photoUrl() }}');"></div>
                        <div class="event-panel-body">
                            <div class="event-badge-row">
                                <span class="event-badge">{{ str_replace('_', ' ', $event->type) }}</span>
                                <span class="event-badge dark">{{ $event->status }}</span>
                                @if($participation)
                                    <span class="event-badge dark">{{ $participation }}</span>
                                @endif
                            </div>
                            <h1 class="event-title">{{ $event->name }}</h1>
                            <p class="event-description">{{ $event->description ?: 'Games for this event will appear here once the host schedules or records them.' }}</p>

                            <div class="event-meta-grid">
                                <div class="event-meta-box">
                                    <span>Dates</span>
                                    <strong>{{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}</strong>
                                </div>
                                <div class="event-meta-box">
                                    <span>Location</span>
                                    <strong>{{ $event->location }}</strong>
                                </div>
                                <div class="event-meta-box">
                                    <span>Host</span>
                                    <strong>{{ $event->organizer->name ?? 'Shuttl' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-panel">
                        <div class="event-section-head">
                            <div>
                                <h3>Games</h3>
                                <p>Scheduled and finished games for this event.</p>
                            </div>
                        </div>

                        @if($event->games->isEmpty())
                            <p class="event-empty">No games have been recorded for this event yet.</p>
                        @else
                            <ul class="game-list">
                                @foreach($event->games as $game)
                                    @php
                                        $team1 = $game->gamePlayers->where('team_side', 1)->map(fn ($entry) => $entry->player->name)->implode(' & ');
                                        $team2 = $game->gamePlayers->where('team_side', 2)->map(fn ($entry) => $entry->player->name)->implode(' & ');
                                    @endphp
                                    <li class="game-row">
                                        <div class="game-row-main">
                                            <div>
                                                <div class="game-title">
                                                    {{ $team1 ?: 'Team 1 TBD' }} <span class="game-vs">vs</span> {{ $team2 ?: 'Team 2 TBD' }}
                                                </div>
                                                <p class="game-meta">{{ ucfirst(str_replace('_', ' ', $game->format)) }} · {{ $game->scheduled_at?->format('M d, Y g:ia') ?? 'No schedule yet' }}</p>
                                                @if($game->setScores->isNotEmpty())
                                                    <p class="game-sets">
                                                        Sets:
                                                        @foreach($game->setScores as $setScore)
                                                            <span>Set {{ $setScore->set_number }} {{ $setScore->team1_score }}-{{ $setScore->team2_score }}</span>@if(! $loop->last), @endif
                                                        @endforeach
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="game-score">
                                                <span class="event-badge dark">{{ $game->status }}</span>
                                                @if($game->status === 'completed')
                                                    <strong>{{ $game->team1_sets_won }}-{{ $game->team2_sets_won }}</strong>
                                                @endif
                                            </div>
                                        </div>

                                        @if($isHost)
                                            <details class="host-form">
                                                <summary>Record final score</summary>
                                                <form method="POST" action="{{ route('games.result', $game) }}" style="margin-top:16px;">
                                                    @csrf
                                                    @method('PUT')

                                                    @for($setNumber = 1; $setNumber <= 3; $setNumber++)
                                                        @php
                                                            $existingSet = $game->setScores->firstWhere('set_number', $setNumber);
                                                        @endphp
                                                        <div class="score-grid">
                                                            <span>Set {{ $setNumber }}</span>
                                                            <div class="manage-field" style="margin-bottom:0;">
                                                                <input type="number" name="set_scores[{{ $setNumber - 1 }}][team1_score]" min="0"
                                                                    value="{{ old("set_scores.".($setNumber - 1).".team1_score", $existingSet?->team1_score) }}"
                                                                    placeholder="Team 1 score">
                                                            </div>
                                                            <div class="manage-field" style="margin-bottom:0;">
                                                                <input type="number" name="set_scores[{{ $setNumber - 1 }}][team2_score]" min="0"
                                                                    value="{{ old("set_scores.".($setNumber - 1).".team2_score", $existingSet?->team2_score) }}"
                                                                    placeholder="Team 2 score">
                                                            </div>
                                                        </div>
                                                    @endfor

                                                    <button type="submit" class="site-btn btn-sm" style="border:0; font-size:13px; padding:8px 22px; margin-top:16px;">Save Final Score</button>
                                                </form>
                                            </details>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <aside class="side-panel">
                    @if($isHost)
                        <div class="side-card">
                            <h4>Schedule Game</h4>
                            <p>Assign approved players, choose the match format, and set the schedule.</p>

                            <form method="POST" action="{{ route('games.store', $event) }}" style="margin-top:18px;">
                                @csrf

                                <div class="manage-field">
                                    <label for="format">Format</label>
                                    <select id="format" name="format">
                                        <option value="singles" {{ old('format') === 'singles' ? 'selected' : '' }}>Singles</option>
                                        <option value="doubles" {{ old('format') === 'doubles' ? 'selected' : '' }}>Doubles</option>
                                    </select>
                                </div>

                                <div class="manage-field">
                                    <label for="scheduled_at">Scheduled At</label>
                                    <input id="scheduled_at" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                </div>

                                <div class="manage-field">
                                    <span class="manage-field-title">Team 1</span>
                                    @for($slot = 0; $slot < 2; $slot++)
                                        <select name="team_one_players[]" style="margin-bottom:8px;">
                                            <option value="">{{ $slot === 0 ? 'Required player' : 'Doubles partner' }}</option>
                                            @foreach($event->approvedPlayers as $player)
                                                <option value="{{ $player->id }}" {{ (string) old("team_one_players.{$slot}") === (string) $player->id ? 'selected' : '' }}>{{ $player->name }}</option>
                                            @endforeach
                                        </select>
                                    @endfor
                                </div>

                                <div class="manage-field">
                                    <span class="manage-field-title">Team 2</span>
                                    @for($slot = 0; $slot < 2; $slot++)
                                        <select name="team_two_players[]" style="margin-bottom:8px;">
                                            <option value="">{{ $slot === 0 ? 'Required player' : 'Doubles partner' }}</option>
                                            @foreach($event->approvedPlayers as $player)
                                                <option value="{{ $player->id }}" {{ (string) old("team_two_players.{$slot}") === (string) $player->id ? 'selected' : '' }}>{{ $player->name }}</option>
                                            @endforeach
                                        </select>
                                    @endfor
                                </div>

                                <button type="submit" class="site-btn" style="border:0; width:100%;">Schedule Game</button>
                            </form>
                        </div>
                    @endif

                    @if(! $participation && ! $isHost && $event->status === 'open')
                        <form method="POST" action="{{ route('events.join', $event) }}" class="side-card">
                            @csrf
                            <h4>Join Event</h4>
                            <p>Your request will be sent to the host for approval.</p>
                            <button type="submit" class="site-btn" style="border:0; width:100%; margin-top:8px;">Request to Join</button>
                        </form>
                    @endif

                    @if($isHost)
                        <div class="side-card">
                            <h4>Join Requests</h4>
                            @if($event->pendingPlayers->isEmpty())
                                <p>No pending requests.</p>
                            @else
                                @foreach($event->pendingPlayers as $player)
                                    <div class="request-card">
                                        <strong>{{ $player->name }}</strong>
                                        <span>{{ $player->email }}</span>
                                        <div class="request-actions">
                                            <form method="POST" action="{{ route('events.join-requests.approve', [$event, $player]) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="site-btn btn-sm" style="border:0; font-size:12px; padding:7px 18px;">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('events.join-requests.reject', [$event, $player]) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="site-btn btn-sm" style="background:#eef2f6; color:#131313; border:0; font-size:12px; padding:7px 18px;">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <div class="side-card">
                        <h4>Approved Players</h4>
                        @if($event->approvedPlayers->isEmpty())
                            <p>No approved players yet.</p>
                        @else
                            <ul class="player-list">
                                @foreach($event->approvedPlayers as $player)
                                    <li>
                                        <span class="player-avatar">{{ strtoupper(substr($player->name, 0, 1)) }}</span>
                                        <span>
                                            {{ $player->name }}
                                            @if((int) $player->id === (int) $event->organizer_id)
                                                <small style="display:block; color:#0f9c8c;">Host</small>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>
</html>
