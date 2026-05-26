<?php
/**
 * admin/update-registration-counselor.php
 * Lightweight AJAX endpoint to update counselor for a registration.
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// Only logged in admin/staff can access
if (!isLoggedIn()) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_id = trim($_POST['registration_id'] ?? '');
    $counselor = trim($_POST['counselor_name'] ?? '');

    if (empty($reg_id)) {
        echo json_encode(['success' => false, 'message' => 'Registration ID is required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE registrations SET counselor_name = ? WHERE registration_id = ?");
        $stmt->execute([$counselor, $reg_id]);
        
        echo json_encode(['success' => true, 'message' => 'Counselor updated successfully!']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
