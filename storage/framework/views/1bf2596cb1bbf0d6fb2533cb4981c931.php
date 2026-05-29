<?php $__env->startSection('content'); ?>     
    <section class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <p class="dashboard-badge">The Ultimate VA Platform</p>
            <h1 class="dashboard-title">
                Manage Your <span>Workspace</span><br>
                With Total Precision.
            </h1>
            <p class="dashboard-lead">
                VA Tools is the centralized hub for Virtual Assistants and organizations. One
                workspace to manage tasks, notes, reports, messages, inventory, and team access.
            </p>

            <div class="dashboard-actions">
                <a href="<?php echo e(route('tasks.index')); ?>" class="dashboard-primary-action">Open Tasks</a>
                <a href="<?php echo e(route('profile.index')); ?>" class="dashboard-secondary-action">Open Profile</a>
                <?php if(auth()->user()->canManageOrganization()): ?>
                    <a href="<?php echo e(route('organization.index')); ?>" class="dashboard-tertiary-link">Manage Organization</a>
                <?php endif; ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="dashboard-tertiary-link">Manage Access</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="dashboard-stats-grid">
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Tasks</span>
            <strong><?php echo e($todoCount); ?></strong>
            <p>Personal task items tracked in your workspace.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Notes</span>
            <strong><?php echo e($noteCount); ?></strong>
            <p>Saved references and written updates for your account.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Inventory</span>
            <strong><?php echo e($inventoryCount); ?></strong>
            <p>Items and equipment currently listed in the system.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Messages</span>
            <strong><?php echo e($messageCount); ?></strong>
            <p>Conversation records available inside the chat module.</p>
        </article>
    </section>

    <section class="dashboard-card-grid">
        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Recent Activity</p>
                <h3>Recent To-Do Items</h3>
            </div>
            <?php if($recentTodos->isEmpty()): ?>
                <p class="dashboard-helper-text">No tasks yet for this account.</p>
            <?php else: ?>
                <ul class="dashboard-item-list">
                    <?php $__currentLoopData = $recentTodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="dashboard-item-main">
                                <strong><?php echo e($todo->task); ?></strong>
                                <span>Personal task item</span>
                            </div>
                            <span class="dashboard-item-pill"><?php echo e(ucfirst($todo->status)); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Saved Content</p>
                <h3>Saved Notes</h3>
            </div>
            <?php if($recentNotes->isEmpty()): ?>
                <p class="dashboard-helper-text">No notes yet for this account.</p>
            <?php else: ?>
                <ul class="dashboard-item-list">
                    <?php $__currentLoopData = $recentNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="dashboard-item-main">
                                <strong><?php echo e($note->title); ?></strong>
                                <span><?php echo e(\Illuminate\Support\Str::limit($note->content, 90)); ?></span>
                            </div>
                            <span class="dashboard-item-pill">Note</span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">System Access</p>
                <h3>Authentication Summary</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>Email verification</span><strong><?php echo e(auth()->user()->hasVerifiedEmail() ? 'Verified' : 'Pending'); ?></strong></li>
                <li><span>Session driver</span><strong>Database</strong></li>
                <li><span>Role</span><strong><?php echo e(strtoupper(auth()->user()->role)); ?></strong></li>
                <li><span>Premium</span><strong><?php echo e(auth()->user()->isPremium() ? 'Enabled' : 'Standard'); ?></strong></li>
            </ul>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Organization</p>
                <h3>Account Access Snapshot</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>Admin accounts</span><strong><?php echo e($adminUserCount); ?></strong></li>
                <li><span>User accounts</span><strong><?php echo e($userAccountCount); ?></strong></li>
                <li><span>My members</span><strong><?php echo e($organizationMemberCount); ?></strong></li>
                <li><span>Department</span><strong><?php echo e(auth()->user()->department ?: 'Not assigned'); ?></strong></li>
                <li><span>Organization</span><strong><?php echo e(auth()->user()->organization ?: 'Not assigned'); ?></strong></li>
            </ul>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Dashboard | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views\dashboard.blade.php ENDPATH**/ ?>