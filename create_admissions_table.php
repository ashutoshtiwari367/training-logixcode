<?php
/**
 * Migration – create the `admissions` table if it does not exist.
 * Place this file in the project root (c:/xampp/htdocs/training) and run:
 *   php create_admissions_table.php
 */

require_once __DIR__ . '/config/db.php';

// Check if table exists
$checkStmt = $pdo->query("SHOW TABLES LIKE 'admissions'");
if ($checkStmt->fetch()) {
    echo "✅ `admissions` table already exists. No action taken.\n";
    exit;
}

$createSql = <<<SQL
CREATE TABLE admissions (
    admission_id       VARCHAR(50)   NOT NULL PRIMARY KEY,
    student_name       VARCHAR(255)  NOT NULL,
    phone              VARCHAR(30)   NULL,
    email              VARCHAR(255)  NULL,
    course_name        VARCHAR(255)  NULL,
    total_fees         DECIMAL(10,2) DEFAULT 0,
    paid_amount        DECIMAL(10,2) DEFAULT 0,
    registered_amount  DECIMAL(10,2) DEFAULT 0,
    balance_amount     DECIMAL(10,2) GENERATED ALWAYS AS (total_fees - (paid_amount + registered_amount)) VIRTUAL,
    fee_status         ENUM('PAID','PARTIAL','PENDING') DEFAULT 'PENDING',
    created_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

$pdo->exec($createSql);

echo "✅ `admissions` table created successfully.\n";
?>
