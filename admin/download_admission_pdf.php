<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? '';
if (!$id) {
    die("Error: No Admission ID provided.");
}

$stmt = $pdo->prepare("SELECT * FROM admissions WHERE admission_id = ?");
$stmt->execute([$id]);
$adm = $stmt->fetch();

if (!$adm) {
    die("Error: Admission record not found.");
}

// Convert Logo to Base64 for DOMPDF (fetch PNG version from Cloudinary to avoid WebP issue)
$logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
$logoData = @file_get_contents($logoUrl);
$logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

// Convert Student Photo to Base64 (JPG/PNG only — no GD required)
$photoBase64 = '';
if (!empty($adm['student_photo'])) {
    $photoPath = __DIR__ . '/../uploads/photos/' . $adm['student_photo'];
    if (file_exists($photoPath)) {
        $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
        // WebP not supported by DomPDF without GD — skip it
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $photoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($photoPath));
        } elseif ($ext === 'png') {
            $photoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($photoPath));
        }
    }
}

// Generate HTML Content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Form - ' . htmlspecialchars($adm['admission_id']) . '</title>
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0d9488; padding-bottom: 20px; margin-bottom: 30px; position: relative; }
        .logo { width: 80px; position: absolute; left: 0; top: 0; }
        .institute-title { font-size: 24px; font-weight: bold; color: #0f172a; margin: 0; }
        .institute-sub { font-size: 12px; color: #64748b; margin: 5px 0 0 0; }
        
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; background: #0284c7; color: #fff; padding: 5px; border-radius: 4px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 11px; color: #475569; width: 30%; }
        .info-table td { border: 1px solid #cbd5e1; padding: 8px; font-size: 12px; color: #1e293b; width: 70%; font-weight: bold; }
        
        .photo-box { width: 100px; height: 120px; border: 1px solid #cbd5e1; position: absolute; right: 20px; top: 120px; background: #f8fafc; text-align: center; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .photo-box span { display: inline-block; margin-top: 50px; color: #94a3b8; font-size: 10px; }

        .section-title { font-size: 14px; font-weight: bold; color: #0d9488; border-bottom: 1px solid #0d9488; padding-bottom: 3px; margin: 20px 0 10px 0; }
        
        .fee-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .fee-table th { background: #0d9488; color: #fff; padding: 10px; text-align: center; border: 1px solid #0f766e; }
        .fee-table td { padding: 15px 10px; text-align: center; border: 1px solid #cbd5e1; font-size: 16px; font-weight: bold; }
        
        .footer { margin-top: 50px; font-size: 11px; color: #64748b; text-align: center; border-top: 1px solid #cbd5e1; padding-top: 10px; }
        .signature { margin-top: 80px; width: 100%; }
        .sig-box { width: 200px; border-top: 1px solid #333; text-align: center; font-size: 12px; padding-top: 5px; }
        .sig-left { float: left; }
        .sig-right { float: right; }
    </style>
</head>
<body>

    <div class="header">
        ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="logo">' : '') . '
        <h1 class="institute-title">LogixCode Enterprise</h1>
        <p class="institute-sub">Advanced Training & Development Center<br>Kanpur, Uttar Pradesh - 208001</p>
    </div>

    <div class="title">ADMISSION FORM / RECORD</div>

    <!-- Photo Container -->
    <div class="photo-box">
        ' . ($photoBase64 ? '<img src="' . $photoBase64 . '">' : '<span>No Photo</span>') . '
    </div>

    <div style="width: 75%;">
        <div class="section-title">1. Personal Information</div>
        <table class="info-table">
            <tr><th>Admission ID</th><td style="color:#0284c7;">' . htmlspecialchars($adm['admission_id']) . '</td></tr>
            <tr><th>Student Name</th><td>' . htmlspecialchars(strtoupper($adm['student_name'])) . '</td></tr>
            <tr><th>Father\'s Name</th><td>' . htmlspecialchars(strtoupper($adm['father_name'] ?? '-')) . '</td></tr>
            <tr><th>Date of Birth</th><td>' . ($adm['dob'] ? date('d-M-Y', strtotime($adm['dob'])) : '-') . '</td></tr>
            <tr><th>Gender</th><td>' . htmlspecialchars($adm['gender'] ?? '-') . '</td></tr>
            <tr><th>Aadhar Number</th><td>' . htmlspecialchars($adm['aadhar_number'] ?? '-') . '</td></tr>
            <tr><th>Medical Condition</th><td>' . htmlspecialchars($adm['medical_condition'] ?: 'None') . '</td></tr>
        </table>
    </div>

    <div class="section-title">2. Contact Details</div>
    <table class="info-table">
        <tr><th>Student Mobile</th><td>' . htmlspecialchars($adm['phone']) . '</td></tr>
        <tr><th>Email Address</th><td>' . htmlspecialchars($adm['email'] ?? '-') . '</td></tr>
        <tr><th>Father\'s Mobile</th><td>' . htmlspecialchars($adm['father_phone'] ?? '-') . '</td></tr>
        <tr><th>Local Address</th><td>' . htmlspecialchars($adm['local_address'] ?? '-') . '</td></tr>
        <tr><th>Permanent Address</th><td>' . htmlspecialchars($adm['permanent_address'] ?? '-') . '</td></tr>
    </table>

    <div class="section-title">3. Academic & Course Details</div>
    <table class="info-table">
        <tr><th>Enrolled Course</th><td style="color:#0d9488;">' . htmlspecialchars(strtoupper($adm['course_name'])) . '</td></tr>
        <tr><th>College / University</th><td>' . htmlspecialchars(strtoupper($adm['college_name'] ?? '-')) . '</td></tr>
        <tr><th>Degree & Branch</th><td>' . htmlspecialchars($adm['degree'] ?? '-') . ' (' . htmlspecialchars($adm['branch'] ?? '-') . ')</td></tr>
        <tr><th>Current Semester</th><td>' . htmlspecialchars($adm['current_semester'] ?? '-') . '</td></tr>
        <tr><th>Hostel Required?</th><td>' . htmlspecialchars($adm['hostel_required']) . '</td></tr>
        <tr><th>Laptop Required?</th><td>' . htmlspecialchars($adm['laptop_required']) . '</td></tr>
    </table>

    <div class="section-title">4. Fee Declaration</div>
    <table class="fee-table">
        <tr>
            <th>TOTAL COURSE FEES</th>
        </tr>
        <tr>
            <td>₹' . number_format($adm['total_fees'], 2) . '</td>
        </tr>
    </table>
    <p style="font-size:10px; color:#64748b; text-align:center; margin-top:5px;">
        * Note: This document reflects the total finalized fees for the enrolled program.
    </p>

    <div class="signature">
        <div class="sig-box sig-left">Student\'s Signature</div>
        <div class="sig-box sig-right">Authorized Signatory<br><span style="font-size:9px; color:#64748b;">(LogixCode Admin)</span></div>
        <div style="clear:both;"></div>
    </div>

    <div class="footer">
        Generated on ' . date('d M Y, h:i A') . ' | System Generated Document
    </div>

</body>
</html>';

// Initialize DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // allow remote images if any
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF to Browser
$filename = 'Admission_' . $adm['admission_id'] . '.pdf';

// Clear buffers
while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $dompdf->output();
exit;
