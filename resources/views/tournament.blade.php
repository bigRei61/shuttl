<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments - Shuttl</title>

    <link href="{{ asset('landing/img/favicon.ico') }}" rel="shortcut icon"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}"/>

    <style>
        .tournament-list-section {
            background: #f5f7fa;
        }

        .tp-section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 45px;
        }

        .tp-section-head h2 {
            margin: 0;
        }

        .tp-section-head p {
            margin: 8px 0 0;
            max-width: 620px;
        }

        .tournament-item.tp-card {
            display: block;
            height: 100%;
            min-width: 0;
            background: #fff;
            border: 1px solid #eaedf2;
            border-radius: 14px;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .tournament-item.tp-card:hover {
            transform: translateY(-6px);
            border-color: #dfe4ea;
            box-shadow: 0 20px 40px rgba(19, 19, 19, .08);
        }

        .tournament-item .ti-thumb {
            position: relative;
            width: 100%;
            height: 240px;
            float: none;
            margin: 0;
            background-size: cover;
            background-position: center;
        }

        .ti-featured {
            position: absolute;
            top: 16px;
            left: 16px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
            color: #0f9c8c;
            background: #d8f6ee;
            border-radius: 50px;
        }

        .ti-status {
            position: absolute;
            right: 16px;
            bottom: 16px;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #131313;
            background: #4EDFCE;
            border-radius: 50px;
        }

        .tournament-item .ti-content {
            min-width: 0;
            padding: 26px 26px 22px;
        }

        .tournament-item .ti-text {
            min-width: 0;
            padding-left: 0;
            padding-top: 0;
        }

        .tournament-item .ti-text h4 {
            color: #131313;
            font-size: 22px;
            margin-bottom: 18px;
            line-height: 1.35;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tournament-item .ti-content .ti-meta {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .tournament-item .ti-content .ti-meta li {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 9px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tournament-item .ti-content .ti-meta li strong {
            display: inline-block;
            min-width: 82px;
            font-weight: 700;
            color: #131313;
        }

        .tournament-item .ti-content .ti-caption {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #eef0f3;
            font-size: 13px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .tp-empty-state {
            background: #fff;
            border: 1px solid #eaedf2;
            border-radius: 14px;
            padding: 60px 24px;
            text-align: center;
            color: #878787;
        }
    </style>
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('partials.header')

    <section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/5.png') }}">
        <div class="pi-content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-7 text-white">
                        <h2>Tournaments</h2>
                        <p>Open your joined or hosted events, check games, and follow completed results.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tournament-list-section page-section spad">
        <div class="container">
            <div class="tp-section-head">
                <div>
                    <h2>{{ auth()->user()->isAdmin() ? 'All Active Events' : 'My Events' }}</h2>
                    <p>{{ auth()->user()->isAdmin() ? 'Every active event currently managed in Shuttl.' : 'Active events you have joined or hosted.' }}</p>
                </div>
                <a href="{{ route('events.index') }}" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">Browse Events</a>
            </div>

            @forelse($tournaments ?? collect() as $tournament)
                @if($loop->first)
                    <div class="row">
                @endif

                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('events.show', $tournament) }}" class="tournament-item tp-card">
                        <div class="ti-thumb" style="background-image: url('{{ $tournament->photoUrl() }}');">
                            @if($tournament->is_featured)
                                <span class="ti-featured"><i class="fa fa-star"></i> Featured</span>
                            @endif
                            <span class="ti-status">{{ $tournament->status }}</span>
                        </div>
                        <div class="ti-content">
                            <div class="ti-text">
                                <h4>{{ $tournament->name }}</h4>
                                <ul class="ti-meta">
                                    <li><strong>Starts:</strong> {{ $tournament->start_date->format('M d, Y') }}</li>
                                    <li><strong>Ends:</strong> {{ $tournament->end_date->format('M d, Y') }}</li>
                                    <li><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $tournament->type)) }}</li>
                                    <li><strong>Location:</strong> {{ $tournament->location }}</li>
                                    <li><strong>Host:</strong> {{ $tournament->organizer->name ?? 'Shuttl' }}</li>
                                    <li><strong>Players:</strong> {{ $tournament->approved_players_count }} approved</li>
                                    <li><strong>Games:</strong> {{ $tournament->games_count }}</li>
                                    @if($tournament->max_participants)
                                        <li><strong>Slots:</strong> {{ $tournament->max_participants }} participants</li>
                                    @endif
                                </ul>
                                @if($tournament->description)
                                    <p class="ti-caption">{{ Str::limit($tournament->description, 110) }}</p>
                                @else
                                    <p class="ti-caption">Open this event to see assigned players and final scores.</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="tp-empty-state">
                    <p>No joined or hosted events yet. Join an event and wait for host approval to see it here.</p>
                </div>
            @endforelse
        </div>
    </section>

    <script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>
</html>
