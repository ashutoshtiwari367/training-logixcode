<?php
// ============================================================
//  MAIL SENDER — ID CARD EMAIL
//  File path: config/mailer.php
//  
//  Is file ko include karo jahan bhi email bhejna ho:
//  require_once __DIR__ . '/../config/mailer.php';
//  $result = sendIDCardEmail($pdo, $student_id, $to_email, $pdf_base64);
// ============================================================


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
// ── MAIL CONFIG — YAHAN APNI DETAILS DAALO ───────────────
define('MAIL_FROM_EMAIL', 'your_email@gmail.com');   // ← Apna email
define('MAIL_FROM_NAME',  'Student Support');     // ← Sender name
define('MAIL_SMTP_HOST',  'smtp.hostinger.com');          // Gmail SMTP
define('MAIL_SMTP_PORT',  587);                       // TLS port
define('MAIL_SMTP_USER',  'info@logixcode.com');   // ← Apna Gmail
define('MAIL_SMTP_PASS',  'Mu$k@n1106');    // ← Gmail App Password
                                                      //   (Gmail → Settings → App Passwords)

// ─────────────────────────────────────────────────────────

/**
 * ID Card PDF email bhejo student ko
 *
 * @param PDO    $pdo         Database connection
 * @param string $student_id  Student ID (e.g. STU20240001)
 * @param string $to_email    Student ka email address
 * @param string $pdf_base64  PDF ka base64 string (frontend se aata hai)
 * @return array ['success' => bool, 'message' => string]
 */
function sendIDCardEmail($pdo, $student_id, $to_email, $pdf_base64) {

    // ── Validate ──────────────────────────────────────────
    if (!$student_id || !$to_email || !$pdf_base64) {
        return ['success' => false, 'message' => 'Required fields missing'];
    }
    if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email address'];
    }

    // ── Student name fetch karo ───────────────────────────
    $stmt = $pdo->prepare("SELECT student_name FROM students WHERE student_id = ? LIMIT 1");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    $to_name = $student ? $student['student_name'] : 'Student';

    // ── PDF save karo server pe ───────────────────────────
    $pdf_dir  = __DIR__ . '/../id_cards/';
    if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0755, true);
    $pdf_file = $pdf_dir . $student_id . '_idcard.pdf';
    file_put_contents($pdf_file, base64_decode($pdf_base64));
    $pdf_rel_path = 'id_cards/' . $student_id . '_idcard.pdf';

    // ── EMAIL SEND ────────────────────────────────────────
    $sent = false;

    // PHPMailer available hai? (composer se install karo)
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        $sent = _sendWithPHPMailer($to_email, $to_name, $student_id, $pdf_file);
    } else {
        // Fallback: PHP built-in mail()
        $sent = _sendWithMail($to_email, $to_name, $student_id, $pdf_base64);
    }

    // ── DB mein log karo ──────────────────────────────────
    try {
        if ($sent) {
            // Student record update karo
            $pdo->prepare("UPDATE students SET id_card_sent = 1, id_card_path = ? WHERE student_id = ?")
                ->execute([$pdf_rel_path, $student_id]);
        }
        // Log table mein entry
        $pdo->prepare("INSERT INTO id_card_logs (student_id, email_to, status, pdf_path, notes) VALUES (?, ?, ?, ?, ?)")
            ->execute([
                $student_id,
                $to_email,
                $sent ? 'Sent' : 'Failed',
                $pdf_rel_path,
                $sent ? null : 'Email send failed'
            ]);
    } catch (PDOException $e) {
        // DB error ignore karo, email result return karo
    }

    if ($sent) {
        return ['success' => true,  'message' => "ID Card successfully sent to {$to_email}"];
    } else {
        return ['success' => false, 'message' => 'Email send failed. Check mail config.'];
    }
}

