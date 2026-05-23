<?php
/**
 * migrate_admissions.php
 * Alters the `admissions` table to add the missing student columns.
 * Run this file in your browser or command line to update the database.
 */

header('Content-Type: text/plain');
require_once __DIR__ . '/config/db.php';

try {
    echo "Starting Admissions table migration...\n";

    // 1. Check existing columns
    $stmt = $pdo->query("DESCRIBE admissions");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $needed_columns = [
        'registration_id'   => "ALTER TABLE admissions ADD COLUMN registration_id VARCHAR(50) NULL AFTER admission_id",
        'father_name'       => "ALTER TABLE admissions ADD COLUMN father_name VARCHAR(255) NULL AFTER student_name",
        'dob'               => "ALTER TABLE admissions ADD COLUMN dob DATE NULL AFTER father_name",
        'gender'            => "ALTER TABLE admissions ADD COLUMN gender VARCHAR(20) NULL AFTER dob",
        'student_photo'     => "ALTER TABLE admissions ADD COLUMN student_photo VARCHAR(255) NULL AFTER gender",
        'aadhar_number'     => "ALTER TABLE admissions ADD COLUMN aadhar_number VARCHAR(30) NULL AFTER student_photo",
        'medical_condition' => "ALTER TABLE admissions ADD COLUMN medical_condition VARCHAR(255) NULL AFTER aadhar_number",
        'father_phone'      => "ALTER TABLE admissions ADD COLUMN father_phone VARCHAR(30) NULL AFTER phone",
        'local_address'     => "ALTER TABLE admissions ADD COLUMN local_address TEXT NULL AFTER father_phone",
        'permanent_address' => "ALTER TABLE admissions ADD COLUMN permanent_address TEXT NULL AFTER local_address",
        'college_name'      => "ALTER TABLE admissions ADD COLUMN college_name VARCHAR(255) NULL AFTER permanent_address",
        'degree'            => "ALTER TABLE admissions ADD COLUMN degree VARCHAR(100) NULL AFTER college_name",
        'branch'            => "ALTER TABLE admissions ADD COLUMN branch VARCHAR(100) NULL AFTER degree",
        'current_semester'  => "ALTER TABLE admissions ADD COLUMN current_semester VARCHAR(50) NULL AFTER branch",
        'hostel_required'   => "ALTER TABLE admissions ADD COLUMN hostel_required VARCHAR(10) DEFAULT 'No' AFTER course_name",
        'laptop_required'   => "ALTER TABLE admissions ADD COLUMN laptop_required VARCHAR(10) DEFAULT 'No' AFTER hostel_required",
        'payment_mode'      => "ALTER TABLE admissions ADD COLUMN payment_mode VARCHAR(50) DEFAULT 'CASH' AFTER paid_amount"
    ];

    $added_count = 0;
    foreach ($needed_columns as $col => $sql) {
        if (!in_array($col, $columns)) {
            echo "Adding column '$col'...";
            $pdo->exec($sql);
            echo " SUCCESS\n";
            $added_count++;
        } else {
            echo "Column '$col' already exists. Skipping.\n";
        }
    }

    echo "\nMigration complete! $added_count columns added.\n";

} catch (Exception $e) {
    echo "\n❌ Database Migration Error: " . $e->getMessage() . "\n";
}
?>
