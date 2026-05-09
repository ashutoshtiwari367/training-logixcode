<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Check</h1>";

echo "<h3>1. PHP Version: " . PHP_VERSION . "</h3>";

echo "<h3>2. Database Connection Test:</h3>";
try {
    require_once 'config/db.php';
    echo "<p style='color:green'>SUCCESS: Database connected successfully!</p>";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'registrations'");
    if ($stmt->fetch()) {
        echo "<p style='color:green'>SUCCESS: 'registrations' table exists!</p>";
    } else {
        echo "<p style='color:red'>ERROR: 'registrations' table NOT found! Please run the SQL queries.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h3>3. Mail Config Check:</h3>";
if (file_exists('config/mail.php')) {
    echo "<p style='color:green'>SUCCESS: mail.php found.</p>";
} else {
    echo "<p style='color:red'>ERROR: mail.php missing!</p>";
}
?>
