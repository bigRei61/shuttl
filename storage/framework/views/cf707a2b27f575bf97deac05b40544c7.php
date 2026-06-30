<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Shuttl</title>
    <link href="<?php echo e(asset('landing/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <style>
        .header-logo img { width:120px }
        .events-list { max-width:1000px; margin:28px auto; }
        .event-card { background:#fff; border:1px solid #e6eef0; padding:16px; border-radius:8px; margin-bottom:12px }
        body { background:#f8fafc; font-family:Inter, sans-serif; }
    </style>
</head>
<body>
    <header class="header-section">
        <div class="container">
            <a class="header-logo" href="<?php echo e(route('landing')); ?>">
                <img src="<?php echo e(asset('images/fullLogo.png')); ?>" alt="Shuttl">
            </a>
            <div class="user-panel">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('profile')); ?>"><?php echo e(auth()->user()->name); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>">Login</a> / <a href="<?php echo e(route('register')); ?>">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="events-list">
        <div class="container">
            <h1>Events</h1>
            <?php if($events->isEmpty()): ?>
                <p>No events available.</p>
            <?php else: ?>
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event-card">
                        <h3><?php echo e($event->name); ?></h3>
                        <p><?php echo e($event->location); ?> — <?php echo e($event->start_date?->toFormattedDateString() ?? 'TBD'); ?></p>
                        <p><?php echo e(Illuminate\Support\Str::limit($event->description, 160)); ?></p>
                        <a href="<?php echo e(route('events.show', $event)); ?>">View</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/events/index.blade.php ENDPATH**/ ?>