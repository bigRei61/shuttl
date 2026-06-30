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
            position: relative;
            z-index: 1;
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
            position: relative;
            z-index: 1000; /* raised so header's stacking context wins over hero */
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

        /* keep hero section's stacking context safely below the header */
        .hero-section {
            position: relative;
            z-index: 1;
        }
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
                    <div id="header-profile">
                        <button id="header-profile-btn" style="display:inline-flex;align-items:center;gap:8px;background:transparent;border:none;color:inherit;cursor:pointer;">
                            <span>{{ auth()->user()->name }}</span>
                        </button>

                        <div id="header-profile-menu" style="background:#fff;color:#111;border:1px solid #e6eef0;border-radius:8px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.08);">
                            <a href="{{ route('profile') }}" style="display:block;padding:10px 12px;text-decoration:none;color:inherit;border-bottom:1px solid #f1f5f9;">Profile</a>
                            <button id="header-logout-btn" style="width:100%;padding:10px 12px;border:none;background:transparent;text-align:left;cursor:pointer;">Logout</button>
                        </div>
                        <form id="header-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var btn = document.getElementById('header-profile-btn');
                            var menu = document.getElementById('header-profile-menu');
                            var logoutBtn = document.getElementById('header-logout-btn');

                            function closeMenu() { menu.style.display = 'none'; }

                            function openMenu() {
                                var rect = btn.getBoundingClientRect();
                                menu.style.display = 'block';
                                var menuWidth = menu.offsetWidth;
                                var left = rect.right - menuWidth;
                                if (left < 8) left = rect.left;
                                menu.style.top = (rect.bottom + 8) + 'px';
                                menu.style.left = left + 'px';
                            }

                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                if (menu.style.display === 'block') closeMenu(); else openMenu();
                            });

                            logoutBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                if (confirm('Sign out of Shuttl?')) {
                                    document.getElementById('header-logout-form').submit();
                                }
                            });

                            document.addEventListener('click', function (e) {
                                if (!document.getElementById('header-profile').contains(e.target) && !menu.contains(e.target)) {
                                    closeMenu();
                                }
                            });

                            window.addEventListener('scroll', function () {
                                if (menu.style.display === 'block') openMenu();
                            }, true);
                            window.addEventListener('resize', function () {
                                if (menu.style.display === 'block') openMenu();
                            });
                        });
                    </script>
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
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Statistics</a>
                        @else
                            <a href="{{ route('history') }}">Statistics</a>
                        @endguest
                    </li>
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Tournament</a>
                        @else
                            <a href="{{ route('events.index') }}">Tournament</a>
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
                    <div class="feature-item set-bg" data-setbg="{{ asset('landing/img/1.jpg') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Events</a>@else<a href="{{ route('events.index') }}">Events</a>@endguest</h5>
                            <p>Discover casual games, ranked matches, and local badminton events happening near you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('landing/img/2.jpg') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Calendar</a>@else<a href="{{ route('calendar') }}">Calendar</a>@endguest</h5>
                            <p>Stay on top of your schedule with upcoming matches, tournaments, and training sessions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('landing/img/3.jpg') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Statistics</a>@else<a href="{{ route('history') }}">Statistics</a>@endguest</h5>
                            <p>Monitor your rating, win rate, match history, and performance as you climb the rankings.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="{{ asset('landing/img/4.jpg') }}">
                        <div class="fi-content text-white">
                            <h5>@guest<a href="{{ route('login') }}">Tournaments</a>@else<a href="{{ route('events.index') }}">Tournaments</a>@endguest</h5>
                            <p>Join competitive tournaments, track your progress, and compete for the top spot.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tournaments-section spad">
        <div class="container">
            <div class="tournament-title">Sponsored Tournaments</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="tournament-item mb-4 mb-lg-0">
                        <div class="ti-notic">Premium Tournament</div>
                        <div class="ti-content">
                            <div class="ti-thumb set-bg" data-setbg="{{ asset('landing/img/tournament/1.jpg') }}"></div>
                            <div class="ti-text">
                                <h4>MTDY 2026 Championships</h4>
                                <ul>
                                    <li><span>Tournament Begins:</span> June 20, 2026</li>
                                    <li><span>Tournament Ends:</span> July 01, 2026</li>
                                    <li><span>Participants:</span> 10 teams</li>
                                    <li><span>Tournament Author:</span> Joshua Mangubat Sr.</li>
                                </ul>
                                <p><span>Prizes:</span> 1st place $2000, 2nd place: $1000, 3rd place: $500</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tournament-item">
                        <div class="ti-notic">Premium Tournament</div>
                        <div class="ti-content">
                            <div class="ti-thumb set-bg" data-setbg="{{ asset('landing/img/review-bg-2.jpg') }}"></div>
                            <div class="ti-text">
                                <h4>Hoops and Rackets Winter Cup</h4>
                                <ul>
                                    <li><span>Tournament Begins:</span> December 14, 2026</li>
                                    <li><span>Tournament Ends:</span> December 16, 2026</li>
                                    <li><span>Participants:</span> 10 teams</li>
                                    <li><span>Tournament Author:</span> Stephen Reilly Gudmalin</li>
                                </ul>
                                <p><span>Prizes:</span> 1st place $2000, 2nd place: $1000, 3rd place: $500</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-section">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="{{ route('landing') }}">Home</a></li>
                <li>@guest<a href="{{ route('login') }}">Events</a>@else<a href="{{ route('events.index') }}">Events</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Calendar</a>@else<a href="{{ route('calendar') }}">Calendar</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Statistics</a>@else<a href="{{ route('history') }}">Statistics</a>@endguest</li>
                <li>@guest<a href="{{ route('login') }}">Tournament</a>@else<a href="{{ route('events.index') }}">Tournament</a>@endguest</li>
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