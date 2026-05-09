<?php
require_once 'c:/xampp/htdocs/training/config/db.php';
require_once 'c:/xampp/htdocs/training/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$stmt = $pdo->prepare("SELECT * FROM registrations LIMIT 1");
$stmt->execute();
$reg = $stmt->fetch();

if (!$reg) die("No reg");

$logoUrl = 'https://res.cloudinary.com/de7mh41io/image/upload/f_jpg,b_white,w_80/v1749888137/logixcode-logo';
$logoData = @file_get_contents($logoUrl);
$logoBase64 = $logoData ? 'data:image/jpeg;base64,' . base64_encode($logoData) : '';

$html = '<html><body><h1>Test</h1><img src="'.$logoBase64.'"></body></html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

try {
    $dompdf->render();
    file_put_contents('test.pdf', $dompdf->output());
    echo "SUCCESS";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
