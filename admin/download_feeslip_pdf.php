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
    SELECT r.*, p.amount, p.payment_gateway_id, p.status as payment_status, p.created_at as payment_date
    FROM registrations r 
    LEFT JOIN payments p ON r.registration_id = p.registration_id 
    WHERE r.registration_id = ?
");
$stmt->execute([$id]);
$reg = $stmt->fetch();

if (!$reg) {
    die("Error: Registration record not found.");
}

// Fallback values
$amount = $reg['amount'] ?? 500.00;
$payment_status = $reg['payment_status'] ?? 'PENDING';
$payment_mode = $reg['payment_mode'] ?? 'ONLINE';
$transaction_id = $reg['payment_gateway_id'] ?? 'N/A';
$payment_date = !empty($reg['payment_date']) ? date('d-M-Y h:i A', strtotime($reg['payment_date'])) : date('d-M-Y h:i A', strtotime($reg['created_at']));

/* Amount to Words Helper */
function amountToWords(float $n): string {
    $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
             'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
             'Seventeen','Eighteen','Nineteen'];
    $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
    $num  = (int)$n;
    if ($num === 0) return 'Zero';
    $w = '';
    if ($num >= 100000) { $w .= amountToWords((int)($num/100000)).' Lakh ';    $num %= 100000; }
    if ($num >= 1000)   { $w .= amountToWords((int)($num/1000)).' Thousand ';  $num %= 1000; }
    if ($num >= 100)    { $w .= $ones[(int)($num/100)].' Hundred ';             $num %= 100; }
    if ($num >= 20)     { $w .= $tens[(int)($num/10)].' ';                      $num %= 10; }
    if ($num > 0)       { $w .= $ones[$num].' '; }
    return trim($w);
}

$amountInWords = strtoupper(amountToWords($amount)) . ' RUPEES ONLY';

// Convert Logo to Base64 for DOMPDF
$logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
$logoData = @file_get_contents($logoUrl);
$logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

// Convert Stamp to Base64 for DOMPDF
$stampPath = __DIR__ . '/../uploads/stamp.jpg';
$stampBase64 = '';
if (file_exists($stampPath)) {
    $stampBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($stampPath));
}

// Generate HTML Content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - ' . htmlspecialchars($reg['registration_id']) . '</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 13px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0d9488; padding-bottom: 20px; margin-bottom: 30px; position: relative; }
        .logo { width: 80px; position: absolute; left: 0; top: 0; }
        .institute-title { font-size: 24px; font-weight: bold; color: #0f172a; margin: 0; }
        .institute-sub { font-size: 12px; color: #64748b; margin: 5px 0 0 0; }
        
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; background: #0d9488; color: #fff; padding: 5px; border-radius: 4px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 11px; color: #475569; width: 30%; }
        .info-table td { border: 1px solid #cbd5e1; padding: 8px; font-size: 12px; color: #1e293b; width: 70%; font-weight: bold; }
        
        .section-title { font-size: 14px; font-weight: bold; color: #0d9488; border-bottom: 1px solid #0d9488; padding-bottom: 3px; margin: 20px 0 10px 0; }
        
        .fee-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .fee-table th { background: #0d9488; color: #fff; padding: 10px; text-align: left; border: 1px solid #0f766e; font-size: 11px; }
        .fee-table td { padding: 12px 10px; border: 1px solid #cbd5e1; font-size: 12px; }
        .fee-table .amount-col { text-align: right; font-weight: bold; width: 30%; }
        .fee-table .total-row td { background: #f8fafc; font-weight: bold; font-size: 13px; border-top: 2px solid #0d9488; }
        
        .footer { margin-top: 50px; font-size: 11px; color: #64748b; text-align: center; border-top: 1px solid #cbd5e1; padding-top: 10px; }
        .signature { margin-top: 60px; width: 100%; }
        .sig-box { width: 220px; border-top: 1px solid #333; text-align: center; font-size: 12px; padding-top: 5px; }
        .sig-left { float: left; }
        .sig-right { float: right; }
        .stamp-container { height: 110px; text-align: center; margin-bottom: 5px; }
        .stamp-img { width: 110px; height: 110px; object-fit: contain; display: inline-block; }
    </style>
</head>
<body>

    <div class="header">
        ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" class="logo">' : '') . '
        <h1 class="institute-title">LogixCode IT Solution</h1>
        <p class="institute-sub">Advanced Training & Development Center<br>Kanpur, Uttar Pradesh - 208001</p>
    </div>

    <div class="title">FEES RECEIPT / SLIP</div>

    <div class="section-title">1. Student Details</div>
    <table class="info-table">
        <tr><th>Registration ID</th><td style="color:#0284c7;">' . htmlspecialchars($reg['registration_id']) . '</td></tr>
        <tr><th>Student Name</th><td>' . htmlspecialchars(strtoupper($reg['first_name'] . ' ' . $reg['last_name'])) . '</td></tr>
        <tr><th>Selected Program</th><td>' . htmlspecialchars(strtoupper($reg['program'])) . '</td></tr>
        <tr><th>Contact Number</th><td>' . htmlspecialchars($reg['phone']) . '</td></tr>
        <tr><th>Email Address</th><td>' . htmlspecialchars($reg['email'] ?? '-') . '</td></tr>
    </table>

    <div class="section-title">2. Payment Details</div>
    <table class="info-table">
        <tr><th>Receipt Number</th><td>REC-' . htmlspecialchars($reg['registration_id']) . '</td></tr>
        <tr><th>Payment Date</th><td>' . htmlspecialchars($payment_date) . '</td></tr>
        <tr><th>Payment Mode</th><td>' . htmlspecialchars(strtoupper($payment_mode)) . '</td></tr>
        <tr><th>Transaction / Gateway ID</th><td>' . htmlspecialchars($transaction_id) . '</td></tr>
        <tr><th>Payment Status</th><td style="color: ' . ($payment_status === 'SUCCESS' || $payment_status === 'OFFLINE' ? '#16a34a' : '#d97706') . ';">' . htmlspecialchars(strtoupper($payment_status)) . '</td></tr>
    </table>

    <div class="section-title">3. Fee Details</div>
    <table class="fee-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right; width: 30%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Program Registration & Admission Fee (Program: ' . htmlspecialchars($reg['program']) . ')</td>
                <td class="amount-col">&#8377;' . number_format($amount, 2) . '</td>
            </tr>
            <tr class="total-row">
                <td>Total Amount Paid</td>
                <td class="amount-col">&#8377;' . number_format($amount, 2) . '</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 10px; color: #475569; font-weight: bold; background: #f8fafc;">
                    Amount in Words: ' . htmlspecialchars($amountInWords) . '
                </td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:10px; color:#64748b; text-align:center; margin-top:20px;">
        * Note: This is an official computer-generated receipt of payment for registration.
    </p>

    <div class="signature">
        <div class="sig-right" style="width: 220px; text-align: center;">
            <div class="stamp-container">
                ' . ($stampBase64 ? '<img src="' . $stampBase64 . '" class="stamp-img">' : '') . '
            </div>
            <div class="sig-box" style="width: 100%; border-top: 1px solid #333; padding-top: 5px; margin-top: 0;">
                Authorized Signatory<br><span style="font-size:9px; color:#64748b;">(LogixCode IT Solution)</span>
            </div>
        </div>
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
$options->set('defaultFont', 'DejaVu Sans'); // Required for Rupee symbol
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF to Browser
$filename = 'FeeReceipt_' . $reg['registration_id'] . '.pdf';

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
