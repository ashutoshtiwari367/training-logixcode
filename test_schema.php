<?php
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/training/';
require 'config/db.php';

echo file_exists('vendor/autoload.php') ? 'autoload: OK' : 'autoload: MISSING';
echo PHP_EOL;

require_once 'vendor/autoload.php';
echo class_exists('Dompdf\Dompdf') ? 'dompdf: OK' : 'dompdf: MISSING';
echo PHP_EOL;
echo class_exists('PHPMailer\PHPMailer\PHPMailer') ? 'phpmailer: OK' : 'phpmailer: MISSING';
echo PHP_EOL;

// Test allow_url_fopen
echo 'allow_url_fopen: ' . (ini_get('allow_url_fopen') ? 'ON' : 'OFF');
echo PHP_EOL;

// Check uploads/id_cards directory
echo is_dir('uploads/id_cards') ? 'id_cards dir: EXISTS' : 'id_cards dir: MISSING (will be created)';
echo PHP_EOL;
echo is_writable('uploads') ? 'uploads writable: YES' : 'uploads writable: NO';
echo PHP_EOL;
