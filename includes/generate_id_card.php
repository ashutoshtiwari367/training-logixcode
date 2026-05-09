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
    // Fetch student data
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE registration_id = ?");
    $stmt->execute([$registrationId]);
    $student = $stmt->fetch();

    if (!$student || empty($student['student_id'])) {
        throw new Exception("Student not found or Student ID not generated.");
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
            body { font-family: "Helvetica", sans-serif; margin: 0; padding: 0; }
            .id-card-container {
                width: 250px;
                height: 400px;
                border: 2px solid #0d9488;
                border-radius: 10px;
                margin: 20px auto;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            .header {
                background: #0d9488;
                color: white;
                padding: 10px 0;
            }
            .header img { width: 40px; margin-bottom: 5px; }
            .header h3 { margin: 0; font-size: 14px; text-transform: uppercase; }
            .header p { margin: 0; font-size: 8px; }
            .photo {
                width: 100px;
                height: 100px;
                background: #e2e8f0;
                border: 3px solid #0d9488;
                border-radius: 50%;
                margin: 15px auto;
                line-height: 100px;
                color: #94a3b8;
                font-size: 12px;
                overflow: hidden;
            }
            .details { padding: 0 15px; }
            .name { font-size: 18px; font-weight: bold; color: #1e293b; margin: 5px 0; text-transform: uppercase; }
            .role { font-size: 12px; color: #0ea5e9; font-weight: bold; margin-bottom: 10px; }
            .info-row { text-align: left; font-size: 10px; margin-bottom: 4px; color: #334155; }
            .info-row strong { color: #0f172a; display: inline-block; width: 60px; }
            .footer {
                position: absolute;
                bottom: 0;
                width: 100%;
                background: #0f172a;
                color: white;
                font-size: 9px;
                padding: 8px 0;
            }
            .barcode-area { margin-top: 10px; }
            .barcode { letter-spacing: 2px; font-family: monospace; font-size: 11px; background: #f1f5f9; padding: 3px; border-radius: 3px;}
        </style>
    </head>
    <body>
        <div class="id-card-container">
            <div class="header">
                ' . ($logoBase64 ? '<img src="' . $logoBase64 . '">' : '') . '
                <h3>LogixCode Enterprise</h3>
                <p>Training & Development Center</p>
            </div>
            
            <div class="photo">
                STUDENT
            </div>
            
            <div class="details">
                <div class="name">' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</div>
                <div class="role">STUDENT</div>
                
                <div class="info-row">
                    <strong>ID No:</strong> ' . htmlspecialchars($student['student_id']) . '
                </div>
                <div class="info-row">
                    <strong>Program:</strong> ' . htmlspecialchars(substr($student['program'], 0, 20)) . '...
                </div>
                <div class="info-row">
                    <strong>Phone:</strong> ' . htmlspecialchars($student['phone']) . '
                </div>
                
                <div class="barcode-area">
                    <span class="barcode">*' . htmlspecialchars($student['student_id']) . '*</span>
                </div>
            </div>
            
            <div class="footer">
                Valid for Course Duration<br>
                support@institute.com
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
    // Custom paper size for ID card (approx CR80 size: 2.125 x 3.375 inches, but we use a bit larger for readability)
    // 250x400 px is roughly 187x300 pt
    $dompdf->setPaper(array(0, 0, 200, 320), 'portrait');
    $dompdf->render();
    
    // Save PDF to a file
    $outputDir = __DIR__ . '/../uploads/id_cards';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }
    
    $filePath = $outputDir . '/' . $student['student_id'] . '_ID.pdf';
    file_put_contents($filePath, $dompdf->output());
    
    return $filePath;
}
