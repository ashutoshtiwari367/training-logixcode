<?php
/**
 * Email Configuration
 * config/mail.php
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Email Configuration
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'info@logixcode.com');
define('SMTP_PASS', 'Mu$k@n1106');
define('SMTP_FROM_EMAIL', 'info@logixcode.com');
define('SMTP_FROM_NAME', 'Logixcode IT Solution');

// Institute Details
define('INSTITUTE_NAME', 'Logixcode IT Solution');
define('INSTITUTE_EMAIL', 'info@logixcode.com');
define('INSTITUTE_PHONE', '+91-8467898854');
define('INSTITUTE_ADDRESS', '2/1 HIG Swarn Jayanti Vihar, Koyla Nagar, Kanpur');
define('INSTITUTE_WEBSITE', 'https://training.logixcode.com');

// BCC Admin Email
define('BCC_EMAIL', 'ashutoshtiwari9453@gmail.com');
define('BCC_NAME', 'Ashutosh Tiwari');

/**
 * Send registration confirmation email
 */
function sendConfirmationEmail($studentData, $registrationId, $paymentStatus) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($studentData['email'], $studentData['firstName'] . ' ' . $studentData['lastName']);
        $mail->addReplyTo(INSTITUTE_EMAIL, INSTITUTE_NAME);

        // BCC - Admin ko copy milegi har registration ki
        $mail->addBCC(BCC_EMAIL, BCC_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = '✅ Registration Confirmed | ' . INSTITUTE_NAME;
        $mail->Body    = getEmailTemplate($studentData, $registrationId, $paymentStatus);
        $mail->AltBody = getEmailPlainText($studentData, $registrationId, $paymentStatus);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Helper: single table row for email
 * ✅ FIX: Was $this->emailRow() — standalone function hai, $this-> nahi hoga
 */
function emailRow($label, $value, $bg = '#ffffff') {
    return "
    <tr style='background:{$bg};'>
      <td style='padding:10px 16px; font-size:13px; color:#666; width:40%; border-bottom:1px solid #f0f0f0;'>{$label}</td>
      <td style='padding:10px 16px; font-size:13px; color:#333; font-weight:600; border-bottom:1px solid #f0f0f0;'>{$value}</td>
    </tr>";
}

/**
 * Full HTML Email Template
 * ✅ FIX: $this->emailRow() → emailRow() (standalone function call)
 * ✅ FIX: {INSTITUTE_PHONE} / {INSTITUTE_EMAIL} → PHP constants variable mein store karke use kiye
 */
function getEmailTemplate($studentData, $registrationId, $paymentStatus) {
    $fullName      = htmlspecialchars($studentData['firstName'] . ' ' . $studentData['lastName']);
    $firstName     = htmlspecialchars($studentData['firstName']);
    $email         = htmlspecialchars($studentData['email']);
    $phone         = htmlspecialchars($studentData['phone']);
    $dob           = htmlspecialchars($studentData['dob']);
    $gender        = htmlspecialchars(ucfirst($studentData['gender']));
    $address       = htmlspecialchars($studentData['address']);
    $qualification = htmlspecialchars($studentData['qualification']);
    $percentage    = !empty($studentData['percentage']) ? htmlspecialchars($studentData['percentage']) : 'N/A';
    $college       = !empty($studentData['college']) ? htmlspecialchars($studentData['college']) : 'N/A';
    $yearOfPassing = !empty($studentData['yearOfPassing']) ? htmlspecialchars($studentData['yearOfPassing']) : 'N/A';
    $program       = htmlspecialchars($studentData['program']);
    $paymentMode   = htmlspecialchars($studentData['payment_mode'] ?? 'OFFLINE');
    $amount        = isset($studentData['amount']) ? '&#8377;' . number_format((float)$studentData['amount'], 2) : '&#8377;500.00';
    $experience    = !empty($studentData['experience']) ? htmlspecialchars($studentData['experience']) : 'N/A';
    $motivation    = !empty($studentData['motivation']) ? htmlspecialchars($studentData['motivation']) : 'N/A';
    $date          = date('d M Y, h:i A');

    // ✅ FIX: Constants ko variables mein store karo — heredoc mein directly constants resolve nahi hote
    $institutePhone   = INSTITUTE_PHONE;
    $instituteEmail   = INSTITUTE_EMAIL;
    $instituteName    = INSTITUTE_NAME;
    $instituteAddress = INSTITUTE_ADDRESS;
    $instituteWebsite = INSTITUTE_WEBSITE;

    $paymentStatusColor = ($paymentStatus === 'OFFLINE' || $paymentStatus === 'SUCCESS') ? '#28a745' : '#ffc107';
    $paymentStatusLabel = ($paymentStatus === 'OFFLINE') ? '&#10003; Paid (Offline/Cash)' : htmlspecialchars($paymentStatus);

    // ✅ FIX: emailRow() — $this-> hataya, direct function call
    $row_name        = emailRow('Full Name', $fullName, '#f9f9f9');
    $row_email       = emailRow('Email Address', $email);
    $row_phone       = emailRow('Phone Number', $phone, '#f9f9f9');
    $row_dob         = emailRow('Date of Birth', $dob);
    $row_gender      = emailRow('Gender', $gender, '#f9f9f9');
    $row_address     = emailRow('Address', $address);
    $row_qual        = emailRow('Qualification', $qualification, '#f9f9f9');
    $row_percent     = emailRow('Percentage / CGPA', $percentage);
    $row_college     = emailRow('College / University', $college, '#f9f9f9');
    $row_year        = emailRow('Year of Passing', $yearOfPassing);
    $row_program     = emailRow('Program', $program, '#f9f9f9');
    $row_exp         = emailRow('Prior Experience', $experience);
    $row_mot         = emailRow('Motivation', $motivation, '#f9f9f9');
    $row_regid       = emailRow('Registration ID', "<strong style='color:#667eea;'>{$registrationId}</strong>", '#f9f9f9');
    $row_amount      = emailRow('Amount Paid', "<strong style='font-size:16px; color:#28a745;'>{$amount}</strong>");
    $row_pmode       = emailRow('Payment Mode', $paymentMode, '#f9f9f9');
    $row_pstatus     = emailRow('Payment Status', "<span style='color:{$paymentStatusColor}; font-weight:bold;'>{$paymentStatusLabel}</span>");

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:#f0f2f5; font-family: Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5; padding: 30px 0;">
  <tr>
    <td align="center">
      <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">

        <!-- HEADER -->
        <tr>
          <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 35px 40px; text-align:center;">
            <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="Logixcode" style="height:55px; margin-bottom:12px;"><br>
            <h1 style="color:#ffffff; margin:0; font-size:24px; letter-spacing:1px;">Registration Confirmed!</h1>
            <p style="color:rgba(255,255,255,0.85); margin:8px 0 0; font-size:14px;">Your journey with Logixcode IT Solution begins here</p>
          </td>
        </tr>

        <!-- SUCCESS BADGE -->
        <tr>
          <td style="background:#f8fff9; padding: 20px 40px; text-align:center; border-bottom: 1px solid #e8f5e9;">
            <span style="display:inline-block; background:#28a745; color:#fff; padding: 8px 24px; border-radius:50px; font-size:13px; font-weight:bold; letter-spacing:1px;">
              &#10003; REGISTRATION ID: {$registrationId}
            </span>
            <p style="color:#555; font-size:13px; margin:10px 0 0;">Registered on: {$date}</p>
          </td>
        </tr>

        <!-- GREETING -->
        <tr>
          <td style="padding: 30px 40px 10px;">
            <p style="font-size:16px; color:#333; margin:0;">Dear <strong>{$fullName}</strong>,</p>
            <p style="font-size:14px; color:#555; margin:12px 0 0; line-height:1.7;">
              Congratulations! Your registration at <strong>Logixcode IT Solution</strong> has been successfully completed.
              Please keep this email safe — it contains all your registration details.
            </p>
          </td>
        </tr>

        <!-- SECTION: Personal Details -->
        <tr>
          <td style="padding: 20px 40px 10px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#667eea; color:#fff; padding:10px 16px; border-radius:6px 6px 0 0; font-size:13px; font-weight:bold; letter-spacing:0.5px;">
                  PERSONAL INFORMATION
                </td>
              </tr>
              <tr>
                <td style="border:1px solid #e0e0e0; border-top:none; border-radius:0 0 6px 6px; padding:0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    {$row_name}
                    {$row_email}
                    {$row_phone}
                    {$row_dob}
                    {$row_gender}
                    {$row_address}
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SECTION: Educational Details -->
        <tr>
          <td style="padding: 20px 40px 10px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#764ba2; color:#fff; padding:10px 16px; border-radius:6px 6px 0 0; font-size:13px; font-weight:bold; letter-spacing:0.5px;">
                  EDUCATIONAL BACKGROUND
                </td>
              </tr>
              <tr>
                <td style="border:1px solid #e0e0e0; border-top:none; border-radius:0 0 6px 6px; padding:0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    {$row_qual}
                    {$row_percent}
                    {$row_college}
                    {$row_year}
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SECTION: Program -->
        <tr>
          <td style="padding: 20px 40px 10px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#17a2b8; color:#fff; padding:10px 16px; border-radius:6px 6px 0 0; font-size:13px; font-weight:bold; letter-spacing:0.5px;">
                  SELECTED PROGRAM
                </td>
              </tr>
              <tr>
                <td style="border:1px solid #e0e0e0; border-top:none; border-radius:0 0 6px 6px; padding:0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    {$row_program}
                    {$row_exp}
                    {$row_mot}
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SECTION: Payment -->
        <tr>
          <td style="padding: 20px 40px 10px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="background:#28a745; color:#fff; padding:10px 16px; border-radius:6px 6px 0 0; font-size:13px; font-weight:bold; letter-spacing:0.5px;">
                  PAYMENT DETAILS
                </td>
              </tr>
              <tr>
                <td style="border:1px solid #e0e0e0; border-top:none; border-radius:0 0 6px 6px; padding:0;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    {$row_regid}
                    {$row_amount}
                    {$row_pmode}
                    {$row_pstatus}
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- IMPORTANT NOTE -->
        <tr>
          <td style="padding: 20px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#fff8e1; border:1px solid #ffe082; border-radius:8px;">
              <tr>
                <td style="padding:16px 20px;">
                  <p style="margin:0; font-size:13px; color:#795548; font-weight:bold;">Important Note:</p>
                  <ul style="margin:8px 0 0; padding-left:18px; color:#555; font-size:13px; line-height:1.8;">
                    <li>Please save your <strong>Registration ID: {$registrationId}</strong> for all future communication.</li>
                    <li>Carry this email or a printout on your first day.</li>
                    <li>For any queries, contact us at <strong>{$institutePhone}</strong> or email <strong>{$instituteEmail}</strong>.</li>
                  </ul>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- FOOTER -->
        <tr>
          <td style="background:#2d2d2d; padding:25px 40px; text-align:center; border-radius:0 0 12px 12px;">
            <p style="color:#fff; font-size:15px; font-weight:bold; margin:0 0 6px;">{$instituteName}</p>
            <p style="color:#aaa; font-size:12px; margin:4px 0;">&#128231; {$instituteEmail} &nbsp;|&nbsp; &#128222; {$institutePhone}</p>
            <p style="color:#aaa; font-size:12px; margin:4px 0;">&#128205; {$instituteAddress}</p>
            <p style="color:#aaa; font-size:12px; margin:4px 0;">&#127760; {$instituteWebsite}</p>
            <p style="color:#666; font-size:11px; margin:16px 0 0; border-top:1px solid #444; padding-top:12px;">
              This is an automated confirmation email. Please do not reply directly to this message.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
HTML;
}

/**
 * Plain Text Email Fallback
 */
function getEmailPlainText($studentData, $registrationId, $paymentStatus) {
    $fullName      = $studentData['firstName'] . ' ' . $studentData['lastName'];
    $program       = $studentData['program'];
    $paymentMode   = $studentData['payment_mode'] ?? 'OFFLINE';
    $amount        = isset($studentData['amount']) ? 'Rs.' . number_format((float)$studentData['amount'], 2) : 'Rs.500.00';
    $dob           = $studentData['dob'];
    $gender        = ucfirst($studentData['gender']);
    $address       = $studentData['address'];
    $qualification = $studentData['qualification'];
    $percentage    = !empty($studentData['percentage']) ? $studentData['percentage'] : 'N/A';
    $college       = !empty($studentData['college']) ? $studentData['college'] : 'N/A';
    $yearOfPassing = !empty($studentData['yearOfPassing']) ? $studentData['yearOfPassing'] : 'N/A';
    $experience    = !empty($studentData['experience']) ? $studentData['experience'] : 'N/A';
    $motivation    = !empty($studentData['motivation']) ? $studentData['motivation'] : 'N/A';
    $date          = date('d M Y, h:i A');

    // ✅ FIX: Constants ko variables mein store karo plain text mein bhi
    $institutePhone   = INSTITUTE_PHONE;
    $instituteEmail   = INSTITUTE_EMAIL;
    $instituteAddress = INSTITUTE_ADDRESS;
    $instituteWebsite = INSTITUTE_WEBSITE;

    return <<<TEXT
LOGIXCODE IT SOLUTION — REGISTRATION CONFIRMATION
==================================================

Dear {$fullName},

Congratulations! Your registration has been successfully completed.

REGISTRATION ID: {$registrationId}
Registered on  : {$date}

--- PERSONAL INFORMATION ---
Full Name    : {$fullName}
Email        : {$studentData['email']}
Phone        : {$studentData['phone']}
Date of Birth: {$dob}
Gender       : {$gender}
Address      : {$address}

--- EDUCATIONAL BACKGROUND ---
Qualification  : {$qualification}
Percentage/CGPA: {$percentage}
College/Univ.  : {$college}
Year of Passing: {$yearOfPassing}

--- SELECTED PROGRAM ---
Program         : {$program}
Prior Experience: {$experience}
Motivation      : {$motivation}

--- PAYMENT DETAILS ---
Amount Paid   : {$amount}
Payment Mode  : {$paymentMode}
Payment Status: {$paymentStatus}

IMPORTANT: Please save your Registration ID ({$registrationId}) for all future communication.

Contact Us:
Email  : {$instituteEmail}
Phone  : {$institutePhone}
Address: {$instituteAddress}
Website: {$instituteWebsite}

This is an automated email. Please do not reply to this message.
TEXT;
}

/**
 * Send student login credentials and ID card email
 */
function sendCredentialEmail($studentData, $registrationId, $idCardPath = null) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($studentData['email'], $studentData['firstName'] . ' ' . $studentData['lastName']);
        $mail->addReplyTo(INSTITUTE_EMAIL, INSTITUTE_NAME);

        // BCC - Admin
        $mail->addBCC(BCC_EMAIL, BCC_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = '🔐 Your Student Portal Access | ' . INSTITUTE_NAME;
        
        // Use a specific template for credentials
        $mail->Body    = getCredentialTemplate($studentData, $registrationId);
        $mail->AltBody = "Hello {$studentData['firstName']}, your student portal is ready. \nStudent ID: {$studentData['student_id']}\nPassword: {$studentData['raw_password']}\nPlease login at " . INSTITUTE_WEBSITE . "/student/login.php";

        // Attach ID Card if provided
        if ($idCardPath && file_exists($idCardPath)) {
            $mail->addAttachment($idCardPath, 'Student_ID_Card.pdf');
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Credential Email Error: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * HTML Template for Credentials
 */
function getCredentialTemplate($studentData, $registrationId) {
    $fullName      = htmlspecialchars($studentData['firstName'] . ' ' . $studentData['lastName']);
    $studentId     = htmlspecialchars($studentData['student_id']);
    $rawPassword   = htmlspecialchars($studentData['raw_password']);
    $instituteName = INSTITUTE_NAME;
    $institutePhone= INSTITUTE_PHONE;
    $instituteEmail= INSTITUTE_EMAIL;
    $loginUrl      = INSTITUTE_WEBSITE . '/student/login.php';

    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Portal Access</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f7f6; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background:#2563eb; padding: 30px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px;">Student Portal Access</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size:16px; color:#333;">Dear <strong>{$fullName}</strong>,</p>
                            <p style="font-size:14px; color:#555; line-height:1.6;">
                                Your student account has been created for the <strong>{$instituteName}</strong> portal. 
                                You can now log in to access your dashboard, academic records, and download your resources.
                            </p>
                            
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:20px; margin:25px 0;">
                                <p style="margin:0 0 10px 0; font-weight:bold; color:#1e293b;">Your Login Credentials:</p>
                                <p style="margin:5px 0; font-size:14px;"><strong>Student ID:</strong> <span style="color:#2563eb;">{$studentId}</span></p>
                                <p style="margin:5px 0; font-size:14px;"><strong>Password:</strong> <span style="color:#2563eb;">{$rawPassword}</span></p>
                            </div>
                            
                            <p style="text-align:center; margin-top:30px;">
                                <a href="{$loginUrl}" style="background:#2563eb; color:#ffffff; padding:12px 30px; text-decoration:none; border-radius:5px; font-weight:bold; display:inline-block;">Login to Student Portal</a>
                            </p>
                            
                            <p style="font-size:12px; color:#94a3b8; margin-top:30px; border-top:1px solid #e2e8f0; padding-top:15px;">
                                * If you have any trouble logging in, please contact the administration at {$institutePhone} or email {$instituteEmail}.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f1f5f9; padding:20px; text-align:center; color:#64748b; font-size:12px;">
                            &copy; 2026 {$instituteName}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}