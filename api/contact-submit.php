<?php
/**
 * Contact & Inquiry Form API Handler
 * api/contact-submit.php
 */

header('Content-Type: application/json; charset=UTF-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get input from POST or JSON body
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true);

$name    = trim($_POST['name'] ?? $jsonData['name'] ?? '');
$phone   = trim($_POST['phone'] ?? $jsonData['phone'] ?? '');
$email   = trim($_POST['email'] ?? $jsonData['email'] ?? '');
$course  = trim($_POST['course'] ?? $jsonData['course'] ?? 'General Query');
$message = trim($_POST['message'] ?? $jsonData['message'] ?? '');

// Validation
if (empty($name) || empty($phone) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please fill in all required fields (Name, Phone, Email, Message).']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

// Sanitize inputs
$safeName    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safePhone   = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safeCourse  = htmlspecialchars($course, ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$ipAddress   = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

// Dispatch Email Notification
$emailSent = false;
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom(SMTP_FROM_EMAIL, 'LogixCode Inquiries');
    $mail->addAddress(SMTP_FROM_EMAIL, 'Logixcode Team');
    if (defined('BCC_EMAIL') && BCC_EMAIL) {
        $mail->addAddress(BCC_EMAIL, BCC_NAME ?? 'Administrator');
    }

    $mail->isHTML(true);
    $mail->Subject = "New Student Inquiry: {$safeName} - {$safeCourse}";
    $mail->Body    = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
            <div style='background-color: #03bfd3; color: #ffffff; padding: 20px; text-align: center;'>
                <h2 style='margin: 0; font-size: 22px;'>New Training Inquiry Received</h2>
            </div>
            <div style='padding: 24px; background-color: #ffffff;'>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; width: 35%; color: #555;'><strong>Full Name:</strong></td><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #111;'>{$safeName}</td></tr>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #555;'><strong>Phone Number:</strong></td><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #111;'><a href='tel:{$safePhone}'>{$safePhone}</a></td></tr>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #555;'><strong>Email:</strong></td><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #111;'><a href='mailto:{$safeEmail}'>{$safeEmail}</a></td></tr>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #555;'><strong>Course Interest:</strong></td><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #111;'>{$safeCourse}</td></tr>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #555;'><strong>IP Address:</strong></td><td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #777;'>{$ipAddress}</td></tr>
                </table>
                <div style='margin-top: 20px;'>
                    <strong style='color: #555;'>Student Message:</strong>
                    <div style='background-color: #f7f9fb; border-left: 4px solid #03bfd3; padding: 12px 16px; margin-top: 8px; color: #333;'>
                        {$safeMessage}
                    </div>
                </div>
            </div>
            <div style='background-color: #f2f4f6; color: #777; padding: 12px; font-size: 12px; text-align: center;'>
                LogixCode Training Institute • Kanpur, Uttar Pradesh
            </div>
        </div>
    ";
    $mail->AltBody = "New Inquiry:\nName: {$name}\nPhone: {$phone}\nEmail: {$email}\nCourse: {$course}\nMessage:\n{$message}";

    $mail->send();
    $emailSent = true;
} catch (Exception $mailEx) {
    error_log("Enquiry email sending failed: " . $mail->ErrorInfo);
}

// Return response
echo json_encode([
    'success' => true,
    'message' => 'Thank you! Your message has been received. Our counselor will contact you shortly.'
]);
