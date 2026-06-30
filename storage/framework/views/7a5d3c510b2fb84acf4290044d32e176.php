<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shuttl - <?php echo $__env->yieldContent('title'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Left side - background image -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <img src="<?php echo e(asset('images/loginBG.png')); ?>"
                 alt="Shuttl Background"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
            <div class="relative z-10 flex flex-col justify-end p-12">
                <img src="<?php echo e(asset('images/fullLogo.png')); ?>" alt="Shuttl" class="w-48 mb-4">
                <p class="text-white text-xl font-light">Elevate your game.</p>
                <p class="text-gray-300 text-sm mt-1">The home of badminton enthusiasts.</p>
            </div>
        </div>

        <!-- Right side - form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-950">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="<?php echo e(asset('images/fullLogo.png')); ?>" alt="Shuttl" class="w-36">
                </div>

                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 bg-teal-900 border border-teal-600 text-teal-300 rounded-lg text-sm">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/layouts/auth.blade.php ENDPATH**/ ?>