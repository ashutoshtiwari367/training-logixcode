<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate Student ID Card PDF
 * 
 * @param PDO $pdo Database connection
 * @param string $registrationId The registration ID
 * @return string Path to the generated PDF
 */
function generateStudentIdCard($pdo, $registrationId) {
    // Fetch student data from registrations
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE registration_id = ?");
    $stmt->execute([$registrationId]);
    $student = $stmt->fetch();

    if (!$student) {
        throw new Exception("Student registration record not found.");
    }

    // Check if there is a photo and admission ID in the admissions table for this registration
    $stmt = $pdo->prepare("SELECT admission_id, student_photo FROM admissions WHERE registration_id = ? LIMIT 1");
    $stmt->execute([$registrationId]);
    $admission = $stmt->fetch();

    // Determine the ID to display on the card (Student ID -> Admission ID -> Registration ID)
    $displayId = !empty($student['student_id']) ? $student['student_id'] : ($admission && !empty($admission['admission_id']) ? $admission['admission_id'] : $student['registration_id']);
    
    $photoBase64 = '';
    if ($admission && !empty($admission['student_photo'])) {
        $photoPath = __DIR__ . '/../uploads/photos/' . $admission['student_photo'];
        if (file_exists($photoPath)) {
            $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $type = ($ext === 'png') ? 'png' : 'jpeg';
                $photoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($photoPath));
            }
        }
    }

    // Convert Logo to Base64
    $logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
    $logoData = @file_get_contents($logoUrl);
    $logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

    // HTML Template for ID Card
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
            /* White Top Section with Curve */
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
            /* Photo Area - Circular and Small */
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
                padding-top: 40pt;
                color: #94a3b8;
                font-size: 10pt;
                font-weight: bold;
            }
            /* Info Area */
            .info-container {
                position: absolute;
                top: 210pt;
                width: 100%;
                text-align: center;
                color: #fff;
                z-index: 4;
            }
            .student-name {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 2pt;
                text-transform: uppercase;
                color: #fff;
            }
            .role-title {
                font-size: 11pt;
                color: #fff;
                letter-spacing: 2pt;
                margin-bottom: 12pt;
                opacity: 0.9;
                font-weight: normal;
            }
            .details-table {
                width: 85%;
                margin: 0 auto;
                font-size: 10pt;
                color: #fff;
                border-collapse: collapse;
            }
            .details-table td {
                padding: 5pt 0;
                vertical-align: top;
            }
            .label {
                width: 60pt;
                text-align: left;
                font-weight: normal;
                opacity: 0.7;
                padding-left: 15pt !important;
            }
            .value {
                text-align: left;
                font-weight: bold;
                padding-left: 5pt !important;
            }
            /* Bottom White Curve */
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
            /* Website Footer */
            .footer {
                position: absolute;
                bottom: 10pt;
                right: 15pt;
                color: #fff;
                font-size: 9pt;
                z-index: 5;
                text-align: right;
            }
            .globe-icon {
                width: 10pt;
                vertical-align: middle;
                margin-right: 3pt;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <!-- Background Shapes -->
            <div class="top-bg"></div>
            <div class="bottom-curve-left"></div>
            
            <div class="header-content">
                ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="logo">' : '') . '
                <div class="brand">LOGIXCODE</div>
            </div>

            <div class="photo-frame">
                ' . ($photoBase64 ? '<img src="' . $photoBase64 . '">' : '<div class="photo-placeholder">PHOTO</div>') . '
            </div>

            <div class="info-container">
                <div class="student-name">' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</div>
                <div class="role-title">' . htmlspecialchars($student['program']) . '</div>
                
                <table class="details-table">
                    <tr>
                        <td class="label">ID No</td>
                        <td class="value">' . htmlspecialchars($displayId) . '</td>
                    </tr>
                    <tr>
                        <td class="label">E-mail</td>
                        <td class="value">' . htmlspecialchars($student['email']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td class="value">' . htmlspecialchars($student['phone']) . '</td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+PGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTAiPjwvY2lyY2xlPjxsaW5lIHgxPSIyIiB5MT0iMTIiIHgyPSIyMiIgeTI9IjEyIj48L2xpbmU+PHBhdGggZD0iTTEyIDJhMTUuMyAxNS4zIDAgMCAxIDQgMTBBMTUuMyAxNS4zIDAgMCAxIDEyIDIyYTE1LjMgMTUuMyAwIDAgMS00LTEwQTE1LjMgMTUuMyAwIDAgMSAxMiAyWiI+PC9wYXRoPjwvc3ZnPg==" class="globe-icon">
                <strong>www.logixcode.com</strong>
            </div>
        </div>
    </body>
    </html>';

    // Initialize DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    
    $dompdf->loadHtml($html);
    // Exact dimensions for the card as defined in HTML
    $dompdf->setPaper(array(0, 0, 250, 400), 'portrait');
    $dompdf->render();
    
    // Save PDF to a file
    $outputDir = __DIR__ . '/../uploads/id_cards';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    
    $filePath = $outputDir . '/' . $displayId . '_ID.pdf';
    file_put_contents($filePath, $dompdf->output());
    
    return $filePath;
}
