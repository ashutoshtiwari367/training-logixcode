<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

use Dompdf\Dompdf;
use Dompdf\Options;


requireStudentLogin();

$type = $_GET['type'] ?? '';

if ($type === 'id_card') {
    // ID Card is generated dynamically using includes/generate_id_card.php if it doesn't exist
    // Actually, to make it simple and secure, we'll just re-generate it on the fly and stream it.
    require_once __DIR__ . '/../includes/generate_id_card.php';
    
    try {
        $filePath = generateStudentIdCard($pdo, $_SESSION['student_registration_id']);
        
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Student_ID_Card.pdf"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            while (ob_get_level()) {
                ob_end_clean();
            }
            readfile($filePath);
            exit;
        } else {
            die("Error: Could not generate ID card.");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

} elseif ($type === 'reg_letter') {
    // Generate Registration Letter using Dompdf inline
    require_once __DIR__ . '/../vendor/autoload.php';

    $stmt = $pdo->prepare("
        SELECT r.*, a.student_photo, a.aadhar_number, a.total_fees, a.admission_id, a.father_name, a.father_phone
        FROM registrations r 
        LEFT JOIN admissions a ON r.registration_id = a.registration_id 
        WHERE r.registration_id = ?
    ");
    $stmt->execute([$_SESSION['student_registration_id']]);
    $reg = $stmt->fetch();

    if (!$reg) {
        die("Error: Registration record not found.");
    }

    // Convert Logo to Base64
    $logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
    $logoData = @file_get_contents($logoUrl);
    $logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

    // Convert Stamp to Base64 for DOMPDF
    $stampPath = __DIR__ . '/../uploads/stamp.jpg';
    $stampBase64 = '';
    if (file_exists($stampPath)) {
        $stampBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($stampPath));
    }

    // Convert Student Photo to Base64
    $studentPhotoBase64 = '';
    if (!empty($reg['student_photo'])) {
        $photoPath = __DIR__ . '/../uploads/photos/' . $reg['student_photo'];
        if (file_exists($photoPath)) {
            $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
            $typeImg = ($ext === 'png') ? 'png' : 'jpeg';
            $studentPhotoBase64 = 'data:image/' . $typeImg . ';base64,' . base64_encode(file_get_contents($photoPath));
        }
    }

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Registration Letter - ' . htmlspecialchars($reg['registration_id']) . '</title>
        <style>
            /* DejaVu Sans is used for better Unicode (Rupee) support in Dompdf */
            body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 5px; }
            .header { text-align: center; border-bottom: 2px solid #0d9488; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
            .logo { width: 65px; position: absolute; left: 0; top: 0; }
            .student-photo-box { width: 90px; height: 110px; border: 1px solid #cbd5e1; position: absolute; right: 0; top: 0; background-color: #f8fafc; }
            .student-photo-box img { width: 100%; height: 100%; object-fit: cover; }
            .institute-title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0; }
            .institute-sub { font-size: 10px; color: #64748b; margin: 3px 0 0 0; }
            .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 15px; background: #0f172a; color: #fff; padding: 5px; border-radius: 4px; }
            .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
            .info-table th { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 6px; text-align: left; font-size: 10px; color: #475569; width: 30%; }
            .info-table td { border: 1px solid #cbd5e1; padding: 6px; font-size: 11px; color: #1e293b; width: 70%; font-weight: bold; }
            .section-title { font-size: 12px; font-weight: bold; color: #0d9488; border-bottom: 1px solid #0d9488; padding-bottom: 2px; margin: 12px 0 6px 0; }
            .amount-val { font-size: 14px; color: #0f172a; }
            .footer { margin-top: 30px; font-size: 9px; color: #64748b; text-align: center; border-top: 1px solid #cbd5e1; padding-top: 8px; }
            .signature { margin-top: 50px; width: 100%; }
            .sig-box { width: 170px; border-top: 1px solid #333; text-align: center; font-size: 10px; padding-top: 4px; float: right; }
            .stamp-container { height: 110px; text-align: center; margin-bottom: 5px; }
            .stamp-img { width: 110px; height: 110px; object-fit: contain; display: inline-block; }
        </style>
    </head>
    <body>
        <div class="header">
            ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="logo">' : '') . '
            <div class="student-photo-box">
                ' . ($studentPhotoBase64 ? '<img src="' . $studentPhotoBase64 . '">' : '<div style="text-align:center;line-height:110px;color:#cbd5e1;font-size:9px;">PHOTO</div>') . '
            </div>
            <h1 class="institute-title">LogixCode IT Solution</h1>
            <p class="institute-sub">Advanced Training & Development Center<br>2/1 HIG Swarn Jayanti Vihar, Koyla Nagar, Kanpur</p>
        </div>
        
        <div class="title">OFFICIAL REGISTRATION LETTER</div>
        
        <div class="section-title">1. Enrollment Details</div>
        <table class="info-table">
            <tr><th>Registration ID</th><td style="color:#2563eb;">' . htmlspecialchars($reg['registration_id']) . '</td></tr>
            ' . ($reg['admission_id'] ? '<tr><th>Admission ID</th><td style="color:#0d9488;">' . htmlspecialchars($reg['admission_id']) . '</td></tr>' : '') . '
            <tr><th>Course Name</th><td style="color:#0d9488; font-size:12px;">' . htmlspecialchars(strtoupper($reg['program'])) . '</td></tr>
        </table>

        <div class="section-title">2. Personal Information</div>
        <table class="info-table">
            <tr><th>Student Full Name</th><td>' . htmlspecialchars(strtoupper($reg['first_name'] . ' ' . $reg['last_name'])) . '</td></tr>
            <tr><th>Father\'s Name</th><td>' . htmlspecialchars(strtoupper($reg['father_name'] ?? 'Not Updated')) . '</td></tr>
            <tr><th>Aadhar Number</th><td>' . htmlspecialchars($reg['aadhar_number'] ?? 'Not Updated') . '</td></tr>
            <tr><th>Date of Birth</th><td>' . ($reg['dob'] ? date('d-M-Y', strtotime($reg['dob'])) : '-') . '</td></tr>
            <tr><th>Gender</th><td>' . htmlspecialchars(ucfirst($reg['gender'])) . '</td></tr>
        </table>

        <div class="section-title">3. Contact Details</div>
        <table class="info-table">
            <tr><th>Student Mobile</th><td>' . htmlspecialchars($reg['phone']) . '</td></tr>
            <tr><th>Father\'s Mobile</th><td>' . htmlspecialchars($reg['father_phone'] ?? 'Not Updated') . '</td></tr>
            <tr><th>Email Address</th><td>' . htmlspecialchars($reg['email'] ?? '-') . '</td></tr>
        </table>

        <div class="section-title">4. Fee Structure</div>
        <table class="info-table">
            <tr><th>Total Course Fees</th><td class="amount-val">&#8377; ' . number_format($reg['total_fees'] ?? 0, 2) . '</td></tr>
        </table>

        <div class="section-title">5. Educational Details</div>
        <table class="info-table">
            <tr><th>Qualification</th><td>' . htmlspecialchars($reg['qualification'] ?? '-') . '</td></tr>
            <tr><th>College / University</th><td>' . htmlspecialchars($reg['college'] ?? '-') . '</td></tr>
        </table>

        <div style="margin-top:15px; font-size:10px; color:#64748b;">
            <p><strong>Declaration:</strong> I hereby declare that all the information provided above is true to the best of my knowledge. I agree to abide by the rules and regulations of LogixCode IT Solution during my training period.</p>
        </div>

        <div class="signature">
            <div style="float: right; width: 170px; text-align: center;">
                <div class="stamp-container">
                    ' . ($stampBase64 ? '<img src="' . $stampBase64 . '" class="stamp-img">' : '') . '
                </div>
                <div class="sig-box" style="float: none; width: 100%; border-top: 1px solid #333; padding-top: 4px; margin-top: 0;">
                    Authorized Signatory<br><span style="font-size:9px; color:#64748b;">(LogixCode IT Solution)</span>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>

        <div class="footer">
            Generated on ' . date('d M Y, h:i A') . ' | This is a computer generated document.
            <br>www.logixcode.com | +91-8467898854
        </div>
    </body>
    </html>';

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans'); // Required for Rupee symbol
    $dompdf = new Dompdf($options);
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $filename = 'Registration_Letter_' . $reg['registration_id'] . '.pdf';

    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    echo $dompdf->output();
    exit;

} else {
    die("Invalid document type requested.");
}
