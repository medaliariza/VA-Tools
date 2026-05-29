<?php
if (!isset($pageTitle)) {
    $pageTitle = 'VA Tools';
}

if (!isset($activeNav)) {
    $activeNav = '';
}

$navLinks = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => '../user/dashboard.php'],
    ['key' => 'chat', 'label' => 'Chat', 'href' => '../user/chat.php'],
    ['key' => 'inventory', 'label' => 'Inventory', 'href' => '../user/inventory.php'],
    ['key' => 'reports', 'label' => 'Reports', 'href' => '../user/report.php'],
    ['key' => 'profile', 'label' => 'Profile', 'href' => '../user/profile.php'],
];

if (($_SESSION['role'] ?? '') === 'admin') {
    $navLinks[] = ['key' => 'users', 'label' => 'Users', 'href' => '../admin/users.php'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo app_escape($pageTitle); ?> | VA Tools</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/app.js" defer></script>
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <a class="brand" href="../index.php">
                <span class="brand-mark">VA</span>
                <span>Virtual Assistant Tools</span>
            </a>

            <div class="app-nav">
                <nav class="nav-links">
                    <?php foreach ($navLinks as $link): ?>
                        <a
                            class="nav-link <?php echo $activeNav === $link['key'] ? 'active' : ''; ?>"
                            href="<?php echo app_escape($link['href']); ?>"
                        >
                            <?php echo app_escape($link['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <span class="user-chip"><?php echo app_escape(app_user_name()); ?> | <?php echo app_escape(strtoupper($_SESSION['role'] ?? 'USER')); ?></span>
                <a class="btn btn-secondary" href="../auth/logout.php">Logout</a>
            </div>
        </header>
