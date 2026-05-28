<?php
require_once __DIR__ . '/config/db.php';
$stmt = $pdo->query('SELECT registration_id, student_id, first_name, email FROM registrations ORDER BY created_at DESC LIMIT 10');
print_r($stmt->fetchAll());
?>
