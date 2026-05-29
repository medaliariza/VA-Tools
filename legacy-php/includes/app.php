<?php

function app_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function app_require_login()
{
    if (!app_is_logged_in()) {
        header('Location: ../auth/login.php');
        exit;
    }
}

function app_require_role($roles)
{
    app_require_login();

    $roles = (array) $roles;
    $currentRole = $_SESSION['role'] ?? 'employee';

    if (!in_array($currentRole, $roles, true)) {
        header('Location: ../user/dashboard.php');
        exit;
    }
}

function app_redirect_by_role()
{
    $role = $_SESSION['role'] ?? 'employee';

    if ($role === 'admin') {
        header('Location: ../admin/users.php');
        exit;
    }

    header('Location: ../user/dashboard.php');
    exit;
}

function app_user_name()
{
    return $_SESSION['user_name'] ?? 'Workspace User';
}

function app_excerpt($value, $length = 90)
{
    $value = trim((string) $value);

    if (strlen($value) <= $length) {
        return $value;
    }

    return substr($value, 0, $length - 3) . '...';
}
