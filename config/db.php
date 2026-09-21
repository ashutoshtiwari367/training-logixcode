<?php
/**
 * Database Configuration
 * config/db.php
 */


// Automatic Environment Detection
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if ($httpHost === 'localhost' || $httpHost === '127.0.0.1' || strpos($requestUri, '/training/') !== false) {
    // LOCAL (XAMPP) — MySQL running on port 3307
    define('DB_HOST', '127.0.0.1');
    define('DB_PORT', 3307);
    define('DB_NAME', 'training_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('BASE_URL', '/training/');
} else {
    // PRODUCTION (Hostinger)
    define('DB_HOST', 'srv2108.hstgr.io');
    define('DB_PORT', 3306);
    define('DB_NAME', 'u447123054_institute_regi');
    define('DB_USER', 'u447123054_institute_regi');
    define('DB_PASS', 'Mu$k@n1106');
    define('BASE_URL', '/');
}

if (!defined('INSTITUTE_NAME')) define('INSTITUTE_NAME', 'Logixcode It Solution');
define('DB_CHARSET', 'utf8mb4');

// Registration & Payment Configuration
define('REGISTRATION_FEE', 699); // Amount in INR
define('RAZORPAY_KEY_ID', 'rzp_live_SCsSRFlYFL6QAC');
define('RAZORPAY_KEY_SECRET', '03oDNzeRZpfUf4q9B9CWXuxs');

// PDO Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        // Do NOT echo anything here — it will corrupt JSON API responses
        // If this is an AJAX/API request, return JSON error
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
            basename($_SERVER['PHP_SELF']) === 'process_registration.php') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed. Please try again later.']);
        } else {
            echo "<div style='color:red;font-family:sans-serif;padding:20px'>❌ Database connection failed. Please try again later.</div>";
        }
        exit;
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
