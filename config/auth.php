<?php
/**
 * Authentication Configuration
 * config/auth.php
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirect helper that respects the BASE_URL constant (defined in config/db.php)
 */
function adminRedirect(string $path): void {
    $url = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    header('Location: ' . $url);
    exit;
}

/** Check if user is logged in */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
}

/** Check if user has specific role */
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user_role'] === $role;
}

/** Require login */
function requireLogin() {
    if (!isLoggedIn()) {
        // Use BASE_URL for proper sub‑folder handling
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}

/** Require specific role */
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        die('Access Denied. You do not have permission to access this page.');
    }
}

/** Require admin role */
function requireAdmin() {
    requireRole('admin');
}

/** Login user */
function loginUser($userId, $userEmail, $userName, $userRole) {
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $userEmail;
    $_SESSION['user_name'] = $userName;
    $_SESSION['user_role'] = $userRole;
    $_SESSION['login_time'] = time();
}

/** Logout user */
function logoutUser() {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
}

/** Verify password */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/** Hash password */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/** Get current user data */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'    => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'name'  => $_SESSION['user_name'],
        'role'  => $_SESSION['user_role'],
    ];
}

/** Check session timeout (optional, 30 minutes) */
function checkSessionTimeout($timeout = 1800) {
    if (isLoggedIn() && isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > $timeout) {
            logoutUser();
            return false;
        }
        // Refresh activity timestamp
        $_SESSION['login_time'] = time();
    }
    return true;
}
?>
