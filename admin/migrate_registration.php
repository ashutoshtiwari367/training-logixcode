<?php
/*
 * Migration: add student_id and password_hash columns to registrations table
 * Run this script once on the live server (https://training.logixcode.com/admin/migrate_registration.php)
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db.php';

try {
    // Add student_id column if it doesn't exist
    $pdo->exec("ALTER TABLE registrations ADD COLUMN IF NOT EXISTS student_id VARCHAR(255) NULL");
    // Add password_hash column if it doesn't exist
    $pdo->exec("ALTER TABLE registrations ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) NULL");
    echo "✅ Migration completed: columns added (if they were missing).";
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage();
}
?>
