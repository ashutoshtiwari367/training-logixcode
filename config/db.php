<?php
/**
 * Database Configuration
 * config/db.php
 */

// ============================================================
// LOCAL (XAMPP) — active for local development
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'training_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// ============================================================
// PRODUCTION (Hostinger) — uncomment below when deploying
// ============================================================
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'u447123054_institute_regi');
// define('DB_USER', 'u447123054_institute_regi');
// define('DB_PASS', 'U447123054_institute_regi');

define('INSTITUTE_NAME', 'Logixcode It Solution');
define('DB_CHARSET', 'utf8mb4');

// Registration & Payment Configuration
define('REGISTRATION_FEE', 500); // Amount in INR
define('RAZORPAY_KEY_ID', 'rzp_live_SCsSRFlYFL6QAC');
define('RAZORPAY_KEY_SECRET', '03oDNzeRZpfUf4q9B9CWXuxs');

// PDO Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please contact administrator.");
}

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
