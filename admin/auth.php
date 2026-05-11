<?php
/**
 * Admin Authentication Guard
 * Include at the top of every protected admin page.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . (dirname($_SERVER['PHP_SELF']) === '/admin' ? '' : '../') . 'index.php?redirect=1');
    exit;
}

// Session timeout: 2 hours
if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity']) > 7200) {
    session_destroy();
    header('Location: index.php?timeout=1');
    exit;
}
$_SESSION['admin_last_activity'] = time();

$admin_user = [
    'id'        => $_SESSION['admin_id'] ?? 0,
    'username'  => $_SESSION['admin_username'] ?? 'Admin',
    'full_name' => $_SESSION['admin_name'] ?? 'Administrator',
    'role'      => $_SESSION['admin_role'] ?? 'admin',
];
