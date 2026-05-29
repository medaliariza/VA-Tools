<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'VA Tools'); ?></title>
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php endif; ?>
</head>
<body class="dashboard-theme app-theme">
    <div class="page-shell dashboard-shell app-shell">
        <header class="topbar dashboard-topbar app-topbar">
            <a class="brand" href="<?php echo e(auth()->check() ? route('dashboard') : route('home')); ?>">
                <img class="brand-mark brand-logo-image" src="<?php echo e(asset('images/va-tools-logo.png')); ?>" alt="VA Tools logo">
                <span>VA Tools</span>
            </a>

            <?php if(auth()->guard()->check()): ?>
                <?php ($unreadNotifications = \App\Models\Notification::query()->where('user_id', auth()->id())->where('seen', false)->count()); ?>
                <?php ($unreadChats = \App\Models\Message::query()->where('receiver_id', auth()->id())->whereNull('read_at')->count()); ?>
                <nav class="nav-links" aria-label="Primary navigation">
                    <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('dashboard')]); ?>">Dashboard</a>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('tasks.*')]); ?>">Tasks</a>
                    <a href="<?php echo e(route('notes.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('notes.*')]); ?>">Notes</a>
                    <a href="<?php echo e(route('inventory.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('inventory.*')]); ?>">Inventory</a>
                    <a href="<?php echo e(route('reports.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('reports.*')]); ?>">Reports</a>
                    <a href="<?php echo e(route('profile.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['nav-icon-link', 'active' => request()->routeIs('profile.*')]); ?>" aria-label="Profile" title="Profile">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </a>
                    <a href="<?php echo e(route('chat.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['nav-icon-link', 'active' => request()->routeIs('chat.*')]); ?>" aria-label="Chat" title="Chat">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                        <?php if($unreadChats > 0): ?>
                            <span class="nav-badge"><?php echo e($unreadChats); ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo e(route('notifications.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['nav-icon-link', 'active' => request()->routeIs('notifications.*')]); ?>" aria-label="Notifications" title="Notifications">
                        <svg class="nav-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M10 21h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <?php if($unreadNotifications > 0): ?>
                            <span class="nav-badge"><?php echo e($unreadNotifications); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if(auth()->user()->canManageOrganization()): ?>
                        <a href="<?php echo e(route('organization.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('organization.*')]); ?>">Organization</a>
                    <?php endif; ?>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.*')]); ?>">Admin</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

            <div class="topbar-actions">
                <?php if(auth()->guard()->check()): ?>
                    <span class="user-chip"><?php echo e(auth()->user()->fullname); ?> | <?php echo e(strtoupper(auth()->user()->role)); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="button-dark button-small">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="button-dark button-small">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="button-light button-small">Sign Up</a>
                <?php endif; ?>
            </div>
        </header>

        <?php if(session('status')): ?>
            <div class="flash flash-success"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="flash flash-error">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html>
<?php /**PATH C:\va-tools\resources\views/layouts/app.blade.php ENDPATH**/ ?>