<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

$currentUser = getCurrentUser();

$error = '';
$success = '';

// Pre-fill variables
$reg_id = $_GET['reg_id'] ?? '';
$student_name = '';
$email = '';
$phone = '';
$course_name = '';
$registered_amount = 0.00;

// Fetch Registration data if reg_id provided
if ($reg_id) {
    $stmt = $pdo->prepare("
        SELECT r.*, p.amount as paid_amount, p.status as p_status 
        FROM registrations r 
        LEFT JOIN payments p ON r.registration_id = p.registration_id 
        WHERE r.registration_id = ?
    ");
    $stmt->execute([$reg_id]);
    $regData = $stmt->fetch();
    
    if ($regData) {
        $student_name = $regData['first_name'] . ' ' . $regData['last_name'];
        $email = $regData['email'];
        $phone = $regData['phone'];
        $course_name = $regData['program'];
        // Only consider SUCCESS or OFFLINE payments as valid paid amounts
        if ($regData['p_status'] === 'SUCCESS' || $regData['p_status'] === 'OFFLINE') {
            $registered_amount = (float)$regData['paid_amount'];
        }
    } else {
        $error = "Registration ID not found!";
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate sequential LGC ID (LGC001, LGC002 ...)
    $lastId = $pdo->query("SELECT MAX(CAST(SUBSTRING(admission_id, 4) AS UNSIGNED)) FROM admissions WHERE admission_id LIKE 'LGC%'")->fetchColumn();
    $nextNum = ($lastId ? (int)$lastId : 0) + 1;
    $admission_id = 'LGC' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    $post_reg_id = $_POST['registration_id'] ?? null;
    if(empty($post_reg_id)) $post_reg_id = null;
    
    // Personal Details
    $student_name = trim($_POST['student_name'] ?? '');
    $father_name = trim($_POST['father_name'] ?? '');
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : null;
    $gender = $_POST['gender'] ?? null;
    $aadhar_number = trim($_POST['aadhar_number'] ?? '');
    $medical_condition = trim($_POST['medical_condition'] ?? '');
    
    // Contact Details
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $father_phone = trim($_POST['father_phone'] ?? '');
    $local_address = trim($_POST['local_address'] ?? '');
    $permanent_address = trim($_POST['permanent_address'] ?? '');
    
    // Academic Details
    $college_name = trim($_POST['college_name'] ?? '');
    $degree = trim($_POST['degree'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $current_semester = trim($_POST['current_semester'] ?? '');
    $course_name = trim($_POST['course_name'] ?? '');
    
    // Requirements
    $hostel_required = $_POST['hostel_required'] ?? 'No';
    $laptop_required = $_POST['laptop_required'] ?? 'No';
    
    // Fee Details
    $total_fees = (float)($_POST['total_fees'] ?? 0);
    $reg_amt = (float)($_POST['registered_amount'] ?? 0);
    $new_paid = (float)($_POST['paid_amount'] ?? 0);
    $payment_mode = $_POST['payment_mode'] ?? 'CASH';
    
    // Total actually paid = registered_amount + new_paid
    $total_paid = $reg_amt + $new_paid;
    $balance_amount = $total_fees - $total_paid;
    
    $fee_status = 'PENDING';
    if ($balance_amount <= 0) {
        $fee_status = 'PAID';
        $balance_amount = 0; // Prevent negative balance
    } elseif ($total_paid > 0) {
        $fee_status = 'PARTIAL';
    }

    // Handle File Upload
    $photo_filename = null;
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['student_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $photo_filename = $admission_id . '_' . time() . '.' . $file_ext;
            $dest_path = $upload_dir . $photo_filename;
            if (!move_uploaded_file($_FILES['student_photo']['tmp_name'], $dest_path)) {
                $error = "Failed to move uploaded file.";
            }
        } else {
            $error = "Invalid photo format. Only JPG, PNG, WEBP allowed.";
        }
    }

    if (empty($student_name) || empty($phone)) {
        $error = "Student name and phone are required!";
    } elseif (!$error) {
        try {
            $sql = "INSERT INTO admissions (
                admission_id, registration_id, student_name, father_name, dob, gender, student_photo, aadhar_number, medical_condition,
                email, phone, father_phone, local_address, permanent_address,
                college_name, degree, branch, current_semester, course_name,
                hostel_required, laptop_required,
                total_fees, registered_amount, paid_amount, payment_mode, balance_amount, fee_status
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, ?, 
                ?, ?, 
                ?, ?, ?, ?, ?, ?
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $admission_id, $post_reg_id, $student_name, $father_name, $dob, $gender, $photo_filename, $aadhar_number, $medical_condition,
                $email, $phone, $father_phone, $local_address, $permanent_address,
                $college_name, $degree, $branch, $current_semester, $course_name,
                $hostel_required, $laptop_required,
                $total_fees, $reg_amt, $new_paid, $payment_mode, $balance_amount, $fee_status
            ]);
            
            $success = "Admission completed successfully! Admission ID: " . $admission_id;
            header("refresh:2;url=admissions.php");
            
        } catch(PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Add Admission - LogixCode Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Public Sans', sans-serif; background-color: #f8fafd; }</style>
</head>
<body class="flex">

<?php include "sidebar.php" ?>

<main class="flex-1 ml-72 min-h-screen p-8">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-slate-50 border-b border-slate-200 p-6 flex items-center gap-4">
            <a href="dashboard.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border text-slate-500 hover:text-slate-800 hover:shadow-sm transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">New Admission Form</h2>
                <p class="text-slate-500 text-sm">Fill in all details to generate ID and admission record.</p>
            </div>
        </div>

        <div class="p-8">
            <?php if($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-6 rounded-xl mb-8 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-5xl mb-2 text-emerald-500">check_circle</span>
                    <h3 class="font-bold text-xl mb-1">Success!</h3>
                    <p><?= htmlspecialchars($success) ?></p>
                </div>
            <?php else: ?>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-10">
                
                <!-- Registration Link Section (if any) -->
                <div class="bg-blue-50 border border-blue-100 p-5 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-sm">
                        <span class="material-symbols-outlined">link</span>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-1">Registration ID (Linked)</label>
                        <input type="text" name="registration_id" value="<?= htmlspecialchars($reg_id) ?>" 
                               class="w-full bg-transparent border-0 border-b border-blue-200 focus:ring-0 px-0 py-1 text-slate-700 font-medium" 
                               readonly placeholder="No registration linked (Direct Admission)">
                    </div>
                </div>

                <!-- 1. Personal Details -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">person</span> 1. Personal Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Student Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="student_name" value="<?= htmlspecialchars($student_name) ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Father's Name</label>
                                    <input type="text" name="father_name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
                                    <input type="date" name="dob" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Gender</label>
                                    <select name="gender" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Aadhar Number</label>
                                    <input type="text" name="aadhar_number" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Any Medical Condition?</label>
                                    <input type="text" name="medical_condition" placeholder="If none, leave blank" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- Photo Upload -->
                            <label class="block text-sm font-bold text-slate-700 mb-2">Student Photo</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div id="photoPreview" class="w-32 h-32 mx-auto rounded-lg bg-slate-200 mb-3 overflow-hidden flex items-center justify-center border">
                                    <span class="material-symbols-outlined text-4xl text-slate-400">person</span>
                                </div>
                                <input type="file" name="student_photo" id="student_photo" accept="image/jpeg,image/png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-xs text-slate-400 mt-2">Format: JPG or PNG only (max 2MB)</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2. Contact Details -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">contacts</span> 2. Contact Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Student Mobile <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Father's / Alt Mobile</label>
                            <input type="text" name="father_phone" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-3 grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Local Address (Hostel/PG/Room)</label>
                                <textarea name="local_address" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Permanent Address</label>
                                <textarea name="permanent_address" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. Academic & Training Details -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">school</span> 3. Academic & Course Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">College / University Name</label>
                            <input type="text" name="college_name" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Course Enrolled (Training)</label>
                            <input type="text" name="course_name" value="<?= htmlspecialchars($course_name) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">College Degree (e.g. B.Tech, BCA)</label>
                            <input type="text" name="degree" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Branch / Specialization</label>
                            <input type="text" name="branch" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Current Semester/Year</label>
                            <input type="text" name="current_semester" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </section>

                <!-- 4. Extra Requirements -->
                <section>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 mb-6 pb-2 border-b">
                        <span class="material-symbols-outlined text-primary">add_circle</span> 4. Additional Requirements
                    </h3>
                    <div class="flex gap-8">
                        <div class="flex items-center gap-3 bg-slate-50 border p-4 rounded-xl flex-1">
                            <span class="material-symbols-outlined text-slate-400">bed</span>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Hostel Required?</p>
                                <div class="mt-2 flex gap-4">
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="hostel_required" value="Yes" class="text-primary focus:ring-primary"> Yes</label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="hostel_required" value="No" checked class="text-primary focus:ring-primary"> No</label>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 border p-4 rounded-xl flex-1">
                            <span class="material-symbols-outlined text-slate-400">laptop_mac</span>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Laptop Required (Rent)?</p>
                                <div class="mt-2 flex gap-4">
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="laptop_required" value="Yes" class="text-primary focus:ring-primary"> Yes</label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="laptop_required" value="No" checked class="text-primary focus:ring-primary"> No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 5. Fee Calculation -->
                <section>
                    <div class="bg-purple-900 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
                        <!-- Decorative shapes -->
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
                        
                        <h3 class="flex items-center gap-2 text-xl font-bold mb-6">
                            <span class="material-symbols-outlined">payments</span> 5. Fee Settlement
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-purple-200 uppercase tracking-widest mb-2">Total Fees</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-purple-400 font-bold">₹</span>
                                    <input type="number" id="total_fees" name="total_fees" required min="0" class="w-full pl-8 pr-3 py-2.5 bg-purple-800/50 border border-purple-700 rounded-lg text-white font-bold text-lg focus:border-white focus:ring-0 placeholder-purple-400">
                                </div>
                            </div>
                            <div class="hidden md:flex items-center justify-center mt-6">
                                <span class="material-symbols-outlined text-purple-400">remove</span>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-emerald-300 uppercase tracking-widest mb-2">Registered Amt</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-emerald-400 font-bold">₹</span>
                                    <input type="number" id="registered_amount" name="registered_amount" value="<?= $registered_amount ?>" readonly class="w-full pl-8 pr-3 py-2.5 bg-emerald-900/40 border border-emerald-800/50 rounded-lg text-emerald-300 font-bold text-lg focus:outline-none">
                                </div>
                            </div>
                            <div class="hidden md:flex items-center justify-center mt-6">
                                <span class="material-symbols-outlined text-purple-400">remove</span>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-blue-300 uppercase tracking-widest mb-2">New Payment</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-blue-400 font-bold">₹</span>
                                    <input type="number" id="paid_amount" name="paid_amount" value="0" min="0" class="w-full pl-8 pr-3 py-2.5 bg-blue-900/40 border border-blue-700 rounded-lg text-white font-bold text-lg focus:border-white focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-purple-700/50 flex flex-wrap items-end justify-between gap-6">
                            <div>
                                <label class="block text-xs font-bold text-purple-200 uppercase tracking-widest mb-2">Payment Mode</label>
                                <select name="payment_mode" class="px-4 py-2.5 bg-purple-800/50 border border-purple-700 rounded-lg text-white font-bold focus:border-white focus:ring-0">
                                    <option value="CASH">CASH</option>
                                    <option value="UPI">UPI / QR</option>
                                    <option value="BANK_TRANSFER">Bank Transfer</option>
                                    <option value="CHEQUE">Cheque</option>
                                </select>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-rose-300 uppercase tracking-widest mb-1">Remaining Balance</p>
                                <div class="flex items-center gap-1 justify-end">
                                    <span class="text-2xl font-bold text-rose-400">₹</span>
                                    <input type="text" id="balance_amount" readonly class="w-32 bg-transparent border-0 text-3xl font-bold text-white text-right focus:ring-0 p-0" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-4 pt-6">
                    <a href="dashboard.php" class="px-8 py-3 rounded-xl border-2 border-slate-200 font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="px-10 py-3 rounded-xl bg-purple-600 text-white font-bold text-lg shadow-lg hover:bg-purple-700 hover:shadow-purple-500/30 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span> Confirm Admission
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Image Preview Logic
document.getElementById('student_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
});

// Fee Calculation Logic
const totalInput = document.getElementById('total_fees');
const regInput = document.getElementById('registered_amount');
const paidInput = document.getElementById('paid_amount');
const balanceInput = document.getElementById('balance_amount');

function calculateFees() {
    const total = parseFloat(totalInput.value) || 0;
    const regAmt = parseFloat(regInput.value) || 0;
    const paidAmt = parseFloat(paidInput.value) || 0;
    
    let balance = total - (regAmt + paidAmt);
    if(balance < 0) balance = 0;
    
    balanceInput.value = balance;
}

totalInput.addEventListener('input', calculateFees);
paidInput.addEventListener('input', calculateFees);
// Initial calc
calculateFees();
</script>
</body>
</html>
