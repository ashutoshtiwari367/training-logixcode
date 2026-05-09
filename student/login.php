<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

if (isStudentLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = trim($_POST['student_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($studentId) || empty($password)) {
        $error = 'Please enter both Student ID and Password.';
    } else {
        // Find student by student_id or email
        $stmt = $pdo->prepare("SELECT * FROM registrations WHERE student_id = ? OR email = ?");
        $stmt->execute([$studentId, $studentId]);
        $student = $stmt->fetch();

        if ($student && !empty($student['password_hash'])) {
            if (password_verify($password, $student['password_hash'])) {
                loginStudent($student);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid credentials. Please try again.';
            }
        } else {
            $error = 'Student not found or account not activated.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Login | LogixCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #fff; }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .bg-shapes {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            overflow: hidden; z-index: -1;
        }
        .shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.5;
            border-radius: 50%;
        }
        .shape-1 { background: #3b82f6; width: 400px; height: 400px; top: -100px; left: -100px; animation: float 10s infinite ease-in-out alternate; }
        .shape-2 { background: #8b5cf6; width: 500px; height: 500px; bottom: -150px; right: -150px; animation: float 12s infinite ease-in-out alternate-reverse; }
        .shape-3 { background: #0ea5e9; width: 300px; height: 300px; bottom: 20%; left: 20%; animation: float 15s infinite ease-in-out alternate; }
        
        @keyframes float {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-50px) scale(1.1); }
        }
        .input-glass {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">
    
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="w-full max-w-md p-6">
        <div class="glass-panel rounded-2xl p-8 relative overflow-hidden">
            <!-- Decorative line -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
            
            <div class="text-center mb-8">
                <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="LogixCode" class="h-16 mx-auto mb-4">
                <h1 class="text-2xl font-bold tracking-tight text-white">Student Portal</h1>
                <p class="text-slate-400 text-sm mt-2">Enter your credentials to access your dashboard</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-3 rounded-lg text-sm mb-6 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Student ID or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-person text-slate-400"></i>
                            </div>
                            <input type="text" name="student_id" class="input-glass w-full pl-10 pr-4 py-3 rounded-xl" placeholder="e.g. STU-2026-1234" required>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-slate-300">Password</label>
                            <a href="#" class="text-xs text-blue-400 hover:text-blue-300">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock text-slate-400"></i>
                            </div>
                            <input type="password" name="password" id="password" class="input-glass w-full pl-10 pr-10 py-3 rounded-xl" placeholder="••••••••" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-white" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-gradient w-full py-3 rounded-xl font-semibold text-white flex justify-center items-center gap-2 mt-4">
                        Sign In <i class="bi bi-arrow-right-short text-xl"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-slate-400 border-t border-slate-700/50 pt-6">
                Don't have an account? <br>
                <a href="../registration/index.php" class="text-blue-400 hover:text-blue-300 font-medium mt-1 inline-block">Register for a Program</a>
            </div>
        </div>
        
        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; <?php echo date('Y'); ?> LogixCode IT Solution. All rights reserved.
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>
</body>
</html>
