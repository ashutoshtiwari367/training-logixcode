<?php
/**
 * Logout
 * admin/logout.php
 */

session_start();
require_once __DIR__ . '/../config/auth.php';

// Logout user
logoutUser();

// Redirect to login page
header('Location: login.php?message=logged_out');
exit;
