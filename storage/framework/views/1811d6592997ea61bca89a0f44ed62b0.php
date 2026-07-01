<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shuttl - Elevate Your Game</title>
    <meta charset="UTF-8">
    <meta name="description" content="Shuttl Badminton Tournament & Rating System">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo e(asset('landing/img/favicon.ico')); ?>" rel="shortcut icon"/>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('landing/css/bootstrap.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/font-awesome.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/owl.carousel.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/style.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/animate.css')); ?>"/>
    <style>
        .header-section {
            position: sticky;
            top: 0;
            z-index: 1000;
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
            position: sticky;
            top: 0;
            z-index: 1000;
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
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <header class="header-section">
        <div class="container">
            <a class="header-logo" href="<?php echo e(route('landing')); ?>">
                <img src="<?php echo e(asset('images/fullLogo.png')); ?>" alt="Shuttl">
            </a>
            <div class="user-panel">
                <?php if(auth()->guard()->check()): ?>
                    <div id="header-profile">
                        <button id="header-profile-btn" style="display:inline-flex;align-items:center;gap:8px;background:transparent;border:none;color:inherit;cursor:pointer;">
                            <span><?php echo e(auth()->user()->name); ?></span>
                        </button>

                        <div id="header-profile-menu" style="background:#fff;color:#111;border:1px solid #e6eef0;border-radius:8px;min-width:180px;box-shadow:0 8px 24px rgba(0,0,0,.08);">
                            <a href="<?php echo e(route('profile')); ?>" style="display:block;padding:10px 12px;text-decoration:none;color:inherit;border-bottom:1px solid #f1f5f9;">Profile</a>
                            <button id="header-logout-btn" style="width:100%;padding:10px 12px;border:none;background:transparent;text-align:left;cursor:pointer;">Logout</button>
                        </div>
                        <form id="header-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>
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
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>">Login</a> / <a href="<?php echo e(route('register')); ?>">Register</a>
                <?php endif; ?>
            </div>
            <div class="nav-switch">
                <i class="fa fa-bars"></i>
            </div>
            <nav class="main-menu">
                <ul>
                    <li><a href="<?php echo e(route('landing')); ?>">Home</a></li>
                    <li>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('login')); ?>">Events</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('events.index')); ?>">Events</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('login')); ?>">Calendar</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('calendar')); ?>">Calendar</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('login')); ?>">Statistics</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('history')); ?>">Statistics</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('login')); ?>">Tournament</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('events.index')); ?>">Tournament</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="<?php echo e(asset('landing/img/slider-1.png')); ?>">
                <div class="hs-text">
                    <div class="container">
                        <h2>Elevate your game with <span>Shuttl</span></h2>
                        <p>Compete, earn ratings, climb the leaderboard, and watch your game reach new heights.</p>
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('register')); ?>" class="site-btn">Participate</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('events.index')); ?>" class="site-btn">Participate</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="hs-item set-bg" data-setbg="<?php echo e(asset('landing/img/slider-2.png')); ?>">
                <div class="hs-text">
                    <div class="container">
                        <h2>Track Every <span>Match</span> You Play</h2>
                        <p>From casual quick-play sessions to full competitive tournaments,<br>
                        Shuttl keeps your rating accurate and your history complete.</p>
                        <a href="<?php echo e(route('login')); ?>" class="site-btn">Read More</a>
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
                    <div class="feature-item set-bg" data-setbg="<?php echo e(asset('page-top-bg/3.png')); ?>">
                        <div class="fi-content text-white">
                            <h5><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Events</a><?php else: ?><a href="<?php echo e(route('events.index')); ?>">Events</a><?php endif; ?></h5>
                            <p>Discover casual games, ranked matches, and local badminton events happening near you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="<?php echo e(asset('page-top-bg/4.png')); ?>">
                        <div class="fi-content text-white">
                            <h5><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Calendar</a><?php else: ?><a href="<?php echo e(route('calendar')); ?>">Calendar</a><?php endif; ?></h5>
                            <p>Stay on top of your schedule with upcoming matches, tournaments, and training sessions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="<?php echo e(asset('page-top-bg/5.png')); ?>">
                        <div class="fi-content text-white">
                            <h5><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Statistics</a><?php else: ?><a href="<?php echo e(route('history')); ?>">Statistics</a><?php endif; ?></h5>
                            <p>Monitor your rating, win rate, match history, and performance as you climb the rankings.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 p-0">
                    <div class="feature-item set-bg" data-setbg="<?php echo e(asset('page-top-bg/1.png')); ?>">
                        <div class="fi-content text-white">
                            <h5><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Tournaments</a><?php else: ?><a href="<?php echo e(route('events.index')); ?>">Tournaments</a><?php endif; ?></h5>
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

            <?php
                $featured = \App\Models\Event::where('is_featured', true)
                    ->whereIn('status', ['open', 'ongoing'])
                    ->orderByDesc('start_date')
                    ->get();
            ?>

            <?php if($featured->isEmpty()): ?>
                <p style="color:#878787; text-align:center; padding:40px 0;">No featured tournaments available right now.</p>
            <?php else: ?>
                <div style="position:relative; width:100%; overflow:hidden;">
                    <div id="featured-track" style="display:flex; width:100%; transition: transform 0.45s cubic-bezier(.4,0,.2,1); will-change:transform; overflow:hidden;">
                        <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="featured-slide" style="flex:0 0 100%; width:100%; max-width:100%;">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div style="height:260px; background: url('<?php echo e(asset('landing/img/slider-1.png')); ?>') center/cover no-repeat; border-radius:6px;"></div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="ti-text" style="padding: 10px 0 0 20px;">
                                            <div style="display:inline-block; background:#4EDFCE; color:#131313; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; padding:4px 14px; border-radius:999px; margin-bottom:14px;">
                                                <?php echo e($event->status === 'ongoing' ? '🔴 Live Now' : '⭐ Featured Tournament'); ?>

                                            </div>
                                            <h4 style="font-size:22px; margin-bottom:14px;"><?php echo e($event->name); ?></h4>
                                            <ul style="list-style:none; padding:0; margin:0 0 14px;">
                                                <li style="margin-bottom:6px; font-size:14px; color:#555;">
                                                    <span style="color:#131313; font-weight:600; margin-right:6px;">Begins:</span>
                                                    <?php echo e($event->start_date->format('F d, Y')); ?>

                                                </li>
                                                <li style="margin-bottom:6px; font-size:14px; color:#555;">
                                                    <span style="color:#131313; font-weight:600; margin-right:6px;">Ends:</span>
                                                    <?php echo e($event->end_date->format('F d, Y')); ?>

                                                </li>
                                                <li style="margin-bottom:6px; font-size:14px; color:#555;">
                                                    <span style="color:#131313; font-weight:600; margin-right:6px;">Location:</span>
                                                    <?php echo e($event->location); ?>

                                                </li>
                                                <?php if($event->max_participants): ?>
                                                    <li style="margin-bottom:6px; font-size:14px; color:#555;">
                                                        <span style="color:#131313; font-weight:600; margin-right:6px;">Participants:</span>
                                                        <?php echo e($event->max_participants); ?> players
                                                    </li>
                                                <?php endif; ?>
                                                <li style="margin-bottom:6px; font-size:14px; color:#555;">
                                                    <span style="color:#131313; font-weight:600; margin-right:6px;">Organizer:</span>
                                                    <?php echo e($event->organizer->name ?? 'Shuttl'); ?>

                                                </li>
                                            </ul>
                                            <?php if($event->description): ?>
                                                <p style="font-size:13px; color:#878787; margin-bottom:18px;"><?php echo e(Str::limit($event->description, 120)); ?></p>
                                            <?php endif; ?>
                                            <div style="display:flex; gap:10px; align-items:center;">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">View Details</a>
                                                    <?php if($event->status === 'open'): ?>
                                                        <form method="POST" action="<?php echo e(route('events.join', $event)); ?>" style="margin:0;">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" style="background:transparent; border:2px solid #4EDFCE; color:#131313; font-size:13px; font-weight:600; padding:7px 22px; border-radius:999px; cursor:pointer; transition:all .2s;"
                                                                    onmouseover="this.style.background='#4EDFCE'" onmouseout="this.style.background='transparent'">
                                                                Join Now
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('login')); ?>" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">Login to Join</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php if($featured->count() > 1): ?>
                        <a id="feat-prev" href="#" style="position:absolute; top:50%; left:-20px; transform:translateY(-50%); display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:50%; background:#e5e5e5; color:#131313; font-size:13px; text-decoration:none; transition:all .2s; z-index:10;"
                           onmouseover="this.style.background='#4EDFCE'" onmouseout="this.style.background='#e5e5e5'">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a id="feat-next" href="#" style="position:absolute; top:50%; right:-20px; transform:translateY(-50%); display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:50%; background:#e5e5e5; color:#131313; font-size:13px; text-decoration:none; transition:all .2s; z-index:10;"
                           onmouseover="this.style.background='#4EDFCE'" onmouseout="this.style.background='#e5e5e5'">
                            <i class="fa fa-angle-right"></i>
                        </a>

                        <div id="feat-dots" style="text-align:center; margin-top:24px; display:flex; justify-content:center; gap:8px;"></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
        if (total <= 1) return;

        let current = 0;
        let autoplay = setInterval(() => goTo(current + 1), 5000);

        function goTo(index) {
            current = (index + total) % total;
            const slideWidth = track.parentElement.getBoundingClientRect().width;
            track.style.transform = `translateX(-${current * slideWidth}px)`;
            renderDots();
        }

        function renderDots() {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < total; i++) {
                const dot = document.createElement('span');
                dot.style.cssText = `
                    display:inline-block; width:${i === current ? '28px' : '10px'}; height:10px;
                    border-radius:999px; background:${i === current ? '#4EDFCE' : '#d6dee7'};
                    transition:all .3s; cursor:pointer;
                `;
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
                <li><a href="<?php echo e(route('landing')); ?>">Home</a></li>
                <li><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Events</a><?php else: ?><a href="<?php echo e(route('events.index')); ?>">Events</a><?php endif; ?></li>
                <li><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Calendar</a><?php else: ?><a href="<?php echo e(route('calendar')); ?>">Calendar</a><?php endif; ?></li>
                <li><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Statistics</a><?php else: ?><a href="<?php echo e(route('history')); ?>">Statistics</a><?php endif; ?></li>
                <li><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('login')); ?>">Tournament</a><?php else: ?><a href="<?php echo e(route('events.index')); ?>">Tournament</a><?php endif; ?></li>
            </ul>
            <p class="copyright">Copyright &copy;<?php echo e(date('Y')); ?> Shuttl. All rights reserved</p>
        </div>
    </footer>

    <script src="<?php echo e(asset('landing/js/jquery-3.2.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/jquery.marquee.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/main.js')); ?>"></script>
</body>
</html><?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/landing.blade.php ENDPATH**/ ?>