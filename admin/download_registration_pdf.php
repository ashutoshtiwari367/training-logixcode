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
    die("Error: No Registration ID provided.");
}

$stmt = $pdo->prepare("
    SELECT r.*, p.amount, p.payment_gateway_id, p.status as payment_status 
    FROM registrations r 
    LEFT JOIN payments p ON r.registration_id = p.registration_id 
    WHERE r.registration_id = ?
");
$stmt->execute([$id]);
$reg = $stmt->fetch();

if (!$reg) {
    die("Error: Registration record not found.");
}

// Convert Logo to Base64 for DOMPDF
$logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
$logoData = @file_get_contents($logoUrl);
$logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

// Generate HTML Content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Letter - ' . htmlspecialchars($reg['registration_id']) . '</title>
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

    <div class="title">STUDENT REGISTRATION LETTER</div>

    <div class="section-title">1. Personal Information</div>
    <table class="info-table">
        <tr><th>Registration ID</th><td style="color:#0284c7;">' . htmlspecialchars($reg['registration_id']) . '</td></tr>
        <tr><th>Student Name</th><td>' . htmlspecialchars(strtoupper($reg['first_name'] . ' ' . $reg['last_name'])) . '</td></tr>
        <tr><th>Date of Birth</th><td>' . ($reg['dob'] ? date('d-M-Y', strtotime($reg['dob'])) : '-') . '</td></tr>
        <tr><th>Gender</th><td>' . htmlspecialchars(ucfirst($reg['gender'])) . '</td></tr>
    </table>

    <div class="section-title">2. Contact Details</div>
    <table class="info-table">
        <tr><th>Mobile Number</th><td>' . htmlspecialchars($reg['phone']) . '</td></tr>
        <tr><th>Email Address</th><td>' . htmlspecialchars($reg['email'] ?? '-') . '</td></tr>
        <tr><th>Address</th><td>' . nl2br(htmlspecialchars($reg['address'] ?? '-')) . '</td></tr>
    </table>

    <div class="section-title">3. Academic & Program Details</div>
    <table class="info-table">
        <tr><th>Selected Program</th><td style="color:#0d9488;">' . htmlspecialchars(strtoupper($reg['program'])) . '</td></tr>
        <tr><th>Highest Qualification</th><td>' . htmlspecialchars($reg['qualification'] ?? '-') . '</td></tr>
        <tr><th>Percentage / CGPA</th><td>' . htmlspecialchars($reg['percentage'] ?? '-') . '</td></tr>
        <tr><th>College / University</th><td>' . htmlspecialchars($reg['college'] ?? '-') . '</td></tr>
        <tr><th>Year of Passing</th><td>' . htmlspecialchars($reg['year_of_passing'] ?? '-') . '</td></tr>
    </table>

    <div class="section-title">4. Payment Information</div>
    <table class="fee-table">
        <tr>
            <th>REGISTRATION FEE</th>
            <th>PAYMENT MODE</th>
            <th>STATUS</th>
        </tr>
        <tr>
            <td>₹' . number_format($reg['amount'] ?? 0, 2) . '</td>
            <td>' . htmlspecialchars($reg['payment_mode']) . '</td>
            <td>' . htmlspecialchars($reg['payment_status'] ?? 'PENDING') . '</td>
        </tr>
    </table>
    <p style="font-size:10px; color:#64748b; text-align:center; margin-top:5px;">
        * Note: This document is proof of your registration. Please keep it for future reference.
    </p>

    <div class="signature">
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
$filename = 'Registration_' . $reg['registration_id'] . '.pdf';

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
