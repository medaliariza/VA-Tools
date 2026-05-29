<?php $__env->startSection('content'); ?>
    <section class="page-hero">
        <div>
            <h1>Notifications</h1>
            <p>Review account alerts, task updates, report requests, and workspace messages in one place.</p>
        </div>
        <div class="inline-actions">
            <span class="pill"><?php echo e($unreadCount); ?> Unread</span>
            <?php if($unreadCount > 0): ?>
                <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="button-light button-small">Read All</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <section class="table-card">
        <h3>Recent Notifications</h3>
        <?php if($notifications->isEmpty()): ?>
            <p class="helper-text">No notifications yet.</p>
        <?php else: ?>
            <ul class="clean-list">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <span><?php echo e($notification->text); ?></span>
                        <div class="inline-actions">
                            <strong><?php echo e($notification->seen ? 'Seen' : 'New'); ?></strong>
                            <?php if (! ($notification->seen)): ?>
                                <form method="POST" action="<?php echo e(route('notifications.read', $notification)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="button-light button-small">Mark Read</button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('notifications.destroy', $notification)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="button-light button-small">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Notifications | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\notifications\index.blade.php ENDPATH**/ ?>