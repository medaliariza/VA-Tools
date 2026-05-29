<?php $__env->startSection('content'); ?>
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Password Recovery</p>
            <h1>Request a reset code</h1>
            <p class="helper-text">A 6-digit password reset code will be sent automatically to your registered Gmail address.</p>

            <form method="POST" action="<?php echo e(route('password.email')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Send Reset Code</button>
                </div>
            </form>

            <p class="auth-links"><a href="<?php echo e(route('login')); ?>"><strong>Back to login</strong></a></p>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Forgot Password | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>