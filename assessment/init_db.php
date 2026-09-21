<?php
/**
 * Assessment Database Setup & Seeder
 * assessment/init_db.php
 */
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Create assessments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS assessments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            course VARCHAR(100) NOT NULL,
            duration_minutes INT DEFAULT 15,
            pass_percentage DECIMAL(5,2) DEFAULT 50.00,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 2. Create assessment questions table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS assessment_questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            assessment_id INT NOT NULL,
            question TEXT NOT NULL,
            option_a TEXT NOT NULL,
            option_b TEXT NOT NULL,
            option_c TEXT NOT NULL,
            option_d TEXT NOT NULL,
            correct_option ENUM('A', 'B', 'C', 'D') NOT NULL,
            marks INT DEFAULT 1,
            FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // 3. Create assessment attempts table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS assessment_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            assessment_id INT NOT NULL,
            student_name VARCHAR(255) NOT NULL,
            mobile VARCHAR(20) NOT NULL,
            email VARCHAR(255) NOT NULL,
            course VARCHAR(100) NOT NULL,
            year VARCHAR(50) NOT NULL,
            college_name VARCHAR(255) NOT NULL,
            score INT NOT NULL,
            total_questions INT NOT NULL,
            percentage DECIMAL(5,2) NOT NULL,
            status ENUM('PASS', 'FAIL') NOT NULL,
            answers_json LONGTEXT NULL,
            completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
            INDEX idx_assessment_id (assessment_id),
            INDEX idx_email (email),
            INDEX idx_mobile (mobile)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Seed default sample assessments if empty
    $count = $pdo->query("SELECT COUNT(*) FROM assessments")->fetchColumn();
    if ($count == 0) {

        // --- Assessment 1: Full Stack Web Development ---
        $stmt = $pdo->prepare("INSERT INTO assessments (title, slug, course, duration_minutes, pass_percentage, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Full Stack Web Development Assessment', 'full-stack-dev', 'Full Stack Development', 15, 50.00, 'active']);
        $a1_id = $pdo->lastInsertId();

        $qStmt = $pdo->prepare("INSERT INTO assessment_questions (assessment_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $qStmt->execute([$a1_id, 'Which HTML5 tag is used to specify a header for a document or section?', '<top>', '<head>', '<header>', '<section>', 'C']);
        $qStmt->execute([$a1_id, 'In CSS, which property controls the space inside an element border?', 'margin', 'padding', 'spacing', 'border-spacing', 'B']);
        $qStmt->execute([$a1_id, 'Which JavaScript keyword declares a variable scoped to the block?', 'var', 'let', 'global', 'def', 'B']);
        $qStmt->execute([$a1_id, 'What does SQL stand for?', 'Structured Query Language', 'Strong Question Language', 'Simple Query Logic', 'Sequential Query List', 'A']);
        $qStmt->execute([$a1_id, 'In PHP, which superglobal is used to collect form data submitted via POST method?', '$_GET', '$_REQUEST', '$_POST', '$_SESSION', 'C']);

        // --- Assessment 2: Python & Data Science ---
        $stmt->execute(['Python & Data Science Assessment', 'python-data-science', 'Python Development', 15, 50.00, 'active']);
        $a2_id = $pdo->lastInsertId();

        $qStmt->execute([$a2_id, 'Which of the following data types in Python is immutable?', 'List', 'Dictionary', 'Set', 'Tuple', 'D']);
        $qStmt->execute([$a2_id, 'What keyword is used to define a function in Python?', 'func', 'function', 'def', 'create', 'C']);
        $qStmt->execute([$a2_id, 'Which Python library is primarily used for data manipulation and analysis?', 'NumPy', 'Pandas', 'Flask', 'Matplotlib', 'B']);
        $qStmt->execute([$a2_id, 'What is the correct syntax to output "Hello World" in Python?', 'echo("Hello World")', 'print("Hello World")', 'Console.WriteLine("Hello World")', 'System.out.println("Hello World")', 'B']);
        $qStmt->execute([$a2_id, 'In Python, what operator is used for exponentiation (power)?', '^', '**', 'pow', '^^', 'B']);

        // --- Assessment 3: Java Programming ---
        $stmt->execute(['Java Core & OOPs Skill Test', 'java-programming', 'Java Programming', 15, 50.00, 'active']);
        $a3_id = $pdo->lastInsertId();

        $qStmt->execute([$a3_id, 'Which of these is NOT a Java access modifier?', 'public', 'private', 'protected', 'friendly', 'D']);
        $qStmt->execute([$a3_id, 'Which feature of OOP allows a class to inherit properties from another class?', 'Polymorphism', 'Encapsulation', 'Inheritance', 'Abstraction', 'C']);
        $qStmt->execute([$a3_id, 'What is the size of an int data type in Java?', '16-bit', '32-bit', '64-bit', '8-bit', 'B']);
        $qStmt->execute([$a3_id, 'Which method is the entry point for any Java program?', 'start()', 'init()', 'main()', 'run()', 'C']);
        $qStmt->execute([$a3_id, 'Which keyword is used to prevent method overriding in Java?', 'static', 'abstract', 'final', 'const', 'C']);

        // --- Assessment 4: General Technical Aptitude ---
        $stmt->execute(['Technical Aptitude & Logic Test', 'technical-aptitude', 'General Training', 10, 50.00, 'active']);
        $a4_id = $pdo->lastInsertId();

        $qStmt->execute([$a4_id, 'Find the next number in the series: 2, 4, 8, 16, 32, ...', '48', '64', '52', '60', 'B']);
        $qStmt->execute([$a4_id, 'Which of the following is a non-volatile memory?', 'RAM', 'Cache', 'ROM', 'Registers', 'C']);
        $qStmt->execute([$a4_id, 'If A = 1, CAT = 24, then DOG = ?', '26', '28', '24', '30', 'A']);
        $qStmt->execute([$a4_id, 'What is the full form of API?', 'Application Programming Interface', 'Applied Program Integration', 'Automated Process Interface', 'Application Protocol Integration', 'A']);
        $qStmt->execute([$a4_id, 'Which binary number represents decimal 10?', '1000', '1010', '1100', '1110', 'B']);
    }

} catch (PDOException $e) {
    error_log("Assessment Init DB Error: " . $e->getMessage());
}
