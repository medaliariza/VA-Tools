<?php $__env->startSection('content'); ?>
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Authentication</p>
            <h1>Log in to your workspace</h1>
            <p class="helper-text">Laravel sessions, email verification, and role-aware routing are now driving access control.</p>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>

                <div class="field-full">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <?php echo $__env->make('auth.partials.captcha', ['captcha' => $captcha], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="field-inline">
                    <label class="checkbox-row">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="inline-link">Forgot password?</a>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Login</button>
                </div>
            </form>

            <p class="auth-links">Need an account? <a href="<?php echo e(route('register')); ?>"><strong>Sign up</strong></a></p>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Log In | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\auth\login.blade.php ENDPATH**/ ?>