<?php $__env->startSection('content'); ?>
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Create Account</p>
            <h1>Set up your workspace</h1>
            <p class="helper-text">Passwords require at least 12 characters with uppercase, lowercase, numbers, and symbols.</p>

            <form method="POST" action="<?php echo e(route('register')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="fullname">Full name</label>
                    <input id="fullname" type="text" name="fullname" value="<?php echo e(old('fullname')); ?>" required>
                </div>

                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <?php echo $__env->make('auth.partials.captcha', ['captcha' => $captcha], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="field-full">
                    <button type="submit" class="button-dark">Sign Up</button>
                </div>
            </form>

            <p class="auth-links">Already registered? <a href="<?php echo e(route('login')); ?>"><strong>Log in</strong></a></p>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Register | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\auth\register.blade.php ENDPATH**/ ?>