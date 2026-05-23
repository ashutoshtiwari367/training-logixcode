<?php
/**
 * student-enquiry.php
 * Public form – student fills personal details only (no payment).
 * Admin can view and edit from dashboard.
 */

session_start();
require_once __DIR__ . '/config/db.php';

$success = false;
$error   = '';

/* ── Programs list ── */
$programs = [
    'Full Stack Web Development',
    'Python Programming',
    'Data Science & AI',
    'Digital Marketing',
    'Graphic Designing',
    'Cyber Security',
    'Cloud Computing',
    'Mobile App Development',
];

/* ── Handle form submission ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $required = ['firstName','lastName','email','phone','dob','gender','address','qualification','program'];
        foreach ($required as $f) {
            if (empty(trim($_POST[$f] ?? '')))
                throw new Exception("Required field missing: $f");
        }

        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        if (!$email) throw new Exception('Invalid email address.');

        $phone = preg_replace('/\D/', '', trim($_POST['phone']));
        if (strlen($phone) === 10) $phone = '+91' . $phone;
        elseif (strlen($phone) === 12 && substr($phone, 0, 2) === '91') $phone = '+' . $phone;
        if (!preg_match('/^\+91[0-9]{10}$/', $phone))
            throw new Exception('Invalid phone number. Use 10-digit mobile number.');

        // Check for duplicate email
        $chk = $pdo->prepare("SELECT id FROM registrations WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) throw new Exception('This email is already registered. Contact the institute.');

        // Generate registration ID
        $year  = date('Y');
        $count = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
        $registrationId = 'REG-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $pdo->prepare("INSERT INTO registrations (
            registration_id, first_name, last_name, email, phone, dob, gender,
            address, qualification, percentage, college, year_of_passing,
            program, experience, motivation, updates_opt_in, payment_mode, created_at
        ) VALUES (
            :rid, :fn, :ln, :email, :phone, :dob, :gender,
            :addr, :qual, :pct, :col, :yop,
            :prog, :exp, :mot, :upd, 'OFFLINE', NOW()
        )")->execute([
            ':rid'   => $registrationId,
            ':fn'    => htmlspecialchars(trim($_POST['firstName'])),
            ':ln'    => htmlspecialchars(trim($_POST['lastName'])),
            ':email' => $email,
            ':phone' => $phone,
            ':dob'   => $_POST['dob'],
            ':gender'=> $_POST['gender'],
            ':addr'  => htmlspecialchars(trim($_POST['address'])),
            ':qual'  => htmlspecialchars(trim($_POST['qualification'])),
            ':pct'   => htmlspecialchars(trim($_POST['percentage'] ?? '')),
            ':col'   => !empty($_POST['college']) ? htmlspecialchars(trim($_POST['college'])) : null,
            ':yop'   => !empty($_POST['yearOfPassing']) ? trim($_POST['yearOfPassing']) : null,
            ':prog'  => htmlspecialchars(trim($_POST['program'])),
            ':exp'   => !empty($_POST['experience']) ? htmlspecialchars(trim($_POST['experience'])) : null,
            ':mot'   => !empty($_POST['motivation']) ? htmlspecialchars(trim($_POST['motivation'])) : null,
            ':upd'   => isset($_POST['updates']) ? 1 : 0,
        ]);

        $success = true;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Student Enquiry Form – LogixCode IT Solution</title>
<meta name="description" content="Fill your details to enrol at LogixCode IT Solution. Our team will contact you shortly."/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --brand:      #7c3aed;
    --brand-dark: #5b21b6;
    --brand-light:#ede9fe;
    --success:    #059669;
    --error:      #dc2626;
    --text:       #1e293b;
    --muted:      #64748b;
    --border:     #e2e8f0;
    --bg:         #f1f5f9;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 40px 16px;
    color: var(--text);
  }

  .container {
    max-width: 760px;
    margin: 0 auto;
  }

  /* ── Header ── */
  .header {
    text-align: center;
    margin-bottom: 32px;
    color: #fff;
  }
  .header .logo {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.5px;
  }
  .header .logo span { color: #c4b5fd; }
  .header p {
    margin-top: 6px;
    font-size: 15px;
    opacity: .85;
  }

  /* ── Card ── */
  .card {
    background: #fff;
    border-radius: 20px;
    padding: 40px 40px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.18);
  }

  /* ── Success ── */
  .success-box {
    text-align: center;
    padding: 48px 24px;
  }
  .success-icon {
    width: 80px; height: 80px;
    background: #d1fae5;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    font-size: 36px;
  }
  .success-box h2 { font-size: 24px; font-weight: 700; color: var(--success); margin-bottom: 10px; }
  .success-box p  { color: var(--muted); font-size: 15px; line-height: 1.7; }

  /* ── Section title ── */
  .section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: var(--brand);
    margin: 28px 0 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--brand-light);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-title:first-of-type { margin-top: 0; }

  /* ── Grid ── */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
  .span-2 { grid-column: span 2; }

  /* ── Form fields ── */
  .field { display: flex; flex-direction: column; gap: 6px; }
  label  { font-size: 13px; font-weight: 600; color: var(--text); }
  label .req { color: var(--error); margin-left: 2px; }

  input, select, textarea {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    color: var(--text);
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(124,58,237,.12);
  }
  textarea { resize: vertical; min-height: 80px; }

  /* ── Radio ── */
  .radio-group { display: flex; gap: 20px; flex-wrap: wrap; padding-top: 4px; }
  .radio-group label { display: flex; align-items: center; gap: 6px; font-weight: 500; cursor: pointer; }
  .radio-group input { width: auto; }

  /* ── Checkbox ── */
  .check-label {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: 13px; color: var(--muted);
    cursor: pointer;
  }
  .check-label input { width: 16px; height: 16px; margin-top: 2px; cursor: pointer; }

  /* ── Error ── */
  .alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: var(--error);
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 20px;
  }

  /* ── Submit ── */
  .btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--brand), var(--brand-dark));
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 28px;
    transition: transform .15s, box-shadow .15s;
    letter-spacing: .3px;
  }
  .btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(124,58,237,.35);
  }
  .btn-submit:active { transform: translateY(0); }

  /* ── Footer ── */
  .footer { text-align: center; color: rgba(255,255,255,.6); font-size: 13px; margin-top: 20px; }

  @media (max-width: 600px) {
    .card       { padding: 24px 20px; }
    .grid-2, .grid-3 { grid-template-columns: 1fr; }
    .span-2     { grid-column: span 1; }
  }
