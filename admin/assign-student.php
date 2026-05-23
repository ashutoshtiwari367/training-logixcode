<?php
/**
 * Student Assignment (Manual)
 * admin/assign-student.php
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/generate_id_card.php';

requireLogin();

$success = '';
$error   = '';
$warnings = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_student'])) {
    try {
        if (!validateCSRF($_POST['csrf_token'])) {
            throw new Exception("Invalid security token. Please refresh and try again.");
        }

        $regId    = trim($_POST['registration_id']);
        $studentId = trim($_POST['student_id']);
        $password  = trim($_POST['password']);

        if (empty($regId) || empty($studentId) || empty($password)) {
            throw new Exception("All fields are required.");
        }

        // Validate Student ID format (basic)
        if (!preg_match('/^[A-Z0-9\-]+$/', strtoupper($studentId))) {
            throw new Exception("Invalid Student ID format. Use letters, numbers and hyphens only.");
        }

        // Check for duplicate Student ID
        $chk = $pdo->prepare("SELECT registration_id FROM registrations WHERE student_id = ? AND registration_id != ?");
        $chk->execute([$studentId, $regId]);
        if ($chk->fetch()) {
            throw new Exception("Student ID '{$studentId}' is already assigned to another student.");
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // === STEP 1: Update registration record (CRITICAL - must succeed) ===
        $stmt = $pdo->prepare("UPDATE registrations SET student_id = ?, password_hash = ? WHERE registration_id = ?");
        $stmt->execute([$studentId, $passwordHash, $regId]);

        // Get student details
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE registration_id = ?");
        $stmt->execute([$regId]);
        $student = $stmt->fetch();

        if (!$student) {
            throw new Exception("Registration record not found after update.");
        }

        $success = "Student ID <strong>{$studentId}</strong> assigned to <strong>{$student['first_name']} {$student['last_name']}</strong> successfully!";

        // === STEP 2: Generate ID Card (optional - warn if fails) ===
        $idCardPath = null;
        try {
            $idCardPath = generateStudentIdCard($pdo, $regId);
        } catch (Exception $e) {
            $warnings[] = "ID Card generation failed: " . $e->getMessage();
        }

        // === STEP 3: Send Credential Email (optional - warn if fails) ===
        try {
            $emailData = [
                'firstName'    => $student['first_name'],
                'lastName'     => $student['last_name'],
                'email'        => $student['email'],
                'student_id'   => $studentId,
                'raw_password' => $password
            ];
            sendCredentialEmail($emailData, $regId, $idCardPath);
            $success .= " Credential email sent to {$student['email']}";
        } catch (Exception $e) {
            $warnings[] = "Email could not be sent (" . $e->getMessage() . "). Credentials are saved — share manually: ID: <strong>{$studentId}</strong>, Password: <strong>{$password}</strong>";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch pending registrations (those without a student_id)
$stmt = $pdo->query("SELECT registration_id, first_name, last_name, email, program FROM registrations WHERE student_id IS NULL OR student_id = '' ORDER BY created_at DESC");
$pending = $stmt->fetchAll();

$csrfToken = generateCSRF();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Assign Student - LogixCode Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h2 class="text-2xl font-bold text-slate-800">Assign Student Credentials</h2>
            <p class="text-slate-500 text-sm">Create credentials and auto-generate ID cards for enrolled students</p>
        </div>
        <div class="text-slate-500 text-sm font-medium">Home / Student Assign</div>
    </div>

    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined">error</span> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Assignment Form Column (5 cols) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">badge</span> Assign Details
                </h3>
                
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Select Registration ID</label>
                        <select name="registration_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20" id="regSelector" required>
                            <option value="">-- Choose Student --</option>
                            <?php foreach ($pending as $p): ?>
                            <option value="<?= $p['registration_id'] ?>" data-name="<?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>" data-email="<?= htmlspecialchars($p['email']) ?>" data-prog="<?= htmlspecialchars($p['program']) ?>">
                                <?= htmlspecialchars($p['registration_id']) ?> - <?= htmlspecialchars($p['first_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Selected Student Card Details -->
                    <div id="studentInfo" class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-xl hidden">
                        <div class="text-sm mb-1.5"><strong class="text-slate-500">Name:</strong> <span id="infoName" class="font-semibold text-slate-800"></span></div>
                        <div class="text-sm mb-1.5"><strong class="text-slate-500">Email:</strong> <span id="infoEmail" class="font-semibold text-slate-800"></span></div>
                        <div class="text-sm"><strong class="text-slate-500">Program:</strong> <span id="infoProg" class="font-semibold text-slate-800"></span></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Custom Student ID</label>
                        <div class="flex gap-2">
                            <span class="bg-slate-100 border border-r-0 rounded-l-lg px-3 py-2 text-slate-500 flex items-center font-bold text-sm">ID</span>
                            <input type="text" name="student_id" class="flex-1 px-4 py-2 border rounded-r-lg focus:ring-2 focus:ring-primary/20" id="stuIdInput" placeholder="STU-2026-0001" required>
                            <button type="button" class="bg-slate-100 border hover:bg-slate-200 px-4 py-2 rounded-lg font-bold text-slate-700 text-sm transition-colors" onclick="genId()">Auto</button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Assign Password</label>
                        <div class="flex gap-2">
                            <span class="bg-slate-100 border border-r-0 rounded-l-lg px-3 py-2 text-slate-500 flex items-center"><span class="material-symbols-outlined text-base">lock</span></span>
                            <input type="text" name="password" class="flex-1 px-4 py-2 border rounded-r-lg focus:ring-2 focus:ring-primary/20" id="passInput" placeholder="SecretPassword" required>
                            <button type="button" class="bg-slate-100 border hover:bg-slate-200 px-4 py-2 rounded-lg font-bold text-slate-700 text-sm transition-colors" onclick="genPass()">Random</button>
                        </div>
                    </div>

                    <button type="submit" name="assign_student" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">person_check</span> Assign & Send Mail
                    </button>
                </form>
            </div>
        </div>

        <!-- Pending Table Column (7 cols) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-500">pending_actions</span> Pending Assignments
                    </h3>
                    <span class="bg-purple-100 text-purple-700 font-bold px-3 py-1 rounded-full text-xs"><?= count($pending) ?></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Reg ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Student Details</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Program</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (count($pending) > 0): ?>
                            <?php foreach ($pending as $p): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-400"><?= htmlspecialchars($p['registration_id']) ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($p['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600"><?= htmlspecialchars($p['program']) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="pickStudent('<?= htmlspecialchars($p['registration_id']) ?>')" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-1.5 rounded-lg text-xs font-bold transition-colors">Assign</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400">No pending assignments found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    const selector = document.getElementById('regSelector');
    const infoDiv = document.getElementById('studentInfo');
    const infoName = document.getElementById('infoName');
    const infoEmail = document.getElementById('infoEmail');
    const infoProg = document.getElementById('infoProg');

    selector.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            infoName.textContent = opt.dataset.name;
            infoEmail.textContent = opt.dataset.email;
            infoProg.textContent = opt.dataset.prog;
            infoDiv.classList.remove('hidden');
            // Auto fill suggest ID if empty
            if (!document.getElementById('stuIdInput').value) genId();
            if (!document.getElementById('passInput').value) genPass();
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    function pickStudent(id) {
        selector.value = id;
        selector.dispatchEvent(new Event('change'));
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function genId() {
        const year = new Date().getFullYear();
        const rand = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('stuIdInput').value = 'STU-' + year + '-' + rand;
    }

    function genPass() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        let pass = "";
        for (let i = 0; i < 8; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('passInput').value = pass;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
