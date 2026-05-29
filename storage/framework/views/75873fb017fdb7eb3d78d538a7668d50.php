<?php $__env->startSection('content'); ?>
    <section class="auth-shell">
        <div class="auth-card">
            <p class="eyebrow">Gmail OTP</p>
            <h1>Enter your 6-digit code</h1>
            <p class="helper-text">We sent a one-time code to <?php echo e($email); ?>. It expires in 10 minutes.</p>

            <form method="POST" action="<?php echo e(route('auth.otp.verify')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="code">Verification code</label>
                    <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" required autofocus>
                </div>

                <div class="field-full">
                    <button type="submit" class="button-dark">Verify Code</button>
                </div>
            </form><br>
            <form method="POST" action="<?php echo e(route('auth.otp.resend')); ?>" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <button type="submit" class="button-light">Resend Gmail OTP</button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Gmail OTP | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\auth\otp.blade.php ENDPATH**/ ?>