</style>
</head>
<body>

<div class="container">

  <!-- Header -->
  <div class="header">
    <div class="logo">Logix<span>Code</span> IT Solution</div>
    <p>Fill the form below — our team will contact you shortly 🚀</p>
  </div>

  <div class="card">

    <?php if ($success): ?>
    <!-- Success State -->
    <div class="success-box">
      <div class="success-icon">✅</div>
      <h2>Form Submitted Successfully!</h2>
      <p>
        Thank you for your interest in LogixCode IT Solution.<br>
        Our team will contact you within <strong>24 hours</strong> to guide you further.<br><br>
        <strong>What's next?</strong><br>
        You will receive a call from our counsellor to discuss the course and next steps.
      </p>
    </div>

    <?php else: ?>

    <!-- Form title -->
    <h1 style="font-size:22px;font-weight:800;color:var(--text);margin-bottom:4px;">Student Enquiry Form</h1>
    <p style="color:var(--muted);font-size:14px;margin-bottom:24px;">All fields marked <span style="color:var(--error)">*</span> are required</p>

    <?php if ($error): ?>
    <div class="alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="enquiryForm" autocomplete="on">

      <!-- ── Personal Details ── -->
      <div class="section-title">👤 Personal Details</div>
      <div class="grid-2">
        <div class="field">
          <label>First Name <span class="req">*</span></label>
          <input type="text" name="firstName" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" placeholder="e.g. Rahul" required/>
        </div>
        <div class="field">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" name="lastName" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" placeholder="e.g. Sharma" required/>
        </div>
        <div class="field">
          <label>Email Address <span class="req">*</span></label>
          <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="rahul@email.com" required/>
        </div>
        <div class="field">
          <label>Mobile Number <span class="req">*</span></label>
          <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="9876543210" maxlength="10" required/>
        </div>
        <div class="field">
          <label>Date of Birth <span class="req">*</span></label>
          <input type="date" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required/>
        </div>
        <div class="field">
          <label>Gender <span class="req">*</span></label>
          <select name="gender" required>
            <option value="">-- Select --</option>
            <option value="male"   <?= ($_POST['gender'] ?? '') === 'male'   ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other"  <?= ($_POST['gender'] ?? '') === 'other'  ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
        <div class="field span-2">
          <label>Address <span class="req">*</span></label>
          <textarea name="address" placeholder="Full address..." required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- ── Educational Details ── -->
      <div class="section-title">🎓 Educational Details</div>
      <div class="grid-3">
        <div class="field">
          <label>Qualification <span class="req">*</span></label>
          <select name="qualification" required>
            <option value="">-- Select --</option>
            <?php foreach (['10th','12th','Diploma','B.Tech/B.E.','BCA','MCA','MBA','Other'] as $q): ?>
            <option value="<?= $q ?>" <?= ($_POST['qualification'] ?? '') === $q ? 'selected' : '' ?>><?= $q ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Percentage / CGPA</label>
          <input type="text" name="percentage" value="<?= htmlspecialchars($_POST['percentage'] ?? '') ?>" placeholder="e.g. 75% or 7.5 CGPA"/>
        </div>
        <div class="field">
          <label>Year of Passing</label>
          <select name="yearOfPassing">
            <option value="">-- Select --</option>
            <?php for ($y = date('Y'); $y >= 2010; $y--): ?>
            <option value="<?= $y ?>" <?= ($_POST['yearOfPassing'] ?? '') == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="field span-2">
          <label>College / School Name</label>
          <input type="text" name="college" value="<?= htmlspecialchars($_POST['college'] ?? '') ?>" placeholder="e.g. Delhi University"/>
        </div>
      </div>

      <!-- ── Course Details ── -->
      <div class="section-title">📚 Course Details</div>
      <div class="grid-2">
        <div class="field span-2">
          <label>Select Program <span class="req">*</span></label>
          <select name="program" required>
            <option value="">-- Choose a Program --</option>
            <?php foreach ($programs as $prog): ?>
            <option value="<?= htmlspecialchars($prog) ?>" <?= ($_POST['program'] ?? '') === $prog ? 'selected' : '' ?>><?= htmlspecialchars($prog) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field span-2">
          <label>Work Experience (if any)</label>
          <textarea name="experience" placeholder="Briefly describe any relevant work experience..."><?= htmlspecialchars($_POST['experience'] ?? '') ?></textarea>
        </div>
        <div class="field span-2">
          <label>Why do you want to join? <small style="color:var(--muted);font-weight:400">(optional)</small></label>
          <textarea name="motivation" placeholder="Tell us what motivated you to join this program..."><?= htmlspecialchars($_POST['motivation'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- ── Consent ── -->
      <div style="margin-top:20px;">
        <label class="check-label">
          <input type="checkbox" name="updates" <?= isset($_POST['updates']) ? 'checked' : 'checked' ?>>
          I agree to receive updates about courses and offers from LogixCode IT Solution via WhatsApp / SMS / Email.
        </label>
      </div>

      <button type="submit" class="btn-submit">
        📩 Submit Enquiry Form
      </button>

    </form>
    <?php endif; ?>

  </div><!-- .card -->

  <div class="footer">© 2026 LogixCode IT Solution • All Rights Reserved</div>

</div><!-- .container -->

</body>
</html>
