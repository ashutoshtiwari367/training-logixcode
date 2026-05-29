<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/includes/generate_id_card.php';

// Fetch the most recent registration to test with
$stmt = $pdo->query("SELECT * FROM registrations ORDER BY created_at DESC LIMIT 1");
$student = $stmt->fetch();

if (!$student) {
    die("No registration record found to test.\n");
}

echo "Testing with Student: " . $student['first_name'] . " " . $student['last_name'] . " (" . $student['registration_id'] . ")\n";

// 1. Temporarily set student_id if not present for the test
$testStudentId = $student['student_id'] ?: 'STU-TEST-' . rand(1000, 9999);
if (empty($student['student_id'])) {
    echo "Setting temporary student_id: $testStudentId...\n";
    $pdo->prepare("UPDATE registrations SET student_id = ? WHERE registration_id = ?")->execute([$testStudentId, $student['registration_id']]);
    $student['student_id'] = $testStudentId;
}

// 2. Generate ID Card
echo "Generating ID Card...\n";
$idCardPath = null;
try {
    $idCardPath = generateStudentIdCard($pdo, $student['registration_id']);
    echo "ID Card generated successfully at: $idCardPath\n";
} catch (Exception $e) {
    echo "ID Card generation FAILED: " . $e->getMessage() . "\n";
}

// 3. Send Credential Email with verbose debugging enabled
echo "Sending email to " . $student['email'] . "...\n";
$emailData = [
    'firstName'    => $student['first_name'],
    'lastName'     => $student['last_name'],
    'email'        => $student['email'],
    'student_id'   => $student['student_id'],
    'raw_password' => 'TestPass123'
];

// Let's create a custom mailer instance here to capture verbose SMTP logs
$mail = new PHPMailer\PHPMailer\PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@logixcode.com';
    $mail->Password   = 'Mu$k@n1106';
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPDebug  = 3; // Verbose output
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->setFrom('info@logixcode.com', 'Logixcode IT Solution');
    $mail->addAddress($student['email']);
    $mail->isHTML(true);
    $mail->Subject = '🔐 Test SMTP Connection';
    $mail->Body    = 'Test body';
    if ($idCardPath && file_exists($idCardPath)) {
        $mail->addAttachment($idCardPath, 'Student_ID_Card.pdf');
    }
    $mailSent = $mail->send();
} catch (Exception $e) {
    $mailSent = false;
    echo "SMTP ERROR: " . $e->getMessage() . "\n";
}

if ($mailSent) {
    echo "SUCCESS: Credential email sent successfully!\n";
} else {
    echo "FAILED: Credential email could not be sent.\n";
    // Check log file
    $logFile = 'C:\\xampp\\php\\logs\\php_error_log';
    if (file_exists($logFile)) {
        echo "\n--- php_error_log content ---\n";
        echo file_get_contents($logFile);
    } else {
        echo "No log file found.\n";
    }
}

// Cleanup: Reset student_id to original if it was empty
if ($testStudentId !== $student['student_id']) {
    $pdo->prepare("UPDATE registrations SET student_id = NULL WHERE registration_id = ?")->execute([$student['registration_id']]);
}
?>
