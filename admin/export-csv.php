<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// Check if user is logged in
requireLogin();

// ============================================
// Filters + Query (matching admin/dashboard.php)
// ============================================
$filters = [
    'program'        => $_GET['program'] ?? '',
    'payment_status' => $_GET['payment_status'] ?? '',
    'date_from'      => $_GET['date_from'] ?? '',
    'date_to'        => $_GET['date_to'] ?? '',
    'search'         => $_GET['search'] ?? ''
];

$sql    = "SELECT r.*, p.payment_gateway_id, p.amount, p.status as payment_status 
           FROM registrations r 
           LEFT JOIN payments p ON r.registration_id = p.registration_id 
           WHERE 1=1";
$params = [];

if (!empty($filters['program'])) {
    $sql      .= " AND r.program = ?";
    $params[]  = $filters['program'];
}
if (!empty($filters['payment_status'])) {
    if ($filters['payment_status'] === 'PAID') {
        $sql .= " AND r.payment_mode = 'ONLINE' AND p.status = 'SUCCESS'";
    } elseif ($filters['payment_status'] === 'OFFLINE') {
        $sql .= " AND r.payment_mode = 'OFFLINE'";
    }
}
if (!empty($filters['date_from'])) {
    $sql      .= " AND DATE(r.created_at) >= ?";
    $params[]  = $filters['date_from'];
}
if (!empty($filters['date_to'])) {
    $sql      .= " AND DATE(r.created_at) <= ?";
    $params[]  = $filters['date_to'];
}
if (!empty($filters['search'])) {
    $sql       .= " AND (r.first_name LIKE ? OR r.last_name LIKE ? OR r.email LIKE ? OR r.registration_id LIKE ?)";
    $s          = '%' . $filters['search'] . '%';
    $params[]   = $s; $params[] = $s; $params[] = $s; $params[] = $s;
}

$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Clear buffers to avoid any leading whitespace/errors in CSV
while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Registrations_Export_' . date('Y-m-d_H-i-s') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Excel compatibility with international characters
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Define Headers
$headers = [
    'Registration ID',
    'Student ID',
    'Student Name',
    'Email Address',
    'Phone Number',
    'Date of Birth',
    'Gender',
    'Address',
    'Highest Qualification',
    'Percentage / CGPA',
    'College / University',
    'Year of Passing',
    'Selected Program',
    'Experience',
    'Motivation / Intent',
    'Counselor Name',
    'Payment Mode',
    'Payment Status',
    'Paid Amount (INR)',
    'Transaction ID',
    'Registration Date'
];

fputcsv($output, $headers);

foreach ($registrations as $reg) {
    // Process payment status details
    $pay_status = $reg['payment_status'] ?? 'PENDING';
    if ($reg['payment_mode'] === 'OFFLINE') {
        $pay_status = 'OFFLINE';
    } elseif ($reg['payment_status'] === 'SUCCESS') {
        $pay_status = 'PAID';
    }

    $counselor = $reg['counselor_name'] ?? 'Direct / Self';

    // Format row
    $row = [
        $reg['registration_id'],
        $reg['student_id'] ?? '',
        $reg['first_name'] . ' ' . $reg['last_name'],
        $reg['email'],
        $reg['phone'],
        $reg['dob'] ? date('d-M-Y', strtotime($reg['dob'])) : '',
        ucfirst($reg['gender']),
        $reg['address'],
        $reg['qualification'],
        $reg['percentage'],
        $reg['college'] ?? '',
        $reg['year_of_passing'] ?? '',
        $reg['program'],
        $reg['experience'] ?? '',
        $reg['motivation'] ?? '',
        $counselor,
        $reg['payment_mode'],
        $pay_status,
        (!empty($reg['amount']) && $reg['amount'] > 0) ? number_format($reg['amount'], 2) : '0.00',
        $reg['payment_gateway_id'] ?? '',
        date('d-M-Y h:i A', strtotime($reg['created_at']))
    ];
    fputcsv($output, $row);
}

fclose($output);
exit;
