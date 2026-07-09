<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - Shuttl</title>
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}" />
    <style>
        body { background: #f4f7fb; }
        .header-section { padding: 18px 0; margin-bottom: 0; border-bottom: 1px solid #4EDFCE; position: sticky; top: 0; z-index: 1000; }
        .page-info-section.set-bg {
            height: 499px !important;
            min-height: 499px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            background-image: url('{{ asset('landing/img/slider-2.png') }}') !important;
            background-size: cover !important;
            background-position: center !important;
        }
        .page-info-section.set-bg .pi-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .stats-page-section { background: #f4f7fb; padding-top: 24px; padding-bottom: 60px; }
        .stats-shell { background: #fff; border: 1px solid #dfe8f0; border-radius: 18px; box-shadow: 0 16px 40px rgba(17,24,39,.06); }
        .stats-overview { padding: 28px; border-bottom: 1px solid #edf2f7; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .stat-card { background: linear-gradient(135deg, #f8fcfb 0%, #eef8f7 100%); border: 1px solid #dff4ef; border-radius: 14px; padding: 18px; }
        .stat-card .label { font-size: 12px; text-transform: uppercase; letter-spacing: .4px; color: #6b7280; margin-bottom: 8px; font-weight: 700; }
        .stat-card .value { font-size: 24px; font-weight: 700; color: #131313; }
        .stats-content { --recent-row-height: 86px; --stats-panel-chrome-height: 58px; padding: 28px; display: grid; grid-template-columns: minmax(220px, .75fr) minmax(0, 2.25fr); gap: 24px; align-items: start; }
        .stats-content.has-recent-matches .stats-panel { min-height: max(220px, calc(var(--stats-panel-chrome-height) + (var(--recent-visible-count) * var(--recent-row-height)))); }
        .stats-panel { background: #f9fbfd; border: 1px solid #e8eef3; border-radius: 16px; padding: 20px; }
        .panel-title { font-size: 18px; font-weight: 600; margin-bottom: 18px; color: #131313; }
        .metric-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eceff3; }
        .metric-row:last-child { border-bottom: none; }
        .rating-pill { display: inline-block; background: #131313; color: #fff; padding: 6px 12px; border-radius: 999px; font-weight: 600; }
        .history-list { list-style: none; padding: 0; margin: 0; }
        .history-list.is-scrollable { max-height: calc(var(--recent-row-height) * 4); overflow-y: auto; padding-right: 8px; }
        .history-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eceff3; }
        .history-item:last-child { border-bottom: none; }
        .match-result-row { display: flex; justify-content: flex-end; align-items: center; gap: 6px; flex-wrap: wrap; }
        .result-rating-pill { display: inline-flex; align-items: center; gap: 7px; padding: 5px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; line-height: 1; text-transform: uppercase; letter-spacing: .4px; }
        .result-rating-pill.win { background: #dff8f2; color: #0f766e; }
        .result-rating-pill.loss { background: #ffe7eb; color: #b42318; }
        .result-rating-pill .rating-change { border-right: 1px solid currentColor; padding-right: 7px; letter-spacing: 0; }
        .muted { color: #6b7280; }
        @media (max-width: 991px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stats-content { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div id="preloder"><div class="loader"></div></div>

@include('partials.header')

<section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/2.png') }}">
    <div class="pi-content">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 text-white">
                    <h2>Statistics</h2>
                    <p>Track your badminton performance, rating progress, and recent match history.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-section stats-page-section">
    <div class="container">
        <div class="stats-shell">
            <div class="stats-overview">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 style="font-size: 24px; margin-bottom: 6px;">{{ $user->name }}</h3>
                        <p class="muted" style="margin: 0;">Performance snapshot for your recent badminton activity</p>
                    </div>
                    <div class="rating-pill">Player Rating: {{ number_format($user->rating_value ?? \App\Models\User::STARTING_RATING, 0) }}</div>
                </div>

                <div class="stats-grid mt-4">
                    <div class="stat-card">
                        <div class="label">Matches Played</div>
                        <div class="value">{{ $stats['matches_played'] }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Singles</div>
                        <div class="value">{{ $stats['singles_played'] }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Doubles</div>
                        <div class="value">{{ $stats['doubles_played'] }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Player Rating</div>
                        <div class="value">{{ number_format($user->rating_value ?? \App\Models\User::STARTING_RATING, 0) }}</div>
                    </div>
                </div>
            </div>

            @php
                $visibleRecentMatchCount = max(1, min($recentMatches->count(), 4));
            @endphp

            <div class="stats-content {{ $recentMatches->isNotEmpty() ? 'has-recent-matches' : '' }}" style="--recent-visible-count: {{ $visibleRecentMatchCount }};">
                <div class="stats-panel win-rates-panel">
                    <h4 class="panel-title">Win Rates</h4>
                    <div class="metric-row">
                        <span>Singles</span>
                        <strong>{{ $stats['singles_winrate'] }}%</strong>
                    </div>
                    <div class="metric-row">
                        <span>Doubles</span>
                        <strong>{{ $stats['doubles_winrate'] }}%</strong>
                    </div>
                    <div class="metric-row">
                        <span>Overall</span>
                        <strong>{{ $stats['matches_played'] > 0 ? round((($stats['singles_wins'] + $stats['doubles_wins']) / $stats['matches_played']) * 100, 1) : 0 }}%</strong>
                    </div>
                </div>

                <div class="stats-panel recent-matches-panel">
                    <h4 class="panel-title">Recent Matches</h4>
                    @if($recentMatches->isEmpty())
                        <p class="muted" style="margin:0;">No completed matches yet.</p>
                    @else
                        <ul class="history-list {{ $recentMatches->count() > 4 ? 'is-scrollable' : '' }}">
                            @foreach($recentMatches as $match)
                                <li class="history-item">
                                    <div>
                                        <div style="font-weight:600; color:#131313;">{{ $match['event'] }}</div>
                                        <div class="muted" style="font-size:13px;">{{ $match['player'] }} vs {{ $match['opponent'] }}</div>
                                        <div class="muted" style="font-size:13px;">{{ $match['format'] }} · {{ $match['played_at'] }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="match-result-row">
                                            <span class="result-rating-pill {{ strtolower($match['result']) }}">
                                                @if($match['rating_delta_label'] !== null)
                                                    <span class="rating-change">{{ $match['rating_delta_label'] }}</span>
                                                @endif
                                                <span>{{ $match['result'] }}</span>
                                            </span>
                                        </div>
                                        <div class="muted" style="font-size:12px; margin-top:4px;">{{ $match['score_detail'] }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-section">
    <div class="container">
        <ul class="footer-menu">
            <li><a href="{{ route('landing') }}">Home</a></li>
            <li><a href="{{ route('events.index') }}">Events</a></li>
            <li><a href="{{ route('calendar') }}">Calendar</a></li>
            <li><a href="{{ route('history') }}">Statistics</a></li>
            <li><a href="{{ route('events.index') }}">Tournament</a></li>
        </ul>
        <p class="copyright">Copyright &copy;{{ date('Y') }} Shuttl. All rights reserved</p>
    </div>
</footer>

<script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('landing/js/jquery.marquee.min.js') }}"></script>
<script src="{{ asset('landing/js/main.js') }}"></script>
</body>
</html>
