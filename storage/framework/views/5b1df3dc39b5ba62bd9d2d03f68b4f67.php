
<?php $__env->startSection('title', 'Register'); ?>

<?php $__env->startSection('content'); ?>
<h2 class="text-2xl font-bold text-white mb-2">Create account</h2>
<p class="text-gray-400 text-sm mb-8">Join the Shuttl community</p>

<form method="POST" action="<?php echo e(route('register.post')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
        <label class="block text-sm text-gray-400 mb-1">Full Name</label>
        <input type="text" name="name" value="<?php echo e(old('name')); ?>"
               class="w-full bg-gray-800 border <?php echo e($errors->has('name') ? 'border-red-500' : 'border-gray-700'); ?>

                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="John Doe">
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-1">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
               class="w-full bg-gray-800 border <?php echo e($errors->has('email') ? 'border-red-500' : 'border-gray-700'); ?>

                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="you@example.com">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full bg-gray-800 border <?php echo e($errors->has('password') ? 'border-red-500' : 'border-gray-700'); ?>

                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="••••••••">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full bg-gray-800 border border-gray-700
                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
                   placeholder="••••••••">
        </div>
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-1">Phone Number</label>
        <input type="text" name="phone" value="<?php echo e(old('phone')); ?>"
               class="w-full bg-gray-800 border <?php echo e($errors->has('phone') ? 'border-red-500' : 'border-gray-700'); ?>

                      text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500"
               placeholder="+63 912 345 6789">
        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-1">Gender</label>
            <select name="gender"
                    class="w-full bg-gray-800 border <?php echo e($errors->has('gender') ? 'border-red-500' : 'border-gray-700'); ?>

                           text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
                <option value="">Select</option>
                <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>Other</option>
            </select>
            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-1">Date of Birth</label>
            <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>"
                   class="w-full bg-gray-800 border <?php echo e($errors->has('date_of_birth') ? 'border-red-500' : 'border-gray-700'); ?>

                          text-white rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-teal-500">
            <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <button type="submit"
            class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3 rounded-lg transition-colors duration-200 mt-2">
        Create Account
    </button>

    <p class="text-center text-sm text-gray-500">
        Already have an account?
        <a href="<?php echo e(route('login')); ?>" class="text-teal-400 hover:text-teal-300">Sign in</a>
    </p>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Raymundo Gudmalin\Shuttl\resources\views/auth/register.blade.php ENDPATH**/ ?>