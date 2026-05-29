<?php $__env->startSection('content'); ?>
    <section class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <p class="dashboard-badge">Your All-In-One Workspace</p>
            <h1 class="dashboard-title">
                Run Your Workday In<br>
                <span>One Smart Platform</span>
            </h1>
            <p class="dashboard-lead">
                VA Tools is the all-in-one website for virtual assistants, teams, and growing businesses
                that want to manage tasks, notes, reports, files, chat, profiles, and access control in
                one powerful workspace.
            </p>

            <div class="dashboard-actions">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="dashboard-primary-action">Go To My Workspace</a>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="dashboard-secondary-action">Start Managing Work</a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="dashboard-primary-action">Start Using VA Tools</a>
                    <a href="<?php echo e(route('login')); ?>" class="dashboard-secondary-action">Sign In To Continue</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="dashboard-stats-grid">
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Accounts</span>
            <strong>Built To Scale</strong>
            <p>Support regular users and admins with a clean, reliable role-based system.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Security</span>
            <strong>Protected Access</strong>
            <p>Email verification, password recovery, and Laravel authentication are ready from day one.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Messaging</span>
            <strong>Team Communication</strong>
            <p>Keep conversations organized with direct messaging built into your workspace.</p>
        </article>
        <article class="dashboard-stat-card">
            <span class="dashboard-stat-label">Reports</span>
            <strong>Work Submission Ready</strong>
            <p>Send reports with attachments and keep important updates easy to find and review.</p>
        </article>
    </section>

    <section class="dashboard-card-grid">
        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">About</p>
                <h3>Your Business Hub In One Place</h3>
            </div>
            <p class="dashboard-helper-text">
                VA Tools helps you replace scattered tools with one centralized website where daily work,
                communication, files, and team activity all stay connected.
            </p>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Mission</p>
                <h3>Work Faster With Less Friction</h3>
            </div>
            <p class="dashboard-helper-text">
                Our goal is to give users one dependable system for managing operations, improving
                communication, and staying productive without jumping between multiple websites.
            </p>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Core Features</p>
                <h3>Everything You Need, Together</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>Tasks and notes</span><strong>Plan, track, and organize work daily</strong></li>
                <li><span>Reports and files</span><strong>Submit updates with supporting attachments</strong></li>
                <li><span>Inventory</span><strong>Manage shared records in one place</strong></li>
                <li><span>Profiles and messaging</span><strong>Keep communication and identity connected</strong></li>
            </ul>
        </article>

        <article class="dashboard-panel">
            <div class="dashboard-panel-heading">
                <p class="dashboard-section-label">Access Model</p>
                <h3>Simple Access, Powerful Control</h3>
            </div>
            <ul class="dashboard-clean-list">
                <li><span>User account</span><strong>Get your own complete workspace experience</strong></li>
                <li><span>Admin account</span><strong>Manage users and oversee the full platform</strong></li>
                <li><span>Email verification</span><strong>Strengthens trust and account security</strong></li>
                <li><span>Password recovery</span><strong>Quick Gmail reset code support</strong></li>
            </ul>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Home | VA Tools'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\va-tools\resources\views/welcome.blade.php ENDPATH**/ ?>