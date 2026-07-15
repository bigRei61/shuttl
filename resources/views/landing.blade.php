<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shuttl - Elevate Your Game</title>
    <meta charset="UTF-8">
    <meta name="description" content="Shuttl Badminton Tournament & Rating System">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('landing/img/favicon.ico') }}" rel="shortcut icon"/>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}"/>
    <style>
        .header-section {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 2147483000 !important;
        }

        body {
            padding-top: 74px;
        }

        .header-logo {
            position: absolute;
            top: 0px;
            left: 0;
            width: 120px;
            padding-top: 0;
            padding-bottom: 0;
            z-index: 2;
        }

        .header-logo img {
            width: 100%;
            height: auto;
            display: block;
        }

        @media only screen and (max-width: 767px) {
            .header-logo {
                position: static;
                width: 100px;
                margin-bottom: 10px;
            }
        }

        #header-profile { position: relative; display: inline-block; }
        #header-profile-menu {
            position: fixed;
            z-index: 2147483647;
            display: none;
        }
        .header-section {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 2147483000 !important;
        }
/* NEW SECTION */
        .header-logo {
            position: absolute;
            top: 0px;
            left: 0;
            width: 120px;
            padding-top: 0;
            padding-bottom: 0;
            z-index: 2;
        }

        .header-logo img {
            width: 100%;
            height: auto;
            display: block;
        }

        @media only screen and (max-width: 767px) {
            body {
                padding-top: 126px;
            }

            .header-logo {
                position: static;
                width: 100px;
                margin-bottom: 10px;
            }
        }

        #header-profile { position: relative; display: inline-block; }
        #header-profile-menu {
            position: fixed;
            z-index: 2147483647;
            display: none;
        }

        .header-section {
            overflow: visible;
        }

        .header-section .container {
            position: relative;
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-section .header-logo {
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
        }

        .header-section .main-menu {
            float: none;
            margin: 0;
        }

        .header-section .main-menu ul {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 42px;
            margin: 0;
            padding: 0;
        }

        .header-section .main-menu ul li {
            display: block;
        }

        .header-section .main-menu ul li a {
            margin-left: 0;
        }

        .header-section .user-panel {
            position: absolute;
            top: 50%;
            right: 15px;
            float: none;
            transform: translateY(-50%);
            z-index: 5;
        }

        .header-section .user-panel.is-profile {
            width: 82px;
            height: 46px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
        }

        .header-section .user-panel.is-guest {
            padding: 8px 28px;
        }

        .profile-icon-button {
            width: 100%;
            height: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 999px;
            color: #131313;
            cursor: pointer;
            list-style: none;
        }

        .profile-icon-button::-webkit-details-marker {
            display: none;
        }

        .profile-icon-button .fa {
            font-size: 24px;
            line-height: 1;
        }

        #header-profile {
            position: relative;
            display: inline-flex;
            width: 100%;
            height: 100%;
        }

        #header-profile[open] .profile-icon-button {
            background: rgba(255, 255, 255, .38);
        }

        #header-profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 260px;
            padding: 14px;
            border: 1px solid #dce8e6;
            border-radius: 16px;
            background: #fff;
            color: #131313;
            box-shadow: 0 18px 42px rgba(19, 19, 19, .14);
            z-index: 2147483647;
        }

        #header-profile[open] #header-profile-menu {
            display: block;
        }

        .header-profile-summary {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 2px 2px 14px;
            border-bottom: 1px solid #eef3f4;
        }

        .header-profile-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            background: #4EDFCE;
            color: #131313;
            font-weight: 800;
        }

        .header-profile-name {
            margin: 0;
            color: #131313;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.25;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-profile-email {
            margin: 2px 0 0;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.25;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-profile-logout {
            width: 100%;
            margin-top: 12px;
            padding: 10px 14px;
            border: none;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #f1f6f7;
            color: #131313;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .header-profile-logout:hover {
            background: #4EDFCE;
            transform: translateY(-1px);
        }

        .header-profile-logout .fa {
            font-size: 14px;
        }

        .header-profile-details {
            min-width: 0;
        }

        @media only screen and (max-width: 420px) {
            #header-profile-menu {
                width: min(260px, calc(100vw - 24px));
            }
        }

        @media only screen and (max-width: 767px) {
            .header-section .container {
                min-height: 80px;
                justify-content: space-between;
            }

            .header-section .header-logo {
                position: static;
                width: 100px;
                margin-bottom: 0;
                transform: none;
            }

            .header-section .user-panel {
                position: static;
                display: inline-flex;
                transform: none;
                margin-left: auto;
            }

            .header-section .main-menu {
                position: absolute;
                top: calc(100% + 24px);
                left: 0;
                width: 100%;
            }

            .header-section .main-menu ul {
                display: block;
            }

            .header-section .main-menu ul li {
                display: block;
            }
        }

        /* keep hero section's stacking context safely below the header */
        .hero-section {
            position: relative;
            z-index: 1;
        }

        .featured-shell {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e4ebf0;
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(19, 19, 19, 0.08);
            padding: 24px;
        }

        .featured-slide {
            display: none;
            width: 100%;
            animation: featuredFadeSlide 0.45s ease;
        }

        .featured-slide-card {
            padding: 4px 2px;
        }

        .featured-carousel-wrap {
            margin-top: 12px;
            position: relative;
            width: 100%;
            overflow: visible;
        }

        @keyframes featuredFadeSlide {
            from {
                opacity: 0;
                transform: translateX(16px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .featured-media {
            position: relative;
            height: 280px;
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(78, 223, 206, 0.22), rgba(255, 255, 255, 0.9));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
        }

        .featured-media::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.25) 100%);
            pointer-events: none;
        }

        .featured-content {
            background: #ffffff;
            border: 1px solid #e9f0f4;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 24px rgba(19, 19, 19, 0.04);
            height: 100%;
        }

        .featured-badge {
            display: inline-block;
            background: #4EDFCE;
            color: #131313;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .featured-title {
            font-size: 22px;
            margin-bottom: 12px;
            color: #131313;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .featured-list {
            list-style: none;
            padding: 0;
            margin: 0 0 14px;
        }

        .featured-list li {
            margin-bottom: 7px;
            font-size: 14px;
            color: #5a6472;
        }

        .featured-list strong {
            color: #131313;
            font-weight: 600;
            margin-right: 6px;
        }

        .featured-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 6px;
        }

        .featured-actions form {
            margin: 0;
        }

        .featured-join,
        .featured-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .featured-join {
            background: transparent;
            border: 2px solid #4EDFCE;
            color: #131313;
            padding: 7px 20px;
            cursor: pointer;
            transition: all .2s;
        }

        .featured-join:hover {
            background: #4EDFCE;
        }

        .featured-pill {
            background: #eef2f6;
            color: #131313;
            padding: 7px 16px;
        }

        .featured-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e9edf2;
            color: #fff;
            font-size: 34px;
            line-height: 1;
            border: 0;
            text-decoration: none;
            transition: background .2s, transform .2s;
            cursor: pointer;
            z-index: 2;
        }

        .featured-nav:hover {
            background: #4EDFCE;
            color: #fff;
            text-decoration: none;
            transform: translateY(-50%) scale(1.03);
        }

        .featured-nav.prev { left: -39px; }
        .featured-nav.next { right: -39px; }

        .featured-dots {
            text-align: center;
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .featured-dot {
            display: inline-block;
            height: 10px;
            border-radius: 999px;
            background: #d6dee7;
            transition: all .3s ease;
            cursor: pointer;
        }

        .featured-dot.active {
            width: 28px;
            background: #4EDFCE;
        }

        @media only screen and (max-width: 767px) {
            .featured-shell {
                padding: 16px;
            }

            .featured-media {
                height: 220px;
                margin-bottom: 16px;
            }

            .featured-content {
                padding: 18px;
            }

            .featured-nav {
                width: 42px;
                height: 42px;
                font-size: 26px;
            }

            .featured-nav.prev { left: -29px; }
            .featured-nav.next { right: -29px; }
        }
    </style>
    @include('partials.caret-guard')
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <header class="header-section">
        <div class="container">
            <a class="header-logo" href="{{ route('landing') }}">
                <img src="{{ asset('images/fullLogo.png') }}" alt="Shuttl">
            </a>
            <div class="user-panel {{ auth()->check() ? 'is-profile' : 'is-guest' }}">
                @auth
                    <details id="header-profile">
                        <summary id="header-profile-btn" class="profile-icon-button" aria-label="Open profile menu">
                            <i class="fa fa-user-o" aria-hidden="true"></i>
                        </summary>

                        <div id="header-profile-menu" role="menu" aria-label="Profile menu">
                            <div class="header-profile-summary">
                                <span class="header-profile-avatar">{{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}</span>
                                <div class="header-profile-details">
                                    <p class="header-profile-name">{{ auth()->user()->name }}</p>
                                    <p class="header-profile-email">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <form id="header-logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button id="header-logout-btn" class="header-profile-logout" type="submit">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </details>
                @else
                    <a href="{{ route('login') }}">Login</a> / <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
            <div class="nav-switch">
                <i class="fa fa-bars"></i>
            </div>
            <nav class="main-menu">
                <ul>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Events</a>
                        @else
                            <a href="{{ route('events.index') }}">Events</a>
                        @endguest
                    </li>
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Calendar</a>
                        @else
                            <a href="{{ route('calendar') }}">Calendar</a>
                        @endguest
                    </li>
                    <li><a href="{{ route('history') }}">Statistics</a></li>
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Tournament</a>
                        @else
                            <a href="{{ route('tournaments') }}">Tournament</a>
                        @endguest
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="{{ asset('landing/img/slider-1.png') }}">
                <div class="hs-text">
                    <div class="container">
                        <h2>Elevate your game with <span>Shuttl</span></h2>
                        <p>Compete, earn ratings, climb the leaderboard, and watch your game reach new heights.</p>
                        @guest
                            <a href="{{ route('register') }}" class="site-btn">Participate</a>
                        @else
                            <a href="{{ route('events.index') }}" class="site-btn">Participate</a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="hs-item set-bg" data-setbg="{{ asset('landing/img/slider-2.png') }}">
                <div class="hs-text">
                    <div class="container">
                        <h2>Track Every <span>Match</span> You Play</h2>
                        <p>From casual quick-play sessions to full competitive tournaments,<br>
                        Shuttl keeps your rating accurate and your history complete.</p>
                        <a href="{{ route('login') }}" class="site-btn">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="feature-section spad">
        <div class="container">
            <div class="section-title">
                <h2>Explore</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('page-top-bg/3.png') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Events</a>@else<a href="{{ route('events.index') }}">Events</a>@endguest</h5>
                            <p>Discover casual games, ranked matches, and local badminton events happening near you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('page-top-bg/4.png') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Calendar</a>@else<a href="{{ route('calendar') }}">Calendar</a>@endguest</h5>
                            <p>Stay on top of your schedule with upcoming matches, tournaments, and training sessions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('page-top-bg/5.png') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Statistics</a>@else<a href="{{ route('history') }}">Statistics</a>@endguest</h5>
                            <p>Monitor your rating, win rate, match history, and performance as you climb the rankings.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('page-top-bg/1.png') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Tournaments</a>@else<a href="{{ route('tournaments') }}">Tournaments</a>@endguest</h5>
                            <p>Join competitive tournaments, track your progress, and compete for the top spot.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tournaments-section spad">
        <div class="container">
            <div class="tournament-title">Featured Tournaments</div>

            @if($featured->isEmpty())
                <p style="color:#878787; text-align:center; padding:40px 0;">No featured tournaments available right now.</p>
            @else
                <div class="featured-carousel-wrap">
                    <div id="featured-track" style="width:100%;">
                        @foreach($featured as $event)
                            @php
                                $participation = $event->players->firstWhere('id', auth()->id())?->pivot?->status;
                                $isHost = (int) $event->organizer_id === (int) auth()->id();
                            @endphp
                            <div class="featured-slide" style="display:none; width:100%;">
                                <div class="featured-shell">
                                    <div class="row g-4 align-items-stretch">
                                        <div class="col-lg-5">
                                            <div class="featured-media" style="background: url('{{ $event->photoUrl() }}') center/cover no-repeat;"></div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="featured-content">
                                                <div class="featured-badge">
                                                    {{ $event->status === 'ongoing' ? '🔴 Live Now' : '⭐ Featured Tournament' }}
                                                </div>
                                                <h4 class="featured-title" title="{{ $event->name }}">{{ Str::limit($event->name, 70, '...') }}</h4>
                                                <ul class="featured-list">
                                                    <li><strong>Begins:</strong>{{ $event->start_date->format('F d, Y') }}</li>
                                                    <li><strong>Ends:</strong>{{ $event->end_date->format('F d, Y') }}</li>
                                                    <li><strong>Location:</strong>{{ $event->location }}</li>
                                                    @if($event->max_participants)
                                                        <li><strong>Participants:</strong>{{ $event->max_participants }} players</li>
                                                    @endif
                                                    <li><strong>Host:</strong>{{ $event->organizer->name ?? 'Shuttl' }}</li>
                                                </ul>
                                                <div class="featured-actions">
                                                    @auth
                                                        <a href="{{ route('events.show', $event) }}" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">View Details</a>
                                                        @if($isHost)
                                                            <span class="featured-pill">Host</span>
                                                        @elseif($participation === 'approved')
                                                            <span class="featured-pill">Joined</span>
                                                        @elseif($participation === 'pending')
                                                            <span class="featured-pill">Pending Approval</span>
                                                        @elseif($event->status === 'open')
                                                            <form method="POST" action="{{ route('events.join', $event) }}">
                                                                @csrf
                                                                <button type="submit" class="featured-join">Request to Join</button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('login') }}" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">Login to Join</a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($featured->count() > 1)
                        <a id="feat-prev" href="#" class="featured-nav prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a id="feat-next" href="#" class="featured-nav next">
                            <i class="fa fa-angle-right"></i>
                        </a>

                        <div id="feat-dots" class="featured-dots"></div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <script>
    (function () {
        const track = document.getElementById('featured-track');
        const dotsContainer = document.getElementById('feat-dots');
        const btnPrev = document.getElementById('feat-prev');
        const btnNext = document.getElementById('feat-next');

        if (!track) return;

        const slides = track.querySelectorAll('.featured-slide');
        const total = slides.length;
        let current = 0;

        function renderSlides() {
            slides.forEach((slide, index) => {
                slide.style.display = index === current ? 'block' : 'none';
            });
        }

        renderSlides();

        if (total <= 1) return;

        let autoplay = setInterval(() => goTo(current + 1), 5000);

        function goTo(index) {
            current = (index + total) % total;
            renderSlides();
            renderDots();
        }

        function renderDots() {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < total; i++) {
                const dot = document.createElement('span');
                dot.className = 'featured-dot' + (i === current ? ' active' : '');
                dot.style.width = i === current ? '28px' : '10px';
                dot.addEventListener('click', () => { clearInterval(autoplay); goTo(i); });
                dotsContainer.appendChild(dot);
            }
        }

        btnPrev.addEventListener('click', (e) => { e.preventDefault(); clearInterval(autoplay); goTo(current - 1); });
        btnNext.addEventListener('click', (e) => { e.preventDefault(); clearInterval(autoplay); goTo(current + 1); });

        goTo(0);
    })();
    </script>

    <footer class="footer-section">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="{{ route('landing') }}">Home</a></li>
                <li>@guest<a href="{{ route('login') }}">Events</a>@else<a href="{{ route('events.index') }}">Events</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Calendar</a>@else<a href="{{ route('calendar') }}">Calendar</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Statistics</a>@else<a href="{{ route('history') }}">Statistics</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Tournament</a>@else<a href="{{ route('tournaments') }}">Tournament</a>@endguest</li>
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
