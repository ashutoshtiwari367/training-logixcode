<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

requireStudentLogin();

// Fetch student details
$stmt = $pdo->prepare("
    SELECT r.*, p.amount, p.payment_gateway_id, p.status as payment_status 
    FROM registrations r 
    LEFT JOIN payments p ON r.registration_id = p.registration_id 
    WHERE r.id = ?
");
$stmt->execute([$_SESSION['student_db_id']]);
$student = $stmt->fetch();

if (!$student) {
    logoutStudent();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .sidebar { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        .gradient-text { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar w-64 text-white flex flex-col hidden md:flex h-full">
        <div class="p-6 border-b border-slate-700/50">
            <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="LogixCode" class="h-10 mb-2 filter brightness-0 invert">
            <h2 class="text-lg font-bold tracking-tight">Student Portal</h2>
        </div>
        <nav class="flex-1 py-4">
            <a href="index.php" class="flex items-center px-6 py-3 bg-blue-600/20 text-blue-400 border-r-4 border-blue-500">
                <i class="bi bi-grid-1x2-fill mr-3 text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors cursor-not-allowed opacity-50" title="Coming Soon">
                <i class="bi bi-journal-text mr-3 text-lg"></i>
                <span class="font-medium">My Courses</span>
            </a>
            <a href="#" class="flex items-center px-6 py-3 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors cursor-not-allowed opacity-50" title="Coming Soon">
                <i class="bi bi-award mr-3 text-lg"></i>
                <span class="font-medium">Certificates</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-700/50">
            <a href="logout.php" class="flex items-center px-4 py-2 text-slate-300 hover:bg-red-500/10 hover:text-red-400 rounded-lg transition-colors">
                <i class="bi bi-box-arrow-right mr-3"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-500 hover:text-slate-700">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800">Welcome back, <?php echo htmlspecialchars($student['first_name']); ?>! 👋</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md">
                    <?php echo strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)); ?>
                </div>
            </div>
        </header>

        <div class="p-8 max-w-7xl mx-auto w-full">
            
            <!-- Welcome Banner -->
            <div class="bg-white rounded-2xl p-8 mb-8 card-shadow relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-100">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-blue-50 to-transparent rounded-full -mr-20 -mt-20"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">Program: <span class="gradient-text"><?php echo htmlspecialchars($student['program']); ?></span></h2>
                    <p class="text-slate-500 max-w-2xl text-base">Your registration is complete. You can access your registration letter and student ID card below. Important updates regarding your course will be communicated via email.</p>
                </div>
                <div class="relative z-10 flex gap-4 shrink-0">
                    <a href="download_doc.php?type=id_card" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2 transition-all shadow-lg shadow-slate-900/20">
                        <i class="bi bi-person-badge text-lg"></i> Download ID Card
                    </a>
                    <a href="download_doc.php?type=reg_letter" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-6 py-3 rounded-xl font-medium flex items-center gap-2 transition-colors border border-blue-200">
                        <i class="bi bi-file-earmark-pdf text-lg"></i> Reg. Letter
                    </a>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Profile Information -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-white rounded-2xl p-6 card-shadow border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                            <i class="bi bi-person-circle text-blue-500"></i> Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Student ID</span>
                                <span class="block text-slate-800 font-medium font-mono bg-slate-50 px-3 py-1 rounded inline-block"><?php echo htmlspecialchars($student['student_id']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Full Name</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email Address</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['email']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Phone Number</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['phone']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Date of Birth & Gender</span>
                                <span class="block text-slate-800 font-medium"><?php echo date('d M Y', strtotime($student['dob'])) . ' • ' . ucfirst($student['gender']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Registration Date</span>
                                <span class="block text-slate-800 font-medium"><?php echo date('d M Y, h:i A', strtotime($student['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 card-shadow border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                            <i class="bi bi-mortarboard-fill text-purple-500"></i> Academic Background
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Highest Qualification</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['qualification']); ?></span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Percentage / CGPA</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['percentage'] ?? '-'); ?></span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">College / University</span>
                                <span class="block text-slate-800 font-medium"><?php echo htmlspecialchars($student['college'] ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Side Panel -->
                <div class="space-y-8">
                    
                    <div class="bg-white rounded-2xl p-6 card-shadow border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                            <i class="bi bi-credit-card-fill text-emerald-500"></i> Payment Details
                        </h3>
                        
                        <div class="flex items-center justify-between mb-6 bg-slate-50 p-4 rounded-xl">
                            <div>
                                <span class="block text-sm text-slate-500">Amount Paid</span>
                                <span class="block text-2xl font-bold text-slate-800">₹<?php echo number_format($student['amount'] ?? 500, 2); ?></span>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm text-slate-500">Status</span>
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold mt-1">
                                    <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($student['payment_status'] ?? 'SUCCESS'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Payment Mode</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($student['payment_mode']); ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Transaction ID</span>
                                <span class="font-medium text-slate-800 font-mono"><?php echo htmlspecialchars($student['payment_gateway_id'] ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white card-shadow relative overflow-hidden">
                        <i class="bi bi-headset absolute -bottom-6 -right-6 text-9xl text-white/5"></i>
                        <h3 class="text-lg font-bold mb-2">Need Help?</h3>
                        <p class="text-slate-300 text-sm mb-6">If you have any questions regarding your course or registration, feel free to contact us.</p>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0"><i class="bi bi-telephone"></i></div>
                                <span>+91-8467898854</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0"><i class="bi bi-envelope"></i></div>
                                <span>info@logixcode.com</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>
