<?php
/**
 * Application Configuration (Database Removed)
 * config/db.php
 */

// Dynamic BASE_URL detection (works on any XAMPP folder name or root domain)
if (!defined('BASE_URL')) {
    $docRoot  = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')) : '';
    $projRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    if ($docRoot !== '' && $projRoot !== false && strpos($projRoot, $docRoot) === 0) {
        $subPath = trim(substr($projRoot, strlen($docRoot)), '/');
        define('BASE_URL', $subPath === '' ? '/' : '/' . $subPath . '/');
    } else {
        define('BASE_URL', '/');
    }
}

if (!defined('INSTITUTE_NAME')) {
    define('INSTITUTE_NAME', 'Logixcode IT Solution');
}

// Registration & Payment Configuration
if (!defined('REGISTRATION_FEE')) {
    define('REGISTRATION_FEE', 699); // Amount in INR
}
if (!defined('RAZORPAY_KEY_ID')) {
    define('RAZORPAY_KEY_ID', 'rzp_live_SCsSRFlYFL6QAC');
}
if (!defined('RAZORPAY_KEY_SECRET')) {
    define('RAZORPAY_KEY_SECRET', '03oDNzeRZpfUf4q9B9CWXuxs');
}

// Database disabled (no PDO connection required)
$pdo = null;

/**
 * Generate unique registration ID
 */
function generateRegistrationId() {
    return 'REG' . date('Ymd') . strtoupper(substr(uniqid(), -6));
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate CSRF token
 */
function validateCSRF($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

/**
 * Generate CSRF token
 */
function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
