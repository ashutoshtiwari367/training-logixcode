<?php
/**
 * Payment Verification API
 * api/verify-payment.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/mail.php';

header('Content-Type: application/json');

// Initialize response
$response2 = ['success' => false, 'registration_id' => null, 'message' => ''];

try {
    // Check if POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !validateCSRF($_POST['csrf_token'])) {
        throw new Exception('Invalid security token');
    }

    // Check if registration data exists in session
    if (!isset($_SESSION['registration_data'])) {
        throw new Exception('Registration data not found. Please start registration again.');
    }

    // Check session timeout
    if (!isset($_SESSION['registration_timestamp']) || (time() - $_SESSION['registration_timestamp']) > 600) {
        throw new Exception('Session expired. Please start registration again.');
    }

    // Validate payment response
    if (!isset($_POST['razorpay_payment_id'])) {
        throw new Exception('Payment ID not received');
    }

    $paymentId = $_POST['razorpay_payment_id'];
    $orderId = $_POST['razorpay_order_id'] ?? '';
    $signature = $_POST['razorpay_signature'] ?? '';

    // Verify payment signature (if using orders)
    if (!empty($orderId) && !empty($signature)) {
        $razorpaySecret = RAZORPAY_KEY_SECRET; 
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $razorpaySecret);
        
        if (!hash_equals($expectedSignature, $signature)) {
            throw new Exception('Payment signature verification failed');
        }
    }

    // Verify payment with Razorpay API (additional verification)
    $razorpayKeyId = RAZORPAY_KEY_ID;
    $razorpaySecret = RAZORPAY_KEY_SECRET;
    
    // Modern approach using file_get_contents with stream context
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("{$razorpayKeyId}:{$razorpaySecret}")
            ],
            'ignore_errors' => true,
            'timeout' => 30
        ],
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true
        ]
    ]);
    
    $response = @file_get_contents("https://api.razorpay.com/v1/payments/{$paymentId}", false, $context);
    
    // Check if request was successful
    if ($response === false) {
        throw new Exception('Failed to connect to payment gateway for verification');
    }
    
    // Parse HTTP response code from headers
    $httpCode = 200; // Default
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                $httpCode = (int)$matches[1];
                break;
            }
        }
    }
    
    $paymentDetails = json_decode($response, true);
    
    // Log the response for debugging
    error_log("Payment Verification Response: " . json_encode([
        'payment_id' => $paymentId,
        'http_code' => $httpCode,
        'response' => $paymentDetails
    ]));
    
    if ($httpCode !== 200) {
        $errorMsg = isset($paymentDetails['error']['description']) 
            ? $paymentDetails['error']['description'] 
            : 'Payment verification failed with HTTP code: ' . $httpCode;
        throw new Exception($errorMsg);
    }
    
    if (!isset($paymentDetails['status'])) {
        throw new Exception('Invalid payment response from gateway');
    }

    // Check payment status
    $validStatuses = ['captured', 'authorized'];
    if (!in_array($paymentDetails['status'], $validStatuses)) {
        throw new Exception('Payment not successful. Status: ' . $paymentDetails['status']);
    }
    
    // Verify amount matches (convert from paise to rupees)
    $expectedAmount = REGISTRATION_FEE; 
    $paidAmount = $paymentDetails['amount'] / 100;
    
    if ($paidAmount < $expectedAmount) {
        throw new Exception('Payment amount mismatch. Expected: ' . $expectedAmount . ', Received: ' . $paidAmount);
    }

    // Start transaction
    $pdo->beginTransaction();

    try {
        // Generate unique registration ID
        $registrationId = generateRegistrationId();

        // Get registration data from session
        $data = $_SESSION['registration_data'];

        // Insert registration record
        $sql = "INSERT INTO registrations (
            registration_id, first_name, last_name, email, phone, dob, gender, 
            address, qualification, percentage, college, year_of_passing, 
            program, experience, motivation, updates_opt_in, payment_mode, created_at
        ) VALUES (
            :registration_id, :first_name, :last_name, :email, :phone, :dob, :gender,
            :address, :qualification, :percentage, :college, :year_of_passing,
            :program, :experience, :motivation, :updates_opt_in, :payment_mode, NOW()
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':registration_id' => $registrationId,
            ':first_name' => $data['firstName'],
            ':last_name' => $data['lastName'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':dob' => $data['dob'],
            ':gender' => $data['gender'],
            ':address' => $data['address'],
            ':qualification' => $data['qualification'],
            ':percentage' => $data['percentage'],
            ':college' => $data['college'],
            ':year_of_passing' => $data['yearOfPassing'],
            ':program' => $data['program'],
            ':experience' => $data['experience'],
            ':motivation' => $data['motivation'],
            ':updates_opt_in' => $data['updates'],
            ':payment_mode' => 'ONLINE'
        ]);

        // Insert payment record
        $amount = $paymentDetails['amount'] / 100; // Convert from paise to rupees
        $currency = $paymentDetails['currency'];

        $paymentSql = "INSERT INTO payments (
            registration_id, payment_gateway_id, amount, currency, status, created_at
        ) VALUES (
            :registration_id, :payment_gateway_id, :amount, :currency, :status, NOW()
        )";

        $paymentStmt = $pdo->prepare($paymentSql);
        $paymentStmt->execute([
            ':registration_id' => $registrationId,
            ':payment_gateway_id' => $paymentId,
            ':amount' => $amount,
            ':currency' => $currency,
            ':status' => 'SUCCESS'
        ]);

        // Commit transaction
        $pdo->commit();

        // Send confirmation email
        $emailData = array_merge($data, [
            'registration_id' => $registrationId,
            'payment_id' => $paymentId
        ]);
        
        sendConfirmationEmail($emailData, $registrationId, 'PAID');

        // Store registration ID in session for success page
        $_SESSION['completed_registration_id'] = $registrationId;
        $_SESSION['completed_payment_id'] = $paymentId;

        // Clear registration data from session
        unset($_SESSION['registration_data']);
        unset($_SESSION['registration_timestamp']);

        $response2['success'] = true;
        $response2['registration_id'] = $registrationId;
        $response2['message'] = 'Payment verified and registration completed successfully';

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    $response2['message'] = $e->getMessage();
    error_log("Payment Verification Error: " . $e->getMessage());
}

echo json_encode($response2);