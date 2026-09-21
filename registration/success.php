<?php
/**
 * Registration Success Page
 * registration/success.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Allow registration ID from session or query param for viewing
$registrationId = $_SESSION['completed_registration_id'] ?? ($_GET['id'] ?? null);
$paymentId      = $_SESSION['completed_payment_id'] ?? null;

if (!$registrationId) {
    header('Location: index.php');
    exit;
}

// Fetch registration & payment details
try {
    $stmt = $pdo->prepare("
        SELECT r.*, p.payment_gateway_id, p.amount, p.status as payment_status 
        FROM registrations r 
        LEFT JOIN payments p ON r.registration_id = p.registration_id 
        WHERE r.registration_id = ?
    ");
    $stmt->execute([$registrationId]);
    $registration = $stmt->fetch();

    if (!$registration) {
        throw new Exception('Registration record not found');
    }

    if (!$paymentId && !empty($registration['payment_gateway_id'])) {
        $paymentId = $registration['payment_gateway_id'];
    }
} catch (Exception $e) {
    die('Error loading registration details: ' . htmlspecialchars($e->getMessage()));
}

// Clear single-use session flags if set
unset($_SESSION['completed_registration_id']);
unset($_SESSION['completed_payment_id']);
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful | <?php echo INSTITUTE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "brand-cyan": "#03c4ce",
                "brand-dark": "#004f54",
                "primary-container": "#03bfd3",
              },
              fontFamily: {
                sans: ['Inter', 'Manrope', 'sans-serif'],
                heading: ['Manrope', 'sans-serif'],
              }
            }
          }
        }
    </script>
    <style>
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            body {
                padding-top: 0 !important;
                background: white !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto space-y-8">
            
            <!-- Success Header Banner -->
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center relative overflow-hidden print-card">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mb-4 animate-bounce">
                    <span class="material-symbols-outlined text-4xl">check_circle</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading mb-2">
                    Registration Confirmed!
                </h1>
                <p class="text-slate-600 max-w-lg mx-auto text-base">
                    Thank you, <strong class="text-slate-900"><?php echo htmlspecialchars($registration['first_name']); ?></strong>. Your application and payment have been processed successfully.
                </p>
            </div>

            <!-- Registration ID Highlight Box -->
            <div class="bg-gradient-to-r from-slate-900 to-[#004f54] text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 print-card">
                <div class="space-y-1 text-center sm:text-left">
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#03c4ce]">Official Registration ID</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-wider font-mono text-white">
                        <?php echo htmlspecialchars($registration['registration_id']); ?>
                    </h2>
                    <p class="text-xs text-slate-300">Please save this ID for all future correspondence</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 text-center min-w-[160px]">
                    <span class="block text-xs uppercase text-slate-300 font-semibold tracking-wider mb-1">Amount Paid</span>
                    <span class="text-2xl font-extrabold text-[#03c4ce]">₹<?php echo number_format($registration['amount'] ?? REGISTRATION_FEE, 2); ?></span>
                    <span class="block text-[10px] text-emerald-400 font-bold uppercase tracking-wide mt-0.5">● PAID ONLINE</span>
                </div>
            </div>

            <!-- Detailed Receipt Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 space-y-8 print-card">
                
                <!-- Section 1: Payment Metadata -->
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[#03c4ce]">receipt_long</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Payment Information</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm bg-slate-50 p-4 sm:p-5 rounded-2xl">
                        <div>
                            <span class="block text-xs text-slate-500 font-semibold uppercase">Payment Gateway ID</span>
                            <span class="font-mono font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($paymentId ?? 'N/A'); ?></span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-semibold uppercase">Payment Mode</span>
                            <span class="font-bold text-slate-800"><?php echo htmlspecialchars($registration['payment_mode'] ?? 'ONLINE'); ?></span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-semibold uppercase">Transaction Date</span>
                            <span class="font-semibold text-slate-800"><?php echo date('d M Y, h:i A', strtotime($registration['created_at'])); ?></span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-500 font-semibold uppercase">Payment Status</span>
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-600">
                                <span class="material-symbols-outlined text-base">check</span> SUCCESS
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Student Profile -->
                <div>
                    <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                        <span class="material-symbols-outlined text-[#03c4ce]">person</span>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Student & Program Details</h3>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Full Name</span>
                            <span class="font-bold text-slate-900"><?php echo htmlspecialchars($registration['first_name'] . ' ' . $registration['last_name']); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Email Address</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registration['email']); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Mobile Number</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registration['phone']); ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Date of Birth / Gender</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registration['dob']); ?> (<?php echo htmlspecialchars(ucfirst($registration['gender'])); ?>)</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Qualification</span>
                            <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($registration['qualification']); ?> (<?php echo htmlspecialchars($registration['percentage']); ?>)</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-500">Selected Program</span>
                            <span class="font-bold text-[#004f54]"><?php echo htmlspecialchars($registration['program']); ?></span>
                        </div>
                        <div class="flex justify-between items-start py-2">
                            <span class="text-slate-500">Residential Address</span>
                            <span class="font-medium text-slate-800 text-right max-w-xs"><?php echo htmlspecialchars($registration['address']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Info Notice -->
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 text-sky-900 text-xs sm:text-sm space-y-1">
                    <div class="flex items-center gap-1.5 font-bold text-sky-800">
                        <span class="material-symbols-outlined text-base">info</span>
                        <span>Confirmation Email Sent</span>
                    </div>
                    <p class="text-sky-700">
                        A detailed receipt and confirmation message has been sent to <strong><?php echo htmlspecialchars($registration['email']); ?></strong>. Please check your inbox / spam folder.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center no-print">
                    <button onclick="window.print()" 
                            class="py-3 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-lg">print</span>
                        <span>Print / Save Receipt PDF</span>
                    </button>
                    
                    <a href="<?= BASE_URL ?>" 
                       class="py-3 px-6 rounded-xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-bold text-sm shadow-lg shadow-[#03c4ce]/20 flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-lg">home</span>
                        <span>Return to Website</span>
                    </a>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
