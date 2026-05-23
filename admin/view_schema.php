<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query('SHOW COLUMNS FROM registrations');
    echo '<h2>Columns in `registrations` table</h2><ul>';
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo '<li>' . htmlspecialchars($col['Field']) . ' (' . $col['Type'] . ')</li>';
    }
    echo '</ul>';
} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
