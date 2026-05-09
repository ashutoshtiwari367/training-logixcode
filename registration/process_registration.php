<?php
/**
 * Process Registration Form
 * registration/process_registration.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => ''];

try {
    // Check if POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRF($_POST['csrf_token'])) {
        throw new Exception('Invalid security token. Please refresh the page and try again.');
    }

    // Validate required fields
    $requiredFields = [
        'firstName', 'lastName', 'email', 'phone', 'dob', 'gender',
        'address', 'qualification', 'percentage', 'program', 'terms'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("Required field missing: {$field}");
        }
    }

    // Validate email format
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new Exception('Invalid email address format');
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered. Please use a different email address.');
    }

    // Validate phone number format
    $phone = trim($_POST['phone']);
    if (!preg_match('/^\+91[0-9]{10}$/', $phone)) {
        throw new Exception('Invalid phone number format. Use +91XXXXXXXXXX');
    }

    // Validate date of birth
    $dob = trim($_POST['dob']);
    $dobDate = new DateTime($dob);
    $today = new DateTime();

    // FIX: Check if DOB is in the future
    if ($dobDate >= $today) {
        throw new Exception('Date of birth cannot be in the future');
    }

    $age = $today->diff($dobDate)->y;
    if ($age < 10) {
        throw new Exception('You must be at least 10 years old to register');
    }

    // Validate gender
    $allowedGenders = ['male', 'female', 'other'];
    if (!in_array($_POST['gender'], $allowedGenders)) {
        throw new Exception('Invalid gender selection');
    }

    // Validate percentage
    $percentage = trim($_POST['percentage']);
    if (!is_numeric($percentage) || $percentage < 0 || $percentage > 100) {
        throw new Exception('Invalid percentage value. Must be between 0 and 100');
    }

    // Validate year of passing if provided
    if (!empty($_POST['yearOfPassing'])) {
        $yearOfPassing = trim($_POST['yearOfPassing']);
        if (!preg_match('/^[0-9]{4}$/', $yearOfPassing) || (int)$yearOfPassing < 1950 || (int)$yearOfPassing > (int)date('Y')) {
            throw new Exception('Invalid year of passing');
        }
    }

    // Sanitize all inputs
    $formData = [
        'firstName'     => sanitizeInput($_POST['firstName']),
        'lastName'      => sanitizeInput($_POST['lastName']),
        'email'         => $email,
        'phone'         => $phone,
        'dob'           => $dob,
        'gender'        => $_POST['gender'],
        'address'       => sanitizeInput($_POST['address']),
        'qualification' => sanitizeInput($_POST['qualification']),
        'percentage'    => $percentage,
        'college'       => !empty($_POST['college']) ? sanitizeInput($_POST['college']) : null,
        'yearOfPassing' => !empty($_POST['yearOfPassing']) ? sanitizeInput($_POST['yearOfPassing']) : null,
        'program'       => sanitizeInput($_POST['program']),
        'experience'    => !empty($_POST['experience']) ? sanitizeInput($_POST['experience']) : null,
        'motivation'    => !empty($_POST['motivation']) ? sanitizeInput($_POST['motivation']) : null,
        'updates'       => isset($_POST['updates']) ? 1 : 0,
        'payment_mode'  => 'ONLINE'
    ];

    // Store form data in session for payment processing
    $_SESSION['registration_data']      = $formData;
    $_SESSION['registration_timestamp'] = time();

    // FIX: Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // FIX: Return redirect URL so frontend knows where to go
    $response['success']  = true;
    $response['message']  = 'Registration data validated successfully';
    $response['redirect'] = 'https://training.logixcode.com/registration/payment.php';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Registration Error: " . $e->getMessage());
}

echo json_encode($response);
