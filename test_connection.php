<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/config/db.php';

try {
    $pdo->query('SELECT 1');
    echo "✅ DB connection successful.\n";
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    foreach ($tables as $t) {
        echo " - $t\n";
    }
} catch (PDOException $e) {
    echo "❌ DB connection failed: " . $e->getMessage() . "\n";
}
?>
