<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$admissionId = $_GET['id'] ?? '';
if (!$admissionId) {
    die("Error: No Admission ID provided.");
}

// Fetch admission data directly — no reliance on registrations/student_id
$stmt = $pdo->prepare("SELECT * FROM admissions WHERE admission_id = ?");
$stmt->execute([$admissionId]);
$adm = $stmt->fetch();

if (!$adm) {
    die("Error: Admission record not found.");
}

// ── Logo ─────────────────────────────────────────────────────────────
$logoUrl  = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
$logoData = @file_get_contents($logoUrl);
$logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

// ── Student Photo ─────────────────────────────────────────────────────
$photoBase64 = '';
if (!empty($adm['student_photo'])) {
    $photoPath = __DIR__ . '/../uploads/photos/' . $adm['student_photo'];
    if (file_exists($photoPath)) {
        $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg'])) {
            $photoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($photoPath));
        } elseif ($ext === 'png') {
            $photoBase64 = 'data:image/png;base64,'  . base64_encode(file_get_contents($photoPath));
        }
    }
}

// ── Display values ────────────────────────────────────────────────────
$studentName = strtoupper(trim($adm['student_name']));
$course      = strtoupper(trim($adm['course_name']));
$cardId      = $adm['admission_id'];
$phone       = $adm['phone'] ?? '-';
$email       = $adm['email'] ?? '-';
$validYear   = date('Y') . '-' . (date('Y') + 1);

// ── HTML (same design as generate_id_card.php) ─────────────────────────
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: "Helvetica", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
        }
        .card {
            width: 250pt;
            height: 400pt;
            background-color: #000;
            position: relative;
            overflow: hidden;
        }
        /* White top curve */
        .top-bg {
            position: absolute;
            top: -160pt;
            left: -25pt;
            width: 300pt;
            height: 310pt;
            background-color: #fff;
            border-radius: 50%;
            z-index: 1;
        }
        .header-content {
            position: absolute;
            top: 20pt;
            width: 100%;
            text-align: center;
            z-index: 2;
        }
        .logo {
            width: 35pt;
            margin-bottom: 2pt;
        }
        .brand {
            font-size: 20pt;
            font-weight: bold;
            color: #03c4ce;
            letter-spacing: 1pt;
            margin: 0;
        }
        /* Circular photo */
        .photo-frame {
            position: absolute;
            top: 90pt;
            left: 75pt;
            width: 100pt;
            height: 100pt;
            border: 3pt solid #2c3e50;
            border-radius: 50%;
            z-index: 3;
            background-color: #fff;
            overflow: hidden;
        }
        .photo-frame img {
            width: 100pt;
            height: 100pt;
            border-radius: 50%;
        }
        .photo-placeholder {
            width: 100pt;
            height: 100pt;
            background-color: #f1f5f9;
            text-align: center;
            padding-top: 38pt;
            color: #94a3b8;
            font-size: 10pt;
            font-weight: bold;
            box-sizing: border-box;
        }
        /* Info section */
        .info-container {
            position: absolute;
            top: 210pt;
            width: 100%;
            text-align: center;
            color: #fff;
            z-index: 4;
        }
        .student-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2pt;
            text-transform: uppercase;
            color: #fff;
        }
        .role-title {
            font-size: 9pt;
            color: #fff;
            letter-spacing: 1.5pt;
            margin-bottom: 12pt;
            opacity: 0.9;
            font-weight: normal;
        }
        .details-table {
            width: 85%;
            margin: 0 auto;
            font-size: 9pt;
            color: #fff;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 4pt 0;
            vertical-align: top;
        }
        .label {
            width: 55pt;
            text-align: left;
            font-weight: normal;
            opacity: 0.7;
            padding-left: 12pt !important;
        }
        .value {
            text-align: left;
            font-weight: bold;
            padding-left: 5pt !important;
        }
        /* Bottom white curve */
        .bottom-curve-left {
            position: absolute;
            bottom: -50pt;
            left: -60pt;
            width: 160pt;
            height: 120pt;
            background-color: #fff;
            border-radius: 50%;
            z-index: 1;
        }
        /* Footer */
        .footer {
            position: absolute;
            bottom: 10pt;
            right: 15pt;
            color: #fff;
            font-size: 8pt;
            z-index: 5;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="top-bg"></div>
        <div class="bottom-curve-left"></div>

        <div class="header-content">
            ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="logo">' : '') . '
            <div class="brand">LOGIXCODE</div>
        </div>

        <div class="photo-frame">
            ' . ($photoBase64
                ? '<img src="' . $photoBase64 . '">'
                : '<div class="photo-placeholder">PHOTO</div>') . '
        </div>

        <div class="info-container">
            <div class="student-name">' . htmlspecialchars($studentName) . '</div>
            <div class="role-title">' . htmlspecialchars($course) . '</div>

            <table class="details-table">
                <tr>
                    <td class="label">ID No</td>
                    <td class="value">' . htmlspecialchars($cardId) . '</td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td class="value">' . htmlspecialchars($phone) . '</td>
                </tr>
                <tr>
                    <td class="label">Valid</td>
                    <td class="value">' . htmlspecialchars($validYear) . '</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <strong>www.logixcode.com</strong>
        </div>
    </div>
</body>
</html>';

// ── Render PDF ────────────────────────────────────────────────────────
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 250, 400], 'portrait');
$dompdf->render();

$filename = 'ID_Card_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $adm['student_name']) . '.pdf';

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $dompdf->output();
exit;
