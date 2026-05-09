<?php
require_once 'config/db.php';
require_once 'config/auth.php';

$email = 'admin@institute.com';
$password = 'Admin@123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Password reset successfully! Try logging in with:<br>Email: $email<br>Password: $password";
    } else {
        // If user doesn't exist, create it
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['System Administrator', $email, $hash, 'admin']);
        echo "Admin user created successfully! Try logging in with:<br>Email: $email<br>Password: $password";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
