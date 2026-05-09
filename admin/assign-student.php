<?php
/**
 * Student Assignment (Manual)
 * admin/assign-student.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../includes/generate_id_card.php';

requireLogin();

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_student'])) {
    try {
        if (!validateCSRF($_POST['csrf_token'])) {
            throw new Exception("Invalid security token");
        }

        $regId = $_POST['registration_id'];
        $studentId = trim($_POST['student_id']);
        $password = trim($_POST['password']);

        if (empty($regId) || empty($studentId) || empty($password)) {
            throw new Exception("All fields are required.");
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Update registration record
        $stmt = $pdo->prepare("UPDATE registrations SET student_id = ?, password_hash = ? WHERE registration_id = ?");
        $stmt->execute([$studentId, $passwordHash, $regId]);

        // Get student details for email
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE registration_id = ?");
        $stmt->execute([$regId]);
        $student = $stmt->fetch();

        // Generate ID Card
        $idCardPath = generateStudentIdCard($pdo, $regId);

        // Send Email
        $emailData = [
            'firstName' => $student['first_name'],
            'lastName' => $student['last_name'],
            'email' => $student['email'],
            'student_id' => $studentId,
            'raw_password' => $password
        ];

        sendCredentialEmail($emailData, $regId, $idCardPath);

        $success = "Student ID ({$studentId}) assigned successfully to {$student['first_name']}. Email sent.";

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Student — Logixcode Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --bg: #f8fafc; --sidebar: #0f172a; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: #1e293b; }
        .sidebar { background: var(--sidebar); min-height: 100vh; color: #fff; padding: 20px; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .btn-primary { background: var(--primary); border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; }
        .btn-primary:hover { background: #1d4ed8; }
        .table thead th { background: #f1f5f9; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar d-none d-md-block">
            <div class="mb-4 text-center">
                <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="Logo" style="height: 40px;">
                <h6 class="mt-2 text-white">Admin Portal</h6>
            </div>
            <nav class="nav flex-column">
                <a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="admissions.php" class="nav-link"><i class="bi bi-people"></i> Admissions</a>
                <a href="add-registration.php" class="nav-link"><i class="bi bi-person-plus"></i> New Registration</a>
                <a href="assign-student.php" class="nav-link active"><i class="bi bi-key"></i> Student Assign</a>
                <hr class="text-white-50">
                <a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Assign Student Credentials</h4>
                <div class="text-muted small">Home / Student Assign</div>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Assignment Form -->
                <div class="col-md-5">
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Assign Details</h6>
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Select Registration ID</label>
                                <select name="registration_id" class="form-select" id="regSelector" required>
                                    <option value="">-- Choose Student --</option>
                                    <?php foreach ($pending as $p): ?>
                                    <option value="<?= $p['registration_id'] ?>" data-name="<?= $p['first_name'] . ' ' . $p['last_name'] ?>" data-email="<?= $p['email'] ?>" data-prog="<?= $p['program'] ?>">
                                        <?= $p['registration_id'] ?> - <?= $p['first_name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div id="studentInfo" class="mb-3 p-3 bg-light rounded d-none">
                                <div class="small mb-1"><strong>Name:</strong> <span id="infoName"></span></div>
                                <div class="small mb-1"><strong>Email:</strong> <span id="infoEmail"></span></div>
                                <div class="small"><strong>Program:</strong> <span id="infoProg"></span></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Custom Student ID</label>
                                <div class="input-group">
                                    <span class="input-group-text">ID</span>
                                    <input type="text" name="student_id" class="form-control" id="stuIdInput" placeholder="STU-2026-0001" required>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="genId()">Auto</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">Assign Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="text" name="password" class="form-control" id="passInput" placeholder="SecretPassword" required>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="genPass()">Random</button>
                                </div>
                            </div>

                            <button type="submit" name="assign_student" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-person-check-fill me-2"></i> Assign & Send Mail
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Pending Table -->
                <div class="col-md-7">
                    <div class="card p-0">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold m-0">Pending Assignments</h6>
                            <span class="badge bg-primary rounded-pill"><?= count($pending) ?></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Reg ID</th>
                                        <th>Student</th>
                                        <th>Program</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($pending) > 0): ?>
                                    <?php foreach ($pending as $p): ?>
                                    <tr>
                                        <td class="fw-bold small"><?= $p['registration_id'] ?></td>
                                        <td>
                                            <div class="fw-bold"><?= $p['first_name'] ?> <?= $p['last_name'] ?></div>
                                            <div class="small text-muted"><?= $p['email'] ?></div>
                                        </td>
                                        <td class="small"><?= $p['program'] ?></td>
                                        <td>
                                            <button onclick="pickStudent('<?= $p['registration_id'] ?>')" class="btn btn-sm btn-outline-primary py-1">Assign</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No pending assignments found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            infoDiv.classList.remove('d-none');
            // Auto fill suggest ID if empty
            if (!document.getElementById('stuIdInput').value) genId();
            if (!document.getElementById('passInput').value) genPass();
        } else {
            infoDiv.classList.add('d-none');
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
