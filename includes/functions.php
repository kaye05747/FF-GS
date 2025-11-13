<?php
// ✅ Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Include database connection from config
require_once __DIR__ . '/../config/db.php';

// ✅ CSRF token generator
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}



// ✅ CSRF token verification
function verify_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

// ✅ Sanitize user input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// ✅ Require login (for all normal users)
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

// ✅ Require admin access
function checkAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: admin/admin_login.php');
        exit;
    }
}

// ✅ Logout function
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}
