<?php
session_start();
$_SESSION['user_id'] = 1; // mock admin login

// Get the first registration ID
require 'c:/xampp/htdocs/training/config/db.php';
$stmt = $pdo->query("SELECT registration_id FROM registrations LIMIT 1");
$id = $stmt->fetchColumn();

$_GET['id'] = $id;

ob_start();
require 'c:/xampp/htdocs/training/admin/download_registration_pdf.php';
$output = ob_get_clean();

file_put_contents('c:/xampp/htdocs/training/test_output.txt', $output);
echo "Length: " . strlen($output) . "\n";
echo "First 100 bytes: " . substr($output, 0, 100) . "\n";
