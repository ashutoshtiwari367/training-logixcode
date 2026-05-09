<?php
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_id'] = 1; // mock admin login

require 'c:/xampp/htdocs/training/config/db.php';
$stmt = $pdo->query("SELECT registration_id FROM registrations LIMIT 1");
$id = $stmt->fetchColumn();

$_GET['id'] = $id;

require 'c:/xampp/htdocs/training/admin/download_registration_pdf.php';
