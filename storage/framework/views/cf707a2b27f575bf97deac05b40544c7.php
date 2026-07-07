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
            overflow-wrap: anywhere;
        }
        .event-text h4 a { color: #131313; text-decoration: none; }
        .event-text h4 a:hover { color: #4EDFCE; }
        .event-text .ti-text { flex: 1; }
        .event-text .ti-text ul { list-style: none; padding: 0; margin: 0; }
        .event-text .ti-text ul li { font-size: 13px; color: #878787; margin-bottom: 5px; }
        .event-text .ti-text ul li span { color: #131313; font-weight: 600; margin-right: 6px; }
        .event-description {
            font-size: 13px;
            color: #878787;
            margin: 12px 0 0;
            line-height: 1.55;
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
        }
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
                <div class="tournament-title" style="margin:0;">Active Events</div>
                <?php if(auth()->guard()->check()): ?>
                    <button type="button" id="open-event-modal" class="site-btn btn-sm" style="font-size:13px; padding:8px 22px; border:0;">+ Create Event</button>
                <?php endif; ?>
            </div>

            <?php if($events->isEmpty()): ?>
                <p style="color:#878787; text-align:center; padding:60px 0;">No events available right now.</p>
            <?php else: ?>
                <div class="events-grid">
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $participation = $event->players->firstWhere('id', auth()->id())?->pivot?->status;
                            $isHost = (int) $event->organizer_id === (int) auth()->id();
                        ?>
                        <div class="event-slide">
                            <article class="event-card">
                                <a href="<?php echo e(route('events.show', $event)); ?>" class="review-cover" style="background-image: url('<?php echo e($event->photoUrl()); ?>');">
                                    <?php if($event->is_featured): ?>
                                        <div class="featured-badge">Featured</div>
                                    <?php endif; ?>
                                </a>
                                <div class="event-text">
                                    <div>
                                        <span class="status-badge status-<?php echo e($event->status); ?>"><?php echo e(ucfirst($event->status)); ?></span>
                                        <h4>
                                            <a href="<?php echo e(route('events.show', $event)); ?>"><?php echo e($event->name); ?></a>
                                        </h4>
                                    </div>
                                    <div class="ti-text">
                                        <ul>
                                            <li><span>Type:</span> <?php echo e(ucfirst(str_replace('_', ' ', $event->type))); ?></li>
                                            <li><span>Starts:</span> <?php echo e($event->start_date->format('M d, Y')); ?></li>
                                            <li><span>Ends:</span> <?php echo e($event->end_date->format('M d, Y')); ?></li>
                                            <li><span>Location:</span> <?php echo e($event->location); ?></li>
                                            <li><span>Host:</span> <?php echo e($event->organizer->name ?? 'Shuttl'); ?></li>
                                            <li><span>Players:</span> <?php echo e($event->approved_players_count ?? 0); ?> approved</li>
                                            <?php if($event->max_participants): ?>
                                                <li><span>Slots:</span> <?php echo e($event->max_participants); ?> participants</li>
                                            <?php endif; ?>
                                        </ul>
                                        <?php if($event->description): ?>
                                            <p class="event-description"><?php echo e(Str::limit($event->description, 120)); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="event-actions">
                                        <a href="<?php echo e(route('events.show', $event)); ?>" class="site-btn btn-sm" style="font-size:13px; padding:7px 18px;">View Details</a>
                                        <?php if($isHost): ?>
                                            <span class="event-pill">Host</span>
                                        <?php elseif($participation === 'approved'): ?>
                                            <span class="event-pill">Joined</span>
                                        <?php elseif($participation === 'pending'): ?>
                                            <span class="event-pill">Pending Approval</span>
                                        <?php elseif($event->status === 'open'): ?>
                                            <form method="POST" action="<?php echo e(route('events.join', $event)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn-join">Request to Join</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if(auth()->guard()->check()): ?>
        <div id="event-modal-overlay" class="modal-overlay" aria-hidden="true">
            <div class="event-modal" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
                <div class="event-modal-header">
                    <h3 id="event-modal-title">Create Event</h3>
                </div>
                <div class="event-modal-body">
                    <form method="POST" action="<?php echo e(route('events.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <?php if(auth()->user()->isAdmin()): ?>
                            <div class="modal-field">
                                <label for="event-host-search">Event Host</label>
                                <p class="modal-help">Search by player name or email, then choose the host.</p>
                                <input id="event-host-search" type="search" autocomplete="off" placeholder="Search player name or email">
                                <select id="event-host-select" name="host_id" style="margin-top:8px;">
                                    <option value="">Select host</option>
                                    <?php $__currentLoopData = $hosts ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $host): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($host->id); ?>" data-search="<?php echo e(Str::lower($host->name.' '.$host->email)); ?>" <?php echo e(old('host_id') == $host->id ? 'selected' : ''); ?>>
                                            <?php echo e($host->name); ?> (<?php echo e($host->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['host_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>

                        <div class="modal-field">
                            <label for="event_name">Event Name</label>
                            <input id="event_name" type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="e.g. Sunday Doubles Cup">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="<?php echo e(auth()->user()->isAdmin() ? 'modal-grid' : ''); ?>">
                            <div class="modal-field">
                                <label for="event_type">Event Type</label>
                                <select id="event_type" name="type">
                                    <option value="">Select type</option>
                                    <option value="tournament" <?php echo e(old('type') == 'tournament' ? 'selected' : ''); ?>>Tournament</option>
                                    <option value="quick_play" <?php echo e(old('type') == 'quick_play' ? 'selected' : ''); ?>>Quick Play</option>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <?php if(auth()->user()->isAdmin()): ?>
                                <div class="modal-field">
                                    <label for="max_participants">Number of Participants</label>
                                    <input id="max_participants" type="number" name="max_participants" min="2" value="<?php echo e(old('max_participants')); ?>" placeholder="e.g. 16">
                                    <?php $__errorArgs = ['max_participants'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="modal-field">
                            <label for="event_location">Location</label>
                            <input id="event_location" type="text" name="location" value="<?php echo e(old('location')); ?>" placeholder="e.g. MTDY Badminton Court">
                            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="modal-grid">
                            <div class="modal-field">
                                <label for="start_date">Start Date</label>
                                <input id="start_date" type="date" name="start_date" value="<?php echo e(old('start_date')); ?>">
                                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="modal-field">
                                <label for="end_date">End Date</label>
                                <input id="end_date" type="date" name="end_date" value="<?php echo e(old('end_date')); ?>">
                                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <?php if(auth()->user()->isAdmin()): ?>
                            <div class="modal-field">
                                <label for="event_description">Description</label>
                                <textarea id="event_description" name="description" placeholder="Describe the tournament, prizes, rules, etc."><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endif; ?>

                        <div class="modal-field">
                            <label for="event_photo">Event Photo</label>
                            <input id="event_photo" type="file" name="photo" accept="image/*">
                            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="modal-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="modal-actions">
                            <button type="button" id="cancel-event-modal" class="modal-cancel">Cancel</button>
                            <button type="submit" class="modal-submit">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

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

        <?php if(isset($errors) && $errors->any()): ?>
            openModal();
        <?php endif; ?>
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
</html>
<?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/events/index.blade.php ENDPATH**/ ?>