<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: auth/login.php');
    exit;
}

// Optional: Check session timeout (24 hours)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 86400)) {
    session_destroy();
    header('Location: auth/login.php');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>