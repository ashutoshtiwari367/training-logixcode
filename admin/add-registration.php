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

        // Counselor Name logic
        $counselor_name = trim($_POST['counselor_name'] ?? 'Direct / Self');
        if ($counselor_name === 'Other') {
            $counselor_name = trim($_POST['counselor_other'] ?? 'Direct / Self');
            if (empty($counselor_name)) {
                $counselor_name = 'Direct / Self';
            }
        }
        $fd['counselor_name'] = $counselor_name;

        $registrationId = generateRegistrationId();

        $pdo->prepare("INSERT INTO registrations (
            registration_id,first_name,last_name,email,phone,dob,gender,
            address,qualification,percentage,college,year_of_passing,
            program,experience,motivation,updates_opt_in,payment_mode,counselor_name,created_at
        ) VALUES (
            :rid,:fn,:ln,:email,:phone,:dob,:gender,
            :addr,:qual,:pct,:col,:yop,
            :prog,:exp,:mot,:upd,'OFFLINE',:counselor,NOW()
        )")->execute([
            ':rid'=>$registrationId, ':fn'=>$fd['firstName'],  ':ln'=>$fd['lastName'],
            ':email'=>$fd['email'],  ':phone'=>$fd['phone'],   ':dob'=>$fd['dob'],
            ':gender'=>$fd['gender'],':addr'=>$fd['address'],  ':qual'=>$fd['qualification'],
            ':pct'=>$fd['percentage'],':col'=>$fd['college'],  ':yop'=>$fd['yearOfPassing'],
            ':prog'=>$fd['program'], ':exp'=>$fd['experience'],':mot'=>$fd['motivation'],
            ':upd'=>$fd['updates'],
            ':counselor'=>$fd['counselor_name']
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
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Offline Registration - LogixCode Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    body { font-family: 'Public Sans', sans-serif; background-color: #f8fafd; }
</style>
</head>
<body class="flex min-h-screen">

<?php include "sidebar.php" ?>

<main class="flex-1 ml-72 min-h-screen p-8 flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Offline Registration Form</h2>
            <p class="text-slate-500 text-sm">Enter student details and collect registration fees manually</p>
        </div>
        <div class="text-slate-500 text-sm font-medium">Home / New Registration</div>
    </div>

    <div class="max-w-4xl mx-auto w-full bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 p-6 flex items-center justify-between">
            <span class="text-sm font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person_add</span> Student Registration Details
            </span>
            <a href="dashboard.php" class="bg-white border text-slate-500 hover:text-slate-800 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back to Dashboard
            </a>
        </div>

        <div class="p-8">
            <!-- Alerts -->
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($registrationData): ?>
                <!-- SUCCESS -->
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-6 rounded-xl mb-8 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-5xl mb-2 text-emerald-500">check_circle</span>
                    <h3 class="font-bold text-xl mb-1">Registration Saved!</h3>
                    <p class="mb-4">Registration ID: <code class="font-mono bg-emerald-100/50 px-2 py-0.5 rounded text-emerald-800 font-bold"><?= htmlspecialchars($registrationData['registration_id']) ?></code></p>
                    <p class="text-sm text-emerald-600/90 mb-6">Confirmation email sent to <strong><?= htmlspecialchars($registrationData['email']) ?></strong>.</p>
                    
                    <div class="flex gap-4">
                        <button id="pdfBtn" onclick="makePDF()" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-6 py-2.5 rounded-xl transition-all flex items-center gap-2 shadow-md">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF Receipt
                        </button>
                        <a href="add-registration.php" class="bg-white border hover:bg-slate-50 text-slate-700 font-bold px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                            New Registration
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-8">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <!-- 1. Personal Details -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">person</span> 1. Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="firstName" value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="lastName" value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Phone <span class="text-red-500">*</span> <span class="text-xs font-normal text-slate-400">(Format: +91XXXXXXXXXX)</span></label>
                            <input type="tel" name="phone" pattern="^\+91[0-9]{10}$" placeholder="+91XXXXXXXXXX" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Gender <span class="text-red-500">*</span></label>
                            <select name="gender" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                <option value="">Select Gender</option>
                                <?php foreach(['male'=>'Male','female'=>'Female','other'=>'Other'] as $v=>$l): ?>
                                <option value="<?=$v?>" <?=(($_POST['gender']??'')===$v?'selected':'')?>><?=$l?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Address <span class="text-red-500">*</span></label>
                            <textarea name="address" required rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </section>

                <!-- 2. Academic Background -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">school</span> 2. Educational Background
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Qualification <span class="text-red-500">*</span></label>
                            <select name="qualification" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                <option value="">Select Qualification</option>
                                <?php foreach($qualifications as $q): ?>
                                <option value="<?=$q?>" <?=(($_POST['qualification']??'')===$q?'selected':'')?>><?=$q?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Percentage / CGPA</label>
                            <input type="text" name="percentage" value="<?= htmlspecialchars($_POST['percentage'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">College / University</label>
                            <input type="text" name="college" value="<?= htmlspecialchars($_POST['college'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Year of Passing</label>
                            <input type="text" name="yearOfPassing" pattern="^[0-9]{4}$" placeholder="YYYY" value="<?= htmlspecialchars($_POST['yearOfPassing'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </section>

                <!-- 3. Program Selection -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">laptop_mac</span> 3. Program Selection
                    </h3>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Select Program <span class="text-red-500">*</span></label>
                        <select name="program" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
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
                </section>

                <!-- 4. Fee & Payment -->
                <section>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-slate-800 shadow-sm">
                        <h3 class="flex items-center gap-2 text-lg font-bold mb-4 text-blue-900">
                            <span class="material-symbols-outlined">payments</span> 4. Payment Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Registration Fee <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-slate-500 font-bold">₹</span>
                                    <input type="number" id="amountInput" name="amount" value="<?= htmlspecialchars($_POST['amount'] ?? '500') ?>" min="0" step="1" required class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 font-bold text-slate-800">
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-white border rounded-xl p-4 shadow-xs">
                                <div>
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wide">Payment Mode</span>
                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-bold mt-1">
                                        <i class="bi bi-cash-coin"></i> OFFLINE / Cash
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wide">To Collect</span>
                                    <span id="amtBig" class="text-xl font-bold text-blue-700">₹<?= number_format((float)($_POST['amount'] ?? 500), 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 5. Additional Information & Counselor -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">chat_bubble</span> 5. Additional Information & Counselor
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Prior Experience</label>
                            <textarea name="experience" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20"><?= htmlspecialchars($_POST['experience'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Motivation</label>
                            <textarea name="motivation" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20"><?= htmlspecialchars($_POST['motivation'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Admission Counselor</label>
                            <select name="counselor_name" id="counselor_select" onchange="checkCounselor(this.value)" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 bg-white">
                                <option value="Direct / Self">Direct / Self</option>
                                <option value="Muskan Yadav">Muskan Yadav</option>
                                <option value="Anjali Tripathi">Anjali Tripathi</option>
                                <option value="Saloni Singh">Saloni Singh</option>
                                <option value="Sanjana Kushwaha">Sanjana Kushwaha</option>
                                <option value="Vijaylal">Vijaylal</option>
                                <option value="Other">Other / Custom Name</option>
                            </select>
                            <input type="text" name="counselor_other" id="counselor_other" placeholder="Enter Counselor Name" class="hidden mt-2 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20 bg-white">
                        </div>
                        <div class="md:col-span-2 flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <input type="checkbox" name="updates" id="updates" <?= isset($_POST['updates']) ? 'checked' : '' ?> class="w-4 h-4 text-primary focus:ring-primary rounded">
                            <label for="updates" class="text-sm font-medium text-slate-700 cursor-pointer">Send email updates and notifications to student</label>
                        </div>
                    </div>
                </section>

                <!-- Submit Bar -->
                <div class="flex justify-between items-center pt-6 border-t">
                    <span class="text-xs text-slate-400"><span class="text-red-500">*</span> Required fields</span>
                    <div class="flex gap-4">
                        <a href="dashboard.php" class="px-6 py-2.5 rounded-xl border font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancel</a>
                        <button type="submit" class="bg-primary hover:bg-teal-700 text-white font-bold px-8 py-2.5 rounded-xl transition-all shadow-md flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">save</span> Save Registration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

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
        btn.innerHTML = '<i class="bi bi-file-earmark-pdf-fill"></i> Download PDF Receipt';
    }, 150);
}
<?php endif; ?>

function checkCounselor(val) {
    const customInput = document.getElementById('counselor_other');
    if (val === 'Other') {
        customInput.classList.remove('hidden');
        customInput.required = true;
        customInput.focus();
    } else {
        customInput.classList.add('hidden');
        customInput.required = false;
    }
}
</script>
</body>
</html>