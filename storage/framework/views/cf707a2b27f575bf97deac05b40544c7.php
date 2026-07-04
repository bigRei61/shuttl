<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Shuttl</title>
    <link href="img/favicon.ico" rel="shortcut icon"/>
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
        .event-slide {
            width: calc((100% - 40px) / 3);
            flex-shrink: 0;
            display: flex;
            height: 520px;
        }
        .review-item {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
        }
        .review-cover {
            height: 200px;
            min-height: 200px;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .event-text {
            background: #fff;
            border: 1px solid #d6dee7;
            border-top: none;
            padding: 24px;
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .event-text h4 a { color: #131313; text-decoration: none; }
        .event-text h4 a:hover { color: #4EDFCE; }
        .event-text .ti-text ul { list-style: none; padding: 0; margin: 0 0 14px; }
        .event-text .ti-text ul li { font-size: 13px; color: #878787; margin-bottom: 5px; }
        .event-text .ti-text ul li span { color: #131313; font-weight: 600; margin-right: 6px; }
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
            display: inline-block; background: transparent;
            border: 2px solid #4EDFCE; color: #131313;
            font-size: 13px; font-weight: 600; padding: 7px 20px;
            border-radius: 999px; cursor: pointer; transition: all .2s;
            text-decoration: none; margin-left: 8px;
        }
        .btn-join:hover { background: #4EDFCE; }
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
    </style>
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Page info -->
    <section class="page-info-section set-bg" data-setbg="<?php echo e(asset('page-top-bg/1.png')); ?>">
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

            <?php if(session('success')): ?>
                <div style="background:#e6fdf9; border:1px solid #4EDFCE; color:#0fa37f; padding:12px 18px; border-radius:8px; margin-bottom:24px; font-size:14px;">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div style="background:#fdecea; border:1px solid #ff205f; color:#ff205f; padding:12px 18px; border-radius:8px; margin-bottom:24px; font-size:14px;">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <div class="tournament-title" style="margin:0;">All Events</div>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('events.create')); ?>" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px;">+ Create Event</a>
                <?php endif; ?>
            </div>

            <?php if($events->isEmpty()): ?>
                <p style="color:#878787; text-align:center; padding:60px 0;">No events available right now. Check back soon.</p>
            <?php else: ?>
                <?php
                    $allEvents = \App\Models\Event::with('organizer')
                        ->orderByDesc('is_featured')
                        ->orderByDesc('start_date')
                        ->get();
                ?>

                <div id="events-slider" style="overflow:hidden; position:relative;">
                    <div id="events-track" style="display:flex; gap:20px; transition: transform 0.4s cubic-bezier(.4,0,.2,1);">
                        <?php $__currentLoopData = $allEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="event-slide">
                                <div class="review-item" style="margin-bottom:0; width:100%;">
                                    <div class="review-cover set-bg" data-setbg="<?php echo e(asset('landing/img/slider-1.png')); ?>" style="height:200px; background-size:cover; background-position:center; position:relative;">
                                        <?php if($event->is_featured): ?>
                                            <div class="featured-badge">⭐ Featured</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="event-text">
                                        <span class="status-badge status-<?php echo e($event->status); ?>"><?php echo e(ucfirst($event->status)); ?></span>
                                        <h4 style="margin-bottom:12px;">
                                            <a href="<?php echo e(route('events.show', $event)); ?>"><?php echo e($event->name); ?></a>
                                        </h4>
                                        <div class="ti-text">
                                            <ul>
                                                <li><span>Type:</span> <?php echo e(ucfirst(str_replace('_', ' ', $event->type))); ?></li>
                                                <li><span>Starts:</span> <?php echo e($event->start_date->format('M d, Y')); ?></li>
                                                <li><span>Ends:</span> <?php echo e($event->end_date->format('M d, Y')); ?></li>
                                                <li><span>Location:</span> <?php echo e($event->location); ?></li>
                                                <li><span>Organizer:</span> <?php echo e($event->organizer->name ?? 'Shuttl'); ?></li>
                                                <?php if($event->max_participants): ?>
                                                    <li><span>Slots:</span> <?php echo e($event->max_participants); ?> participants</li>
                                                <?php endif; ?>
                                            </ul>
                                            <?php if($event->description): ?>
                                                <p style="font-size:13px; color:#878787; margin-bottom:14px;"><?php echo e(Str::limit($event->description, 90)); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                            <a href="<?php echo e(route('events.show', $event)); ?>" class="site-btn btn-sm" style="font-size:13px; padding:7px 18px;">View Details</a>
                                            <?php if(auth()->guard()->check()): ?>
                                                <?php if($event->status === 'open'): ?>
                                                    <form method="POST" action="<?php echo e(route('events.join', $event)); ?>" style="margin:0;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn-join">Join</button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="text-center pt-4" style="margin-top:24px;">
                    <ul id="events-pagination" style="display:inline-flex; gap:8px; list-style:none; padding:0; margin:0; align-items:center;">
                        <li>
                            <a id="btn-prev" href="#" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:50%; background:#e5e5e5; color:#131313; font-size:13px; text-decoration:none; transition:all .2s;">
                                <i class="fa fa-angle-left"></i>
                            </a>
                        </li>
                        <li id="page-numbers" style="display:inline-flex; gap:8px;"></li>
                        <li>
                            <a id="btn-next" href="#" style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:50%; background:#e5e5e5; color:#131313; font-size:13px; text-decoration:none; transition:all .2s;">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
    (function () {
        const slider = document.getElementById('events-slider');
        const track = document.getElementById('events-track');
        const pageNumbers = document.getElementById('page-numbers');
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');

        if (!track) return;

        const slides = Array.from(track.querySelectorAll('.event-slide'));
        const perPage = 3;
        const totalPages = Math.ceil(slides.length / perPage);
        const gap = 20;
        let currentPage = 0;

        function goTo(page) {
            currentPage = Math.max(0, Math.min(page, totalPages - 1));

            const slideWidth = slides[0]?.getBoundingClientRect().width || 0;
            let offset = currentPage * perPage * (slideWidth + gap);

            const maxOffset = Math.max(0, track.scrollWidth - slider.offsetWidth);
            offset = Math.min(offset, maxOffset);

            track.style.transform = `translateX(-${offset}px)`;
            renderDots();
        }

        function renderDots() {
            pageNumbers.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('li');
                dot.innerHTML = `<a href="#" data-page="${i}" style="
                    display:inline-flex; align-items:center; justify-content:center;
                    width:38px; height:38px; border-radius:50%;
                    background:${i === currentPage ? '#4EDFCE' : '#e5e5e5'};
                    color:#131313;
                    font-size:14px; font-weight:${i === currentPage ? '700' : '400'};
                    text-decoration:none; transition:all .2s;">
                    ${i + 1}
                </a>`;
                dot.querySelector('a').addEventListener('click', (e) => {
                    e.preventDefault();
                    goTo(i);
                });
                pageNumbers.appendChild(dot);
            }

            btnPrev.style.opacity = currentPage === 0 ? '0.4' : '1';
            btnPrev.style.pointerEvents = currentPage === 0 ? 'none' : 'auto';
            btnNext.style.opacity = currentPage === totalPages - 1 ? '0.4' : '1';
            btnNext.style.pointerEvents = currentPage === totalPages - 1 ? 'none' : 'auto';
        }

        btnPrev.addEventListener('click', (e) => { e.preventDefault(); goTo(currentPage - 1); });
        btnNext.addEventListener('click', (e) => { e.preventDefault(); goTo(currentPage + 1); });

        [btnPrev, btnNext].forEach(btn => {
            btn.addEventListener('mouseenter', () => { if (btn.style.opacity !== '0.4') btn.style.background = '#4EDFCE'; });
            btn.addEventListener('mouseleave', () => { btn.style.background = '#e5e5e5'; });
        });

        window.addEventListener('resize', () => goTo(currentPage));

        goTo(0);
    })();
    </script>

    <!-- Casual Matches -->
    <section class="review-section review-dark spad set-bg" data-setbg="<?php echo e(asset('landing/img/review-bg-2.jpg')); ?>">
        <div class="container">
            <div class="section-title text-white">
                <h2>Casual Matches</h2>
            </div>

            <?php if($casualGames->isEmpty()): ?>
                <p style="color:#9ca3af; text-align:center; padding:30px 0;">No casual matches recorded yet.</p>
            <?php else: ?>
                <div class="row text-white">
                    <?php $__currentLoopData = $casualGames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $team1 = $game->gamePlayers->where('team_side', 1);
                            $team2 = $game->gamePlayers->where('team_side', 2);
                            $colors1 = ['#4EDFCE', '#2bb3a3'];
                            $colors2 = ['#ff205f', '#c9174c'];
                        ?>
                        <div class="col-lg-4 col-md-6" style="margin-bottom:24px;">
                            <div class="casual-item">
                                <!-- Team 1 -->
                                <div>
                                    <?php $__currentLoopData = $team1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $gp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="player-avatar" style="background: <?php echo e($colors1[$i % 2]); ?>; color: #131313;">
                                            <?php echo e(strtoupper(substr($gp->player->name, 0, 1))); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <p style="font-weight:600; margin-bottom:0;">
                                        <?php echo e($team1->map(fn($gp) => $gp->player->name)->implode(' & ')); ?>

                                    </p>
                                </div>

                                <div class="versus">vs</div>

                                <!-- Team 2 -->
                                <div>
                                    <?php $__currentLoopData = $team2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $gp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="player-avatar" style="background: <?php echo e($colors2[$i % 2]); ?>; color: #fff;">
                                            <?php echo e(strtoupper(substr($gp->player->name, 0, 1))); ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <p style="font-weight:600; margin-bottom:0;">
                                        <?php echo e($team2->map(fn($gp) => $gp->player->name)->implode(' & ')); ?>

                                    </p>
                                </div>

                                <!-- Meta -->
                                <div style="margin-top:14px; font-size:12px; color:#9ca3af; border-top:1px solid rgba(255,255,255,0.1); padding-top:12px;">
                                    <span style="text-transform:uppercase; letter-spacing:.5px;"><?php echo e(ucfirst(str_replace('_', ' ', $game->format))); ?></span>
                                    &nbsp;·&nbsp; <?php echo e($game->event->name); ?>

                                    <?php if($game->status === 'completed'): ?>
                                        &nbsp;·&nbsp; <span style="color:#4EDFCE;">Completed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="text-center pt-4">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('events.index')); ?>" class="site-btn">Find a Match</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="site-btn">Login to Join</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container">
            <ul class="footer-menu">
                <li><a href="<?php echo e(route('landing')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('events.index')); ?>">Events</a></li>
                <li><a href="<?php echo e(route('login')); ?>">Calendar</a></li>
                <li><a href="<?php echo e(route('login')); ?>">Statistics</a></li>
                <li><a href="<?php echo e(route('login')); ?>">Tournament</a></li>
            </ul>
            <p class="copyright">Copyright &copy;<?php echo e(date('Y')); ?> Shuttl. All rights reserved.</p>
        </div>
    </footer>

    <script src="<?php echo e(asset('landing/js/jquery-3.2.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/jquery.marquee.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/main.js')); ?>"></script>
</body>
</html><?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/events/index.blade.php ENDPATH**/ ?>