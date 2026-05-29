<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>My Profile</h1>
            <p>Manage your personal details, workspace identity, and profile information in one place.</p>
        </div>
        <span class="pill"><?php echo e(strtoupper($user->role)); ?> Account</span>
    </section>

    <section class="split-grid">
        <article class="card">
            <div class="profile-summary">
                <?php if($user->avatar): ?>
                    <img class="avatar" src="<?php echo e(route('profile.avatar')); ?>" alt="Profile avatar">
                <?php else: ?>
                    <div class="avatar"><?php echo e(strtoupper(substr($user->fullname, 0, 2))); ?></div>
                <?php endif; ?>
                <div>
                    <h3><?php echo e($user->fullname); ?></h3>
                    <p class="meta-text"><?php echo e($user->email); ?></p>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="form-grid">
                <?php echo csrf_field(); ?>
                <div class="field-full">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="8"><?php echo e(old('bio', $user->bio)); ?></textarea>
                </div>
                <div class="field-full">
                    <label for="avatar">Profile Image</label>
                    <input id="avatar" type="file" name="avatar" accept="image/*">
                </div>
                <div class="field-full">
                    <button type="submit" class="button-dark">Save Profile</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h3>Account Summary</h3>
            <ul class="clean-list">
                <li><span>Full name</span><strong><?php echo e($user->fullname); ?></strong></li>
                <li><span>Email</span><strong><?php echo e($user->email); ?></strong></li>
                <li><span>Role</span><strong><?php echo e($user->role); ?></strong></li>
                <li><span>Department</span><strong><?php echo e($user->department ?: 'Not assigned'); ?></strong></li>
                <li><span>Organization</span><strong><?php echo e($user->organization ?: 'Not assigned'); ?></strong></li>
            </ul>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Profile | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\profile\index.blade.php ENDPATH**/ ?>