<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

$admissionId = $_GET['id'] ?? '';
if (!$admissionId) {
    die("Error: No Admission ID provided.");
}

// Fetch the registration_id linked to this admission
$stmt = $pdo->prepare("SELECT registration_id, student_name FROM admissions WHERE admission_id = ?");
$stmt->execute([$admissionId]);
$adm = $stmt->fetch();

if (!$adm) {
    die("Error: Admission record not found.");
}

if (empty($adm['registration_id'])) {
    die("Error: This admission has no linked registration. ID card cannot be generated.");
}

// Reuse the existing ID card generator from the student portal
require_once __DIR__ . '/../includes/generate_id_card.php';

try {
    $filePath = generateStudentIdCard($pdo, $adm['registration_id']);

    if (file_exists($filePath)) {
        $filename = 'ID_Card_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $adm['student_name']) . '.pdf';

        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die("Error: ID card file could not be generated.");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
