<?php
session_start();
$_SESSION['user_id'] = 1; // mock admin login

require 'c:/xampp/htdocs/training/config/db.php';
$stmt = $pdo->query("SELECT registration_id FROM registrations LIMIT 1");
$id = $stmt->fetchColumn();

$_GET['id'] = $id;

ob_start();
require 'c:/xampp/htdocs/training/admin/download_registration_pdf.php';
$output = ob_get_clean();

$headers = headers_list();
print_r($headers);
echo "Length: " . strlen($output) . "\n";
file_put_contents('c:/xampp/htdocs/training/test_output.pdf', $output);
