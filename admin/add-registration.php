<?php
/**
 * Add Offline Registration (Office Staff)
 * admin/add-registration.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/mail.php';

requireLogin();

$currentUser = getCurrentUser();
$success = '';
$error   = '';
$registrationData = null;

/* ── Amount to Words helper ── */
function amountToWords(float $n): string {
    $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
             'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
             'Seventeen','Eighteen','Nineteen'];
    $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
    $num  = (int)$n;
    if ($num === 0) return 'Zero';
    $w = '';
    if ($num >= 100000) { $w .= amountToWords((int)($num/100000)).' Lakh ';    $num %= 100000; }
    if ($num >= 1000)   { $w .= amountToWords((int)($num/1000)).' Thousand ';  $num %= 1000; }
    if ($num >= 100)    { $w .= $ones[(int)($num/100)].' Hundred ';             $num %= 100; }
    if ($num >= 20)     { $w .= $tens[(int)($num/10)].' ';                      $num %= 10; }
    if ($num > 0)       { $w .= $ones[$num].' '; }
    return trim($w);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!validateCSRF($_POST['csrf_token']))
            throw new Exception('Invalid security token');

        foreach (['firstName','lastName','email','phone','dob','gender','address','qualification','program'] as $f)
            if (empty(trim($_POST[$f] ?? '')))
                throw new Exception("Required field missing: $f");

        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        if (!$email) throw new Exception('Invalid email address');

        $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) throw new Exception('Email already registered');

        $phone = trim($_POST['phone']);
        if (!preg_match('/^\+91[0-9]{10}$/', $phone))
            throw new Exception('Invalid phone (format: +91XXXXXXXXXX)');

        $amount = (isset($_POST['amount']) && is_numeric($_POST['amount']) && (float)$_POST['amount'] >= 0)
            ? (float)$_POST['amount'] : 500.00;

        $fd = [
            'firstName'    => sanitizeInput($_POST['firstName']),
            'lastName'     => sanitizeInput($_POST['lastName']),
            'email'        => $email,
            'phone'        => $phone,
            'dob'          => $_POST['dob'],
            'gender'       => $_POST['gender'],
            'address'      => sanitizeInput($_POST['address']),
            'qualification'=> sanitizeInput($_POST['qualification']),
            'percentage'   => sanitizeInput($_POST['percentage'] ?? ''),
            'college'      => !empty($_POST['college'])       ? sanitizeInput($_POST['college'])       : null,
            'yearOfPassing'=> !empty($_POST['yearOfPassing']) ? sanitizeInput($_POST['yearOfPassing']) : null,
            'program'      => sanitizeInput($_POST['program']),
            'experience'   => !empty($_POST['experience'])    ? sanitizeInput($_POST['experience'])    : null,
            'motivation'   => !empty($_POST['motivation'])    ? sanitizeInput($_POST['motivation'])    : null,
            'updates'      => isset($_POST['updates']) ? 1 : 0,
        ];

        $registrationId = generateRegistrationId();

        $pdo->prepare("INSERT INTO registrations (
            registration_id,first_name,last_name,email,phone,dob,gender,
            address,qualification,percentage,college,year_of_passing,
            program,experience,motivation,updates_opt_in,payment_mode,created_at
        ) VALUES (
            :rid,:fn,:ln,:email,:phone,:dob,:gender,
            :addr,:qual,:pct,:col,:yop,
            :prog,:exp,:mot,:upd,'OFFLINE',NOW()
        )")->execute([
            ':rid'=>$registrationId, ':fn'=>$fd['firstName'],  ':ln'=>$fd['lastName'],
            ':email'=>$fd['email'],  ':phone'=>$fd['phone'],   ':dob'=>$fd['dob'],
            ':gender'=>$fd['gender'],':addr'=>$fd['address'],  ':qual'=>$fd['qualification'],
            ':pct'=>$fd['percentage'],':col'=>$fd['college'],  ':yop'=>$fd['yearOfPassing'],
            ':prog'=>$fd['program'], ':exp'=>$fd['experience'],':mot'=>$fd['motivation'],
            ':upd'=>$fd['updates'],
        ]);

        $pdo->prepare("INSERT INTO payments (
            registration_id,payment_gateway_id,amount,currency,status,created_at
        ) VALUES (:rid,:pgid,:amt,'INR','OFFLINE',NOW())")->execute([
            ':rid'=>$registrationId, ':pgid'=>'OFFLINE-'.time(), ':amt'=>$amount,
        ]);

        sendConfirmationEmail(
            array_merge($fd, [
                'registration_id'=>$registrationId,
                'payment_mode'=>'OFFLINE',
                'amount'=>$amount
            ]),
            $registrationId, 'OFFLINE'
        );

        $registrationData = array_merge($fd, [
            'registration_id' => $registrationId,
            'amount'          => $amount,
            'amount_words'    => amountToWords($amount),
            'payment_mode'    => 'OFFLINE',
            'registered_at'   => date('d M Y, h:i A'),
        ]);
        $_POST = [];

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$csrfToken      = generateCSRF();
$qualifications = ['10th','12th','Diploma','B.Tech / B.E.','BCA','B.Sc','MCA','M.Tech','M.Sc','Other'];
$trainingProgs  = ['Summer Training (45-60 Days)','Winter Training (45 Days)','Industrial Training (6 Months)','Apprenticeship Program (3-6 Months)'];
$techCourses    = ['Full Stack Development','Java Programming','Python Development','MERN Stack Development','Android Development','Data Science & Analytics','AI & Machine Learning','Frontend Development','Cloud Computing (AWS)'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Offline Registration — Logixcode</title>
<link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root {
    --ink:     #0f172a;
    --ink2:    #334155;
    --muted:   #64748b;
    --border:  #e2e8f0;
    --surf:    #ffffff;
    --bg:      #f1f5f9;
    --accent:  #2563eb;
    --accent2: #1d4ed8;
    --green:   #16a34a;
    --red:     #dc2626;
    --radius:  12px;
    --shadow:  0 1px 3px rgba(0,0,0,.06),0 4px 18px rgba(0,0,0,.08);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh}

/* topbar */
.topbar{background:var(--surf);border-bottom:1px solid var(--border);padding:13px 24px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.brand{display:flex;align-items:center;gap:9px;font-weight:700;font-size:1rem;color:var(--ink);text-decoration:none}
.brand img{height:32px}
.btn-back{display:inline-flex;align-items:center;gap:6px;padding:7px 15px;border-radius:8px;border:1px solid var(--border);background:var(--surf);color:var(--ink2);font-size:.82rem;font-weight:500;text-decoration:none;transition:.15s}
.btn-back:hover{background:var(--bg);color:var(--ink)}

/* layout */
.wrap{max-width:840px;margin:0 auto;padding:30px 18px 64px}

/* heading */
.pg-head{margin-bottom:24px}
.pg-head h1{font-size:1.45rem;font-weight:700}
.pg-head p{font-size:.85rem;color:var(--muted);margin-top:3px}

/* card */
.card{background:var(--surf);border-radius:var(--radius);border:1px solid var(--border);box-shadow:var(--shadow);overflow:hidden;margin-bottom:14px}

/* section label */
.slabel{display:flex;align-items:center;gap:7px;padding:15px 22px 12px;border-bottom:1px solid var(--border);font-weight:600;font-size:.73rem;letter-spacing:.07em;text-transform:uppercase;color:var(--muted)}
.slabel i{font-size:.95rem;color:var(--accent)}

/* grid */
.fgrid{display:grid;grid-template-columns:1fr 1fr;gap:0}
.fc{padding:15px 22px;border-bottom:1px solid var(--border);border-right:1px solid var(--border)}
.fc:nth-child(even){border-right:none}
.fc.full{grid-column:1/-1;border-right:none}
.fc.last-row,.fc.full:last-child{border-bottom:none}

label{display:block;font-size:.74rem;font-weight:600;color:var(--ink2);margin-bottom:5px;letter-spacing:.02em}
label .req{color:var(--red);margin-left:2px}
.form-control,.form-select{width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:.875rem;color:var(--ink);background:var(--surf);outline:none;transition:border-color .15s,box-shadow .15s}
.form-control:focus,.form-select:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(37,99,235,.11)}
textarea.form-control{resize:vertical;min-height:76px}

/* amount */
.amtwrap{position:relative}
.amtwrap .sym{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-weight:600;color:var(--muted);pointer-events:none;font-size:.88rem}
.amtwrap input{padding-left:24px;font-family:'DM Mono',monospace}
.amt-preview{margin-top:8px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:10px}
.amt-preview .big{font-family:'DM Mono',monospace;font-size:1.35rem;font-weight:700;color:var(--green)}
.amt-preview .lbl{font-size:.7rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em}

/* checkbox */
.chkrow{display:flex;align-items:center;gap:9px}
.chkrow input[type=checkbox]{width:15px;height:15px;accent-color:var(--accent);cursor:pointer;flex-shrink:0}
.chkrow span{font-size:.875rem;color:var(--ink2)}

/* submit bar */
.subbar{padding:18px 22px;background:var(--bg);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:10px 26px;background:var(--accent);color:#fff;border:none;border-radius:9px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;transition:background .15s,transform .1s}
.btn-save:hover{background:var(--accent2)}
.btn-save:active{transform:scale(.98)}

/* alerts */
.alert{display:flex;gap:11px;padding:13px 18px;border-radius:var(--radius);font-size:.875rem;margin-bottom:14px;border:1px solid transparent}
.alert i{font-size:1.05rem;flex-shrink:0;margin-top:1px}
.alert-success{background:#f0fdf4;border-color:#bbf7d0;color:#166534}
.alert-danger {background:#fef2f2;border-color:#fecaca;color:#991b1b}
.alert-info   {background:#eff6ff;border-color:#bfdbfe;color:#1e40af}

/* receipt card */
.rcpt{background:var(--surf);border-radius:var(--radius);border:1px solid var(--border);box-shadow:var(--shadow);overflow:hidden;margin-bottom:18px}
.rcpt-head{background:linear-gradient(120deg,#1e3a8a 0%,#2563eb 100%);color:#fff;padding:22px 26px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
.rcpt-head h3{font-size:1.05rem;font-weight:700;margin-bottom:2px}
.rcpt-head p {font-size:.78rem;opacity:.8}
.rid-badge{font-family:'DM Mono',monospace;font-size:.95rem;font-weight:600;background:rgba(255,255,255,.15);padding:6px 15px;border-radius:100px;letter-spacing:.04em;white-space:nowrap}

.rrow{display:flex;border-bottom:1px solid var(--border)}
.rrow:last-child{border-bottom:none}
.rk{width:165px;flex-shrink:0;padding:11px 18px;font-size:.72rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.04em;background:#fafafa;border-right:1px solid var(--border);display:flex;align-items:center}
.rv{padding:11px 18px;font-size:.875rem;color:var(--ink);flex:1;display:flex;align-items:center;gap:7px}
.rv.green{font-family:'DM Mono',monospace;font-size:1.05rem;font-weight:700;color:var(--green)}
.badge-off{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#f1f5f9;border:1px solid var(--border);border-radius:100px;font-size:.73rem;font-weight:600;color:var(--ink2)}
.badge-ok {display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:100px;font-size:.73rem;font-weight:700;color:var(--green)}

.rcpt-foot{padding:15px 22px;background:#fafafa;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.rcpt-foot p{font-size:.75rem;color:var(--muted)}
.btn-pdf{display:inline-flex;align-items:center;gap:7px;padding:9px 20px;background:var(--red);color:#fff;border:none;border-radius:9px;font-family:inherit;font-size:.85rem;font-weight:600;cursor:pointer;transition:background .15s}
.btn-pdf:hover{background:#b91c1c}
.btn-pdf:disabled{opacity:.6;cursor:not-allowed}

@media(max-width:620px){
    .fgrid{grid-template-columns:1fr}
    .fc,.fc:nth-child(even){border-right:none}
    .rk{width:120px}
    .rcpt-head{flex-direction:column;align-items:flex-start}
}
</style>
</head>
<body>

<nav class="topbar">
    <a href="#" class="brand">
        <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="Logixcode IT Solution">
        Logixcode IT Solution
    </a>
    <a href="dashboard.php" class="btn-back"><i class="bi bi-arrow-left"></i> Dashboard</a>
</nav>

<div class="wrap">

    <div class="pg-head">
        <h1>Offline Registration</h1>
        <p>Cash / in-person — enter student details and collect payment manually.</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <?php if ($registrationData): ?>
    <!-- SUCCESS -->
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div>
            <strong>Registration saved!</strong> &nbsp;ID: <code><?= htmlspecialchars($registrationData['registration_id']) ?></code>
            — Confirmation email sent to <em><?= htmlspecialchars($registrationData['email']) ?></em>.
        </div>
    </div>

    <!-- Receipt summary -->
    <div class="rcpt">
        <div class="rcpt-head">
            <div>
                <h3><?= htmlspecialchars($registrationData['firstName'].' '.$registrationData['lastName']) ?></h3>
                <p><?= htmlspecialchars($registrationData['program']) ?> &nbsp;&middot;&nbsp; <?= htmlspecialchars($registrationData['registered_at']) ?></p>
            </div>
            <div class="rid-badge"><?= htmlspecialchars($registrationData['registration_id']) ?></div>
        </div>

        <div class="rrow"><div class="rk">Email</div>   <div class="rv"><?= htmlspecialchars($registrationData['email']) ?></div></div>
        <div class="rrow"><div class="rk">Phone</div>   <div class="rv"><?= htmlspecialchars($registrationData['phone']) ?></div></div>
        <div class="rrow"><div class="rk">DOB</div>     <div class="rv"><?= htmlspecialchars($registrationData['dob']) ?></div></div>
        <div class="rrow"><div class="rk">Gender</div>  <div class="rv"><?= htmlspecialchars(ucfirst($registrationData['gender'])) ?></div></div>
        <div class="rrow"><div class="rk">Address</div> <div class="rv"><?= htmlspecialchars($registrationData['address']) ?></div></div>
        <div class="rrow"><div class="rk">Qualification</div><div class="rv"><?= htmlspecialchars($registrationData['qualification']) ?></div></div>
        <div class="rrow"><div class="rk">College</div> <div class="rv"><?= htmlspecialchars($registrationData['college'] ?: '—') ?></div></div>
        <div class="rrow">
            <div class="rk">Amount Paid</div>
            <div class="rv green">
                &#8377;<?= number_format((float)$registrationData['amount'], 2) ?>
                <span style="font-size:.72rem;font-weight:400;color:var(--muted);font-family:'DM Sans',sans-serif;">
                    (<?= strtoupper($registrationData['amount_words']) ?> RUPEES ONLY)
                </span>
            </div>
        </div>
        <div class="rrow">
            <div class="rk">Payment Mode</div>
            <div class="rv"><span class="badge-off"><i class="bi bi-cash-coin"></i> OFFLINE / Cash</span></div>
        </div>
        <div class="rrow">
            <div class="rk">Status</div>
            <div class="rv"><span class="badge-ok"><i class="bi bi-check-circle-fill"></i> CONFIRMED</span></div>
        </div>

        <div class="rcpt-foot">
            <p>Logixcode IT Solution &nbsp;&middot;&nbsp; training.logixcode.com &nbsp;&middot;&nbsp; +91-8467898854</p>
            <button class="btn-pdf" id="pdfBtn" onclick="makePDF()">
                <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
            </button>
        </div>
    </div>

    <hr style="border:none;border-top:1px solid var(--border);margin:22px 0 20px">
    <p style="font-size:.85rem;color:var(--muted);margin-bottom:16px;"><i class="bi bi-plus-circle"></i> Register another student below:</p>
    <?php endif; ?>

    <!-- ── FORM ── -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        <span>Offline / cash registration — no online payment required.</span>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <!-- Personal -->
        <div class="card">
            <div class="slabel"><i class="bi bi-person-vcard"></i> Personal Information</div>
            <div class="fgrid">
                <div class="fc">
                    <label>First Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="firstName" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" required>
                </div>
                <div class="fc">
                    <label>Last Name <span class="req">*</span></label>
                    <input type="text" class="form-control" name="lastName" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" required>
                </div>
                <div class="fc">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="fc">
                    <label>Phone <span class="req">*</span> <span style="font-weight:400;color:var(--muted)">(+91XXXXXXXXXX)</span></label>
                    <input type="tel" class="form-control" name="phone" pattern="^\+91[0-9]{10}$" placeholder="+91XXXXXXXXXX" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                </div>
                <div class="fc">
                    <label>Date of Birth <span class="req">*</span></label>
                    <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required>
                </div>
                <div class="fc">
                    <label>Gender <span class="req">*</span></label>
                    <select class="form-select" name="gender" required>
                        <option value="">Select</option>
                        <?php foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $v=>$l): ?>
                        <option value="<?=$v?>" <?=(($_POST['gender']??'')===$v?'selected':'')?>><?=$l?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fc full last-row">
                    <label>Address <span class="req">*</span></label>
                    <textarea class="form-control" name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Education -->
        <div class="card">
            <div class="slabel"><i class="bi bi-mortarboard"></i> Educational Background</div>
            <div class="fgrid">
                <div class="fc">
                    <label>Qualification <span class="req">*</span></label>
                    <select class="form-select" name="qualification" required>
                        <option value="">Select</option>
                        <?php foreach($qualifications as $q): ?>
                        <option value="<?=$q?>" <?=(($_POST['qualification']??'')===$q?'selected':'')?>><?=$q?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fc">
                    <label>Percentage / CGPA</label>
                    <input type="text" class="form-control" name="percentage" value="<?= htmlspecialchars($_POST['percentage'] ?? '') ?>">
                </div>
                <div class="fc last-row">
                    <label>College / University</label>
                    <input type="text" class="form-control" name="college" value="<?= htmlspecialchars($_POST['college'] ?? '') ?>">
                </div>
                <div class="fc last-row">
                    <label>Year of Passing</label>
                    <input type="text" class="form-control" name="yearOfPassing" pattern="^[0-9]{4}$" placeholder="YYYY" value="<?= htmlspecialchars($_POST['yearOfPassing'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Program -->
        <div class="card">
            <div class="slabel"><i class="bi bi-laptop"></i> Program Selection</div>
            <div class="fgrid">
                <div class="fc full last-row">
                    <label>Select Program <span class="req">*</span></label>
                    <select class="form-select" name="program" required>
                        <option value="">Select Program</option>
                        <optgroup label="── Training Programs">
                            <?php foreach($trainingProgs as $p): ?>
                            <option value="<?=$p?>" <?=(($_POST['program']??'')===$p?'selected':'')?>><?=$p?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="── Technology Courses">
                            <?php foreach($techCourses as $p): ?>
                            <option value="<?=$p?>" <?=(($_POST['program']??'')===$p?'selected':'')?>><?=htmlspecialchars($p)?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payment -->
        <div class="card">
            <div class="slabel"><i class="bi bi-cash-stack"></i> Payment Details</div>
            <div class="fgrid">
                <div class="fc last-row">
                    <label>Registration Fee (&#8377;) <span class="req">*</span></label>
                    <div class="amtwrap">
                        <span class="sym">&#8377;</span>
                        <input type="number" class="form-control" name="amount" id="amountInput"
                               value="<?= htmlspecialchars($_POST['amount'] ?? '500') ?>" min="0" step="1" required>
                    </div>
                    <div class="amt-preview">
                        <div>
                            <div class="lbl">Amount to Collect</div>
                            <div class="big" id="amtBig">&#8377;<?= number_format((float)($_POST['amount'] ?? 500),2) ?></div>
                        </div>
                    </div>
                </div>
                <div class="fc last-row" style="display:flex;align-items:center;justify-content:center">
                    <div style="text-align:center">
                        <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;font-weight:600;margin-bottom:8px">Payment Mode</div>
                        <span class="badge-off" style="font-size:.88rem;padding:8px 18px"><i class="bi bi-cash-coin"></i> &nbsp;OFFLINE / Cash</span>
                        <div style="font-size:.75rem;color:var(--muted);margin-top:8px">No online payment needed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional -->
        <div class="card">
            <div class="slabel"><i class="bi bi-chat-text"></i> Additional Information</div>
            <div class="fgrid">
                <div class="fc">
                    <label>Prior Experience</label>
                    <textarea class="form-control" name="experience"><?= htmlspecialchars($_POST['experience'] ?? '') ?></textarea>
                </div>
                <div class="fc">
                    <label>Motivation</label>
                    <textarea class="form-control" name="motivation"><?= htmlspecialchars($_POST['motivation'] ?? '') ?></textarea>
                </div>
                <div class="fc full last-row">
                    <div class="chkrow">
                        <input type="checkbox" name="updates" id="updates" <?= isset($_POST['updates']) ? 'checked' : '' ?>>
                        <span>Send email updates and notifications to student</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="card">
            <div class="subbar">
                <span style="font-size:.78rem;color:var(--muted)"><span class="req">*</span> Required fields</span>
                <button type="submit" class="btn-save">
                    <i class="bi bi-save2-fill"></i> Save Registration
                </button>
            </div>
        </div>
    </form>

</div><!-- /wrap -->

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ── live amount ── */
const aInput = document.getElementById('amountInput');
const aBig   = document.getElementById('amtBig');
if (aInput) {
    aInput.addEventListener('input', () => {
        const v = parseFloat(aInput.value);
        aBig.textContent = (isNaN(v)||v<0) ? '₹0.00' : '₹'+v.toFixed(2);
    });
}

<?php if ($registrationData): ?>
/* ── Direct PDF (jsPDF) — no print dialog ── */
function makePDF() {
    const btn = document.getElementById('pdfBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Generating...';

    setTimeout(() => {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit:'mm', format:'a4' });
            const W   = 210, P = 18;
            let   Y   = 0;

            const BLUE  = [37,99,235],  DKBLUE = [30,58,138];
            const GREEN = [22,163,74],  INK    = [15,23,42];
            const MUTED = [100,116,139],LGRAY  = [241,245,249];
            const BORDER= [226,232,240],WHITE  = [255,255,255];

            /* header */
            doc.setFillColor(...DKBLUE); doc.rect(0,0,W/2,44,'F');
            doc.setFillColor(...BLUE);   doc.rect(W/2,0,W/2,44,'F');

            doc.setFont('helvetica','bold'); doc.setFontSize(17);
            doc.setTextColor(...WHITE);
            doc.text('Logixcode IT Solution', P, 16);

            doc.setFont('helvetica','normal'); doc.setFontSize(7.5);
            doc.setTextColor(200,215,255);
            doc.text('2/1 HIG Swarn Jayanti Vihar, Koyla Nagar, Kanpur', P, 23);
            doc.text('+91-8467898854  |  info@logixcode.com  |  training.logixcode.com', P, 29);

            /* receipt tag */
            doc.setFillColor(...WHITE);
            doc.roundedRect(W-P-48,7,48,11,2,2,'F');
            doc.setFont('helvetica','bold'); doc.setFontSize(7.5);
            doc.setTextColor(...BLUE);
            doc.text('REGISTRATION RECEIPT', W-P-45, 14);

            Y = 44;

            /* ID band */
            doc.setFillColor(...LGRAY); doc.rect(0,Y,W,13,'F');
            doc.setFont('helvetica','bold'); doc.setFontSize(8.5); doc.setTextColor(...INK);
            doc.text('Registration ID:', P, Y+8.5);
            doc.setFont('courier','bold'); doc.setFontSize(9.5); doc.setTextColor(...BLUE);
            doc.text('<?= addslashes($registrationData['registration_id']) ?>', P+31, Y+8.5);
            doc.setFont('helvetica','normal'); doc.setFontSize(7.5); doc.setTextColor(...MUTED);
            doc.text('Registered on: <?= addslashes($registrationData['registered_at']) ?>', W-P, Y+8.5, {align:'right'});
            Y += 13;

            /* helpers */
            function secHead(t) {
                Y += 5;
                doc.setFillColor(...BLUE); doc.rect(P,Y,W-P*2,7.5,'F');
                doc.setFont('helvetica','bold'); doc.setFontSize(8); doc.setTextColor(...WHITE);
                doc.text(t.toUpperCase(), P+3, Y+5.5);
                Y += 7.5;
            }
            function row(k,v,hi) {
                const RH=8.5;
                doc.setFillColor(...LGRAY); doc.rect(P,Y,54,RH,'F');
                doc.setDrawColor(...BORDER);
                doc.rect(P,Y,54,RH,'S');
                doc.setFont('helvetica','bold'); doc.setFontSize(7.5); doc.setTextColor(...MUTED);
                doc.text(k, P+3, Y+6);

                doc.setFillColor(hi?[240,253,244]:WHITE); doc.rect(P+54,Y,W-P*2-54,RH,'F');
                doc.rect(P+54,Y,W-P*2-54,RH,'S');
                if(hi){doc.setFont('helvetica','bold');doc.setFontSize(9.5);doc.setTextColor(...GREEN);}
                else  {doc.setFont('helvetica','normal');doc.setFontSize(8);doc.setTextColor(...INK);}
                const safe=String(v||'—').substring(0,80);
                doc.text(safe, P+57, Y+6);
                Y += RH;
            }

            /* Personal */
            secHead('Personal Information');
            row('Full Name','<?= addslashes($registrationData['firstName'].' '.$registrationData['lastName']) ?>');
            row('Email',    '<?= addslashes($registrationData['email']) ?>');
            row('Phone',    '<?= addslashes($registrationData['phone']) ?>');
            row('Date of Birth','<?= addslashes($registrationData['dob']) ?>');
            row('Gender',   '<?= addslashes(ucfirst($registrationData['gender'])) ?>');
            row('Address',  '<?= addslashes($registrationData['address']) ?>');

            /* Education */
            secHead('Educational Background');
            row('Qualification','<?= addslashes($registrationData['qualification']) ?>');
            row('Percentage',   '<?= addslashes($registrationData['percentage'] ?: 'N/A') ?>');
            row('College',      '<?= addslashes($registrationData['college'] ?: 'N/A') ?>');
            row('Year of Passing','<?= addslashes($registrationData['yearOfPassing'] ?: 'N/A') ?>');

            /* Program */
            secHead('Program Details');
            row('Program',   '<?= addslashes($registrationData['program']) ?>');
            row('Experience','<?= addslashes($registrationData['experience'] ?: 'N/A') ?>');
            row('Motivation','<?= addslashes($registrationData['motivation'] ?: 'N/A') ?>');

            /* Payment */
            secHead('Payment Details');
            row('Registration ID','<?= addslashes($registrationData['registration_id']) ?>');
            row('Amount Paid',
                '\u20B9<?= number_format((float)$registrationData['amount'],2) ?>  —  <?= strtoupper(addslashes($registrationData['amount_words'])) ?> RUPEES ONLY',
                true);
            row('Payment Mode','OFFLINE / Cash');
            row('Currency',   'INR — Indian Rupee');
            row('Status',     'CONFIRMED \u2714');

            Y += 8;

            /* PAID stamp */
            doc.setDrawColor(...GREEN); doc.setLineWidth(1.3);
            doc.roundedRect(P, Y, 36, 12, 2, 2, 'S');
            doc.setFont('helvetica','bold'); doc.setFontSize(12); doc.setTextColor(...GREEN);
            doc.text('PAID \u2714', P+18, Y+8.5, {align:'center'});

            Y += 20;

            /* footer */
            doc.setDrawColor(...BLUE); doc.setLineWidth(0.4);
            doc.line(P,Y,W-P,Y); Y+=5;
            doc.setFont('helvetica','normal'); doc.setFontSize(7.5); doc.setTextColor(...MUTED);
            doc.text('Official registration receipt — Logixcode IT Solution', W/2, Y, {align:'center'}); Y+=4.5;
            doc.text('training.logixcode.com  |  info@logixcode.com  |  +91-8467898854', W/2, Y, {align:'center'}); Y+=4.5;
            doc.setTextColor(180,180,180);
            doc.text('Computer-generated receipt — no signature required. Please retain for your records.', W/2, Y, {align:'center'});

            /* save directly — no dialog */
            doc.save('Logixcode-Receipt-<?= htmlspecialchars($registrationData['registration_id']) ?>.pdf');

        } catch(e) {
            alert('PDF error: ' + e.message);
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-file-earmark-pdf-fill"></i> Download PDF';
    }, 150);
}
<?php endif; ?>
</script>
</body>
</html>