<?php
/**
 * DIAGNOSTIC SCRIPT — Delete after use!
 * Access: https://training.logixcode.com/admin/debug_check.php?key=lx_debug_2026
 */

// Simple security key
if (!isset($_GET['key']) || $_GET['key'] !== 'lx_debug_2026') {
    http_response_code(403);
    die('403 Forbidden');
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$results = [];

// ─── 1. PHP VERSION & EXTENSIONS ──────────────────────────
$results['php_version'] = PHP_VERSION;
$results['extensions'] = [
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'openssl'   => extension_loaded('openssl'),
    'mbstring'  => extension_loaded('mbstring'),
    'gd'        => extension_loaded('gd'),
];

// ─── 2. DATABASE CONNECTION & COLLATION TEST ──────────────
try {
    require_once __DIR__ . '/../config/db.php';
    
    // Check DB version
    $ver = $pdo->query("SELECT VERSION() as v")->fetch();
    $results['db_version'] = $ver['v'];

    // Test CONVERT-based JOIN (new fix)
    $stmt = $pdo->prepare("
        SELECT r.registration_id, r.first_name, a.email as admission_email
        FROM registrations r
        LEFT JOIN admissions a 
            ON CONVERT(r.registration_id USING utf8mb4) = CONVERT(a.registration_id USING utf8mb4)
        LIMIT 3
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results['db_join_test'] = 'OK — ' . count($rows) . ' rows';
    $results['db_join_sample'] = $rows;

    // Test old COLLATE (to confirm it fails)
    try {
        $stmt2 = $pdo->prepare("
            SELECT r.registration_id
            FROM registrations r
            LEFT JOIN admissions a ON r.registration_id = a.registration_id COLLATE utf8mb4_unicode_ci
            LIMIT 1
        ");
        $stmt2->execute();
        $results['db_old_collate'] = 'OK (no error — server may be lenient)';
    } catch (Exception $e2) {
        $results['db_old_collate'] = 'ERROR: ' . $e2->getMessage();
    }

    // Check table collations
    $collations = $pdo->query("
        SELECT TABLE_NAME, TABLE_COLLATION 
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME IN ('registrations','admissions')
    ")->fetchAll(PDO::FETCH_ASSOC);
    $results['table_collations'] = $collations;

    // Check column collations
    $colColl = $pdo->query("
        SELECT TABLE_NAME, COLUMN_NAME, COLLATION_NAME 
        FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME IN ('registrations','admissions')
        AND COLUMN_NAME = 'registration_id'
    ")->fetchAll(PDO::FETCH_ASSOC);
    $results['column_collations'] = $colColl;

} catch (Exception $e) {
    $results['db_error'] = $e->getMessage();
}

// ─── 3. SMTP MAIL TEST ────────────────────────────────────
if (isset($_GET['sendmail'])) {
    try {
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../config/mail.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;

        $mail = new PHPMailer(true);
        $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function($str, $level) use (&$results) {
            $results['smtp_debug'][] = trim($str);
        };

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $testTo = $_GET['to'] ?? BCC_EMAIL;
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($testTo);
        $mail->isHTML(true);
        $mail->Subject = '✅ Test Email from Debug Script — ' . date('H:i:s');
        $mail->Body    = '<h2>Test Email</h2><p>This is a test email from <strong>debug_check.php</strong> sent at ' . date('Y-m-d H:i:s') . '</p>';
        $mail->AltBody = 'Test email from debug_check.php at ' . date('Y-m-d H:i:s');

        $mail->send();
        $results['mail_status'] = 'SUCCESS — Email sent to ' . $testTo;
    } catch (Exception $e) {
        $results['mail_status'] = 'FAILED: ' . $e->getMessage();
        $results['mail_error_info'] = $mail->ErrorInfo ?? 'N/A';
    }
} else {
    $results['mail_note'] = 'Add ?sendmail=1 to URL to test SMTP. Add &to=email@example.com to override recipient.';
}

// ─── 4. FILE PATHS ────────────────────────────────────────
$results['paths'] = [
    'vendor_autoload' => file_exists(__DIR__ . '/../vendor/autoload.php') ? 'EXISTS' : 'MISSING',
    'config_mail'     => file_exists(__DIR__ . '/../config/mail.php') ? 'EXISTS' : 'MISSING',
    'config_db'       => file_exists(__DIR__ . '/../config/db.php') ? 'EXISTS' : 'MISSING',
    'uploads_dir'     => is_writable(__DIR__ . '/../uploads') ? 'WRITABLE' : 'NOT WRITABLE / MISSING',
];

// ─── OUTPUT ───────────────────────────────────────────────
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>🔍 Debug Check</title>
<style>
body { font-family: monospace; background: #0f172a; color: #e2e8f0; padding: 30px; }
h1 { color: #7dd3fc; }
h2 { color: #67e8f9; border-bottom: 1px solid #334155; padding-bottom: 5px; }
.ok    { color: #4ade80; }
.err   { color: #f87171; }
.warn  { color: #facc15; }
pre { background: #1e293b; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 13px; }
.section { margin-bottom: 30px; }
a { color: #7dd3fc; }
</style>
</head>
<body>
<h1>🔍 Debug Diagnostic — <?= date('Y-m-d H:i:s T') ?></h1>
<p class="warn">⚠️ Delete this file after use: <code>admin/debug_check.php</code></p>

<div class="section">
<h2>PHP Environment</h2>
<pre>PHP Version: <span class="<?= version_compare($results['php_version'], '8.0', '>=') ? 'ok' : 'err' ?>"><?= $results['php_version'] ?></span>
<?php foreach ($results['extensions'] as $ext => $loaded): ?>
<?= $ext ?>: <span class="<?= $loaded ? 'ok' : 'err' ?>"><?= $loaded ? 'LOADED' : 'MISSING' ?></span>
<?php endforeach; ?></pre>
</div>

<div class="section">
<h2>Database</h2>
<pre><?php
if (isset($results['db_error'])) {
    echo '<span class="err">DB Error: ' . htmlspecialchars($results['db_error']) . '</span>';
} else {
    echo 'MariaDB/MySQL Version: <span class="ok">' . htmlspecialchars($results['db_version']) . "</span>\n";
    echo 'JOIN Test (CONVERT): <span class="' . (str_starts_with($results['db_join_test'], 'OK') ? 'ok' : 'err') . '">' . htmlspecialchars($results['db_join_test']) . "</span>\n";
    echo 'JOIN Test (old COLLATE): <span class="warn">' . htmlspecialchars($results['db_old_collate']) . "</span>\n\n";
    echo "Table Collations:\n";
    foreach ($results['table_collations'] as $t) {
        echo '  ' . $t['TABLE_NAME'] . ': ' . $t['TABLE_COLLATION'] . "\n";
    }
    echo "\nColumn (registration_id) Collations:\n";
    foreach ($results['column_collations'] as $c) {
        echo '  ' . $c['TABLE_NAME'] . '.' . $c['COLUMN_NAME'] . ': ' . $c['COLLATION_NAME'] . "\n";
    }
}
?></pre>
</div>

<div class="section">
<h2>SMTP Mail Test</h2>
<pre><?php
if (isset($results['mail_status'])) {
    $isOk = str_contains($results['mail_status'], 'SUCCESS');
    echo '<span class="' . ($isOk ? 'ok' : 'err') . '">' . htmlspecialchars($results['mail_status']) . '</span>';
    if (!empty($results['mail_error_info'])) {
        echo "\nError Info: " . htmlspecialchars($results['mail_error_info']);
    }
    if (!empty($results['smtp_debug'])) {
        echo "\n\n--- SMTP Debug Log ---\n";
        foreach ($results['smtp_debug'] as $line) {
            echo htmlspecialchars($line) . "\n";
        }
    }
} else {
    echo $results['mail_note'];
}
?></pre>
<p>
    <a href="?key=lx_debug_2026&sendmail=1">▶ Test SMTP (send to BCC admin)</a> &nbsp;|&nbsp;
    <a href="?key=lx_debug_2026&sendmail=1&to=nitintiwari9062@gmail.com">▶ Test SMTP → nitintiwari9062@gmail.com</a>
</p>
</div>

<div class="section">
<h2>File Paths</h2>
<pre><?php
foreach ($results['paths'] as $path => $status) {
    $cls = ($status === 'EXISTS' || $status === 'WRITABLE') ? 'ok' : 'err';
    echo $path . ': <span class="' . $cls . '">' . $status . '</span>' . "\n";
}
?></pre>
</div>

</body>
</html>
