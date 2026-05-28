<?php
/**
 * Admin: Download Student ID Card PDF
 * admin/download_idcard_pdf.php
 *
 * Uses the SAME generateStudentIdCard() function as the student portal
 * so both downloads produce an identical PDF.
 *
 * Accepts:
 *   ?id=ADMISSION_ID   — looks up the linked registration_id from admissions
 *   ?reg_id=REG_ID     — uses the registration_id directly (for dashboard table)
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

require_once __DIR__ . '/../includes/generate_id_card.php';

// ── Determine the registration_id to use ───────────────────────────────
$registrationId = '';

if (!empty($_GET['reg_id'])) {
    // Direct registration_id passed (e.g. from dashboard table)
    $registrationId = trim($_GET['reg_id']);
} elseif (!empty($_GET['id'])) {
    // Admission ID passed — look up the linked registration_id
    $admissionId = trim($_GET['id']);
    $stmt = $pdo->prepare("SELECT registration_id FROM admissions WHERE admission_id = ? LIMIT 1");
    $stmt->execute([$admissionId]);
    $adm = $stmt->fetch();

    if (!$adm || empty($adm['registration_id'])) {
        // Fallback: try to generate using admission data directly
        // Fetch admission record and build a registration-compatible row
        $stmt2 = $pdo->prepare("SELECT * FROM admissions WHERE admission_id = ? LIMIT 1");
        $stmt2->execute([$admissionId]);
        $admData = $stmt2->fetch();

        if (!$admData) {
            die("Error: Admission record not found for ID: " . htmlspecialchars($admissionId));
        }

        // Check if there is a registration linked by phone/email as a last resort
        $stmt3 = $pdo->prepare("SELECT registration_id FROM registrations WHERE phone = ? OR email = ? LIMIT 1");
        $stmt3->execute([$admData['phone'], $admData['email']]);
        $linked = $stmt3->fetch();
        if ($linked) {
            $registrationId = $linked['registration_id'];
        } else {
            die("Error: No student registration linked to admission ID: " . htmlspecialchars($admissionId) . ". Please ensure the student has been assigned credentials before downloading the ID card.");
        }
    } else {
        $registrationId = $adm['registration_id'];
    }
}

if (empty($registrationId)) {
    die("Error: No Admission ID or Registration ID provided.");
}

// ── Generate the ID Card using the shared function ──────────────────────
try {
    $filePath = generateStudentIdCard($pdo, $registrationId);
} catch (Exception $e) {
    die("Error generating ID Card: " . htmlspecialchars($e->getMessage()) . ". Make sure the student has a Student ID assigned.");
}

if (!file_exists($filePath)) {
    die("Error: ID Card file could not be created.");
}

// ── Stream the PDF to the browser ──────────────────────────────────────
// Get student name for filename
$stmt = $pdo->prepare("SELECT first_name, last_name, student_id FROM registrations WHERE registration_id = ?");
$stmt->execute([$registrationId]);
$stu = $stmt->fetch();
$safeName = $stu ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $stu['student_id'] . '_' . $stu['first_name'] . '_' . $stu['last_name']) : 'Student_ID_Card';
$filename  = 'ID_Card_' . $safeName . '.pdf';

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