// ─────────────────────────────────────────────────────────
// PRIVATE: PHPMailer se send (recommended — SMTP)
// Install: composer require phpmailer/phpmailer
// ─────────────────────────────────────────────────────────
function _sendWithPHPMailer($to_email, $to_name, $student_id, $pdf_file) {
    require_once __DIR__ . '/../vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = MAIL_SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_SMTP_USER;
        $mail->Password   = MAIL_SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // From / To
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to_email, $to_name);

        // Subject & Body
        $mail->isHTML(true);
        $mail->Subject = "🪪 Your Student ID Card — {$to_name}";
        $mail->Body    = _getEmailHTML($to_name, $student_id);
        $mail->AltBody = "Dear {$to_name},\n\nYour Student ID Card (ID: {$student_id}) is attached.\n\nRegards,\n" . MAIL_FROM_NAME;

        // PDF attachment
        $mail->addAttachment($pdf_file, $student_id . '_ID_Card.pdf');

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

// ─────────────────────────────────────────────────────────
// PRIVATE: PHP mail() se send (fallback — shared hosting)
// ─────────────────────────────────────────────────────────
function _sendWithMail($to_email, $to_name, $student_id, $pdf_base64) {
    $boundary = md5(time() . $student_id);
    $subject  = "=?UTF-8?B?" . base64_encode("🪪 Your Student ID Card — {$to_name}") . "?=";

    $headers  = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM_EMAIL . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    // HTML part
    $body  = "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode(_getEmailHTML($to_name, $student_id))) . "\r\n";

    // PDF attachment
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: application/pdf; name=\"{$student_id}_ID_Card.pdf\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"{$student_id}_ID_Card.pdf\"\r\n\r\n";
    $body .= chunk_split($pdf_base64) . "\r\n";

    $body .= "--{$boundary}--";

    return mail($to_email, $subject, $body, $headers);
}

// ─────────────────────────────────────────────────────────
// PRIVATE: Email HTML template
// ─────────────────────────────────────────────────────────
function _getEmailHTML($to_name, $student_id) {
    $year = date('Y');
    return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:20px;background:#f0f2f5;font-family:'Segoe UI',Arial,sans-serif;">
  <div style="max-width:520px;margin:0 auto;">

    <!-- Header -->
    <div style="background:linear-gradient(135deg,#6c63ff,#00d4aa);border-radius:14px 14px 0 0;padding:32px 28px;text-align:center;">
      <div style="font-size:40px;margin-bottom:10px;">🪪</div>
      <h1 style="color:#fff;margin:0;font-size:22px;font-weight:800;letter-spacing:.5px;">Student ID Card</h1>
      <p style="color:rgba(255,255,255,.8);margin:6px 0 0;font-size:14px;">Your official identity card is ready</p>
    </div>

    <!-- Body -->
    <div style="background:#ffffff;padding:30px 28px;border-left:1px solid #e8e8e8;border-right:1px solid #e8e8e8;">
      <p style="font-size:16px;color:#333;margin:0 0 12px;">Dear <strong>{$to_name}</strong>,</p>
      <p style="color:#555;line-height:1.7;margin:0 0 20px;">
        Your Student ID Card has been generated successfully.<br>
        Please find it <strong>attached as a PDF</strong> to this email.
      </p>

      <!-- Student ID Box -->
      <div style="background:#f5f5ff;border:1.5px solid #d0cbff;border-radius:10px;padding:16px 20px;margin-bottom:24px;">
        <p style="margin:0;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:.05em;">Your Student ID</p>
        <p style="margin:6px 0 0;font-size:20px;font-weight:800;color:#6c63ff;font-family:monospace;letter-spacing:.1em;">{$student_id}</p>
      </div>

      <!-- Info List -->
      <p style="color:#555;font-size:14px;margin:0 0 10px;">Keep this ID card safe. You may need it for:</p>
      <ul style="color:#555;font-size:14px;line-height:2;padding-left:20px;margin:0 0 24px;">
        <li>College / Institute entry</li>
        <li>Library access</li>
        <li>Hostel verification</li>
        <li>Exam hall entry</li>
      </ul>

      <p style="color:#888;font-size:13px;border-top:1px solid #eee;padding-top:16px;margin:0;">
        If you have any questions, please contact the admin office.
      </p>
    </div>

    <!-- Footer -->
    <div style="background:#f8f8f8;border:1px solid #e8e8e8;border-top:none;border-radius:0 0 14px 14px;padding:16px 28px;text-align:center;">
      <p style="margin:0;font-size:12px;color:#aaa;">
        © {$year} Student Registration Admin Panel<br>
        This is an automated email. Please do not reply.
      </p>
    </div>

  </div>
</body>
</html>
HTML;
}
?>
