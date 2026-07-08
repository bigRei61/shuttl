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

        .tp-results {
            position: relative;
        }

        .tp-pages-viewport {
            overflow: hidden;
            width: 100%;
        }

        .tp-pages-track {
            display: flex;
            transition: transform .34s ease;
            will-change: transform;
        }

        .tp-page-slide {
            flex: 0 0 100%;
            min-width: 100%;
        }

        .tournament-page-fragment {
            animation: tournamentFragmentEnter .26s ease both;
            transition: opacity .22s ease, transform .22s ease;
            will-change: opacity, transform;
        }

        .tournament-page-fragment.is-transitioning-out {
            animation: none;
            opacity: 0;
            pointer-events: none;
            transform: translateX(var(--transition-exit-x, -18px));
        }

        .tournament-page-fragment.is-transitioning-in {
            animation: tournamentFragmentEnter .26s ease both;
        }

        .pagination-side {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e9edf2;
            color: #fff;
            font-size: 44px;
            line-height: 1;
            border: 0;
            text-decoration: none;
            transition: background .2s, transform .2s;
            cursor: pointer;
            z-index: 2;
        }

        .pagination-side:hover {
            background: #4EDFCE;
            color: #fff;
            text-decoration: none;
            transform: translateY(-50%) scale(1.03);
        }

        .pagination-side.is-disabled {
            opacity: .38;
            pointer-events: none;
        }

        .tp-results .pagination-prev {
            left: -86px;
        }

        .tp-results .pagination-next {
            right: -86px;
        }

        .tp-pagination {
            display: flex;
            justify-content: center;
            margin-top: 18px;
        }

        .pagination-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .pagination-dot {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff;
            color: #131313;
            font-size: 15px;
            font-weight: 700;
            border: 0;
            text-decoration: none;
            transition: background .2s, color .2s;
            cursor: pointer;
        }

        .pagination-dot:hover {
            background: #DEF3EE;
            color: #131313;
            text-decoration: none;
        }

        .pagination-dot.is-active {
            background: #4EDFCE;
            color: #131313;
        }

        .pagination-dot.pagination-ellipsis {
            background: transparent;
            pointer-events: none;
        }

        body.tournaments-index-page .page-info-section,
        body.tournaments-index-page .tournament-list-section {
            transition: opacity .2s ease, transform .2s ease;
        }

        body.tournaments-index-page.is-leaving-event .page-info-section,
        body.tournaments-index-page.is-leaving-event .tournament-list-section {
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes tournamentFragmentEnter {
            from {
                opacity: 0;
                transform: translateX(var(--transition-enter-x, 18px));
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tp-empty-state {
            background: #fff;
            border: 1px solid #eaedf2;
            border-radius: 14px;
            padding: 60px 24px;
            text-align: center;
            color: #878787;
        }

        @media (max-width: 640px) {
            .tp-results {
                display: flex;
                flex-direction: column;
            }

            .pagination-side {
                position: static;
                transform: none;
                width: 46px;
                height: 46px;
                font-size: 30px;
            }

            .pagination-side:hover {
                transform: none;
            }

            .tp-pagination {
                align-items: center;
                gap: 14px;
            }
        }

        @media (min-width: 641px) and (max-width: 991px) {
            .tp-results .pagination-prev {
                left: -24px;
            }

            .tp-results .pagination-next {
                right: -24px;
            }
        }
    </style>
</head>
<body class="tournaments-index-page">
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

            @php
                $tournamentPages = ($tournaments ?? collect())->chunk(3)->values();
                $tournamentPageCount = $tournamentPages->count();
            @endphp

            <div id="tournament-page-fragment" class="tournament-page-fragment" data-transition-fragment data-client-pager data-current-page="1" data-page-count="{{ $tournamentPageCount }}" aria-live="polite">
                @if(($tournaments ?? collect())->isEmpty())
                    <div class="tp-empty-state">
                        <p>No joined or hosted events yet. Join an event and wait for host approval to see it here.</p>
                    </div>
                @else
                    <div class="tp-results">
                        <div class="tp-pages-viewport">
                            <div class="tp-pages-track">
                                @foreach($tournamentPages as $tournamentPage)
                                    <div class="tp-page-slide" data-client-page="{{ $loop->iteration }}">
                                        <div class="row">
                                            @foreach($tournamentPage as $tournament)
                                                <div class="col-lg-4 col-md-6 mb-4">
                                                    <a href="{{ route('events.show', $tournament) }}" class="tournament-item tp-card" data-event-transition-link>
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
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($tournamentPageCount > 1)
                            <button type="button" class="pagination-side pagination-prev is-disabled" data-client-pagination="prev" aria-label="Previous page" disabled><i class="fa fa-angle-left"></i></button>
                            <button type="button" class="pagination-side pagination-next" data-client-pagination="next" aria-label="Next page"><i class="fa fa-angle-right"></i></button>
                        @endif
                    </div>

                    @if($tournamentPageCount > 1)
                    <div class="tp-pagination">
                        <nav class="pagination-dots" aria-label="Tournament pages" data-client-dots></nav>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <script>
    (function () {
        const fragment = document.getElementById('tournament-page-fragment');
        const track = fragment?.querySelector('.tp-pages-track');
        const dots = fragment?.querySelector('[data-client-dots]');
        const previousButton = fragment?.querySelector('[data-client-pagination="prev"]');
        const nextButton = fragment?.querySelector('[data-client-pagination="next"]');
        const totalPages = parseInt(fragment?.dataset.pageCount || '1', 10);
        let currentPage = parseInt(fragment?.dataset.currentPage || '1', 10);

        if (!fragment) return;

        function visiblePages() {
            if (totalPages <= 4) {
                return Array.from({ length: totalPages }, (_, index) => index + 1);
            }

            if (currentPage <= 3) {
                return [1, 2, 3, totalPages];
            }

            if (currentPage >= totalPages - 2) {
                return [1, totalPages - 2, totalPages - 1, totalPages];
            }

            return [1, currentPage, currentPage + 1, totalPages];
        }

        function renderDots() {
            if (!dots || totalPages <= 1) return;

            dots.innerHTML = '';

            visiblePages().forEach(function (page, index, pages) {
                if (index > 0 && page > pages[index - 1] + 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'pagination-dot pagination-ellipsis';
                    ellipsis.setAttribute('aria-hidden', 'true');
                    ellipsis.textContent = '...';
                    dots.appendChild(ellipsis);
                }

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'pagination-dot';
                button.dataset.clientPage = String(page);
                button.textContent = String(page);

                if (page === currentPage) {
                    button.classList.add('is-active');
                    button.setAttribute('aria-current', 'page');
                    button.disabled = true;
                }

                dots.appendChild(button);
            });
        }

        function updateControls() {
            if (previousButton) {
                previousButton.disabled = currentPage === 1;
                previousButton.classList.toggle('is-disabled', currentPage === 1);
            }

            if (nextButton) {
                nextButton.disabled = currentPage === totalPages;
                nextButton.classList.toggle('is-disabled', currentPage === totalPages);
            }
        }

        function goToPage(page) {
            if (!track || totalPages <= 1) return;

            currentPage = Math.min(Math.max(page, 1), totalPages);
            fragment.dataset.currentPage = String(currentPage);
            track.style.transform = `translateX(-${(currentPage - 1) * 100}%)`;

            updateControls();
            renderDots();
        }

        if (track && totalPages > 1) {
            previousButton?.addEventListener('click', function () {
                goToPage(currentPage - 1);
            });

            nextButton?.addEventListener('click', function () {
                goToPage(currentPage + 1);
            });

            dots?.addEventListener('click', function (event) {
                const button = event.target.closest('[data-client-page]');

                if (!button) return;

                goToPage(parseInt(button.dataset.clientPage, 10));
            });

            goToPage(currentPage);
        }

        window.addEventListener('pageshow', function () {
            document.body.classList.remove('is-leaving-event');
        });

        document.addEventListener('click', function (event) {
            const link = event.target.closest('[data-event-transition-link]');

            if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

            const url = new URL(link.href, window.location.href);

            if (url.origin !== window.location.origin) return;

            event.preventDefault();
            document.body.classList.add('is-leaving-event');

            window.setTimeout(() => {
                window.location.href = url.href;
            }, 170);
        });
    })();
    </script>

    <script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>
</html>
