<?php $__env->startSection('content'); ?>
    <section class="auth-shell">
        <div class="auth-card auth-card-wide">
            <div class="verify-grid">
                <div>
                    <p class="eyebrow">Email Verification</p>
                    <h1>Verify your email</h1>
                    <p class="helper-text">
                        Your account is almost ready. Verify your email address to unlock the dashboard,
                        tasks, notes, reports, inventory, and role-based access.
                    </p>

                    <div class="verify-steps">
                        <div class="verify-step">
                            <strong>1</strong>
                            <span>Send a Gmail OTP to your account email address.</span>
                        </div>
                        <div class="verify-step">
                            <strong>2</strong>
                            <span>Enter the 6-digit code on the OTP screen.</span>
                        </div>
                        <div class="verify-step">
                            <strong>3</strong>
                            <span>Once verified, the app will allow full access to your workspace.</span>
                        </div>
                    </div>
                </div>

                <div class="verify-panel">
                    <?php if(session('mail_issue')): ?>
                        <div class="dev-link-box">
                            <p class="eyebrow">Mail Issue</p>
                            <p class="helper-text"><?php echo e(session('mail_issue')); ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="form-grid">
                        <?php echo csrf_field(); ?>
                        <div class="field-full">
                            <button type="submit" class="button-dark">Send Gmail OTP</button>
                        </div>
                    </form>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="button-light">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Verify Email | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>