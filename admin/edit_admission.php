<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

$currentUser = getCurrentUser();

$error = '';
$success = '';

$id = $_GET['id'] ?? '';
if (!$id) {
    die("Admission ID is required.");
}

// Fetch Existing Admission Data
$stmt = $pdo->prepare("SELECT * FROM admissions WHERE admission_id = ?");
$stmt->execute([$id]);
$adm = $stmt->fetch();

if (!$adm) {
    die("Admission not found.");
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    
    // For editing, we allow them to add new payment to the existing paid amount, OR just update total fees.
    $additional_payment = (float)($_POST['new_payment'] ?? 0);
    $payment_mode = $_POST['payment_mode'] ?? 'CASH';
    
    $paid_amount = (float)$adm['paid_amount'] + $additional_payment;
    
    $total_paid_overall = $reg_amt + $paid_amount;
    $balance_amount = $total_fees - $total_paid_overall;
    
    $fee_status = 'PENDING';
    if ($balance_amount <= 0) {
        $fee_status = 'PAID';
        $balance_amount = 0;
    } elseif ($total_paid_overall > 0) {
        $fee_status = 'PARTIAL';
    }

    // Handle Photo Upload
    $photo_filename = $adm['student_photo']; // Keep old by default
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/photos/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['student_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $photo_filename = $id . '_' . time() . '.' . $file_ext;
            $dest_path = $upload_dir . $photo_filename;
            if (move_uploaded_file($_FILES['student_photo']['tmp_name'], $dest_path)) {
                // Delete old photo
                if ($adm['student_photo'] && file_exists($upload_dir . $adm['student_photo'])) {
                    @unlink($upload_dir . $adm['student_photo']);
                }
            } else {
                $error = "Failed to move uploaded photo.";
            }
        } else {
            $error = "Invalid photo format.";
        }
    }

    if (empty($student_name) || empty($phone)) {
        $error = "Student name and phone are required!";
    } elseif (!$error) {
        try {
            $sql = "UPDATE admissions SET 
                student_name=?, father_name=?, dob=?, gender=?, student_photo=?, aadhar_number=?, medical_condition=?,
                email=?, phone=?, father_phone=?, local_address=?, permanent_address=?,
                college_name=?, degree=?, branch=?, current_semester=?, course_name=?,
                hostel_required=?, laptop_required=?,
                total_fees=?, paid_amount=?, payment_mode=?, balance_amount=?, fee_status=?
                WHERE admission_id=?";
                
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $student_name, $father_name, $dob, $gender, $photo_filename, $aadhar_number, $medical_condition,
                $email, $phone, $father_phone, $local_address, $permanent_address,
                $college_name, $degree, $branch, $current_semester, $course_name,
                $hostel_required, $laptop_required,
                $total_fees, $paid_amount, $payment_mode, $balance_amount, $fee_status,
                $id
            ]);
            
            $success = "Admission details updated successfully!";
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM admissions WHERE admission_id = ?");
            $stmt->execute([$id]);
            $adm = $stmt->fetch();
            
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
<title>Edit Admission - LogixCode Admin</title>
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
        <div class="bg-slate-50 border-b border-slate-200 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="admissions.php" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border text-slate-500 hover:text-slate-800 hover:shadow-sm transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Edit Admission</h2>
                    <p class="text-slate-500 text-sm">ID: <?= htmlspecialchars($id) ?></p>
                </div>
            </div>
            <a href="download_admission_pdf.php?id=<?= urlencode($id) ?>" class="flex items-center gap-2 bg-slate-800 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-slate-700">
                <span class="material-symbols-outlined text-sm">download</span> Download PDF
            </a>
        </div>

        <div class="p-8">
            <?php if($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-8 font-bold flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-10">
                
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
                                    <input type="text" name="student_name" value="<?= htmlspecialchars($adm['student_name']) ?>" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Father's Name</label>
                                    <input type="text" name="father_name" value="<?= htmlspecialchars($adm['father_name']) ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
                                    <input type="date" name="dob" value="<?= $adm['dob'] ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Gender</label>
                                    <select name="gender" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?= $adm['gender']=='Male'?'selected':'' ?>>Male</option>
                                        <option value="Female" <?= $adm['gender']=='Female'?'selected':'' ?>>Female</option>
                                        <option value="Other" <?= $adm['gender']=='Other'?'selected':'' ?>>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Aadhar Number</label>
                                    <input type="text" name="aadhar_number" value="<?= htmlspecialchars($adm['aadhar_number']) ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Any Medical Condition?</label>
                                    <input type="text" name="medical_condition" value="<?= htmlspecialchars($adm['medical_condition']) ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                        </div>
                        <div>
                            <!-- Photo Upload -->
                            <label class="block text-sm font-bold text-slate-700 mb-2">Student Photo</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-slate-50 hover:bg-slate-100 transition-colors">
                                <div id="photoPreview" class="w-32 h-32 mx-auto rounded-lg bg-slate-200 mb-3 overflow-hidden flex items-center justify-center border">
                                    <?php if($adm['student_photo']): ?>
                                        <img src="../uploads/photos/<?= $adm['student_photo'] ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="material-symbols-outlined text-4xl text-slate-400">person</span>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="student_photo" id="student_photo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-[10px] text-slate-400 mt-2">Upload new photo to replace</p>
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
                            <input type="email" name="email" value="<?= htmlspecialchars($adm['email']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Student Mobile <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($adm['phone']) ?>" required class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Father's / Alt Mobile</label>
                            <input type="text" name="father_phone" value="<?= htmlspecialchars($adm['father_phone']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div class="md:col-span-3 grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Local Address (Hostel/PG/Room)</label>
                                <textarea name="local_address" rows="2" class="w-full px-4 py-2 border rounded-lg"><?= htmlspecialchars($adm['local_address']) ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Permanent Address</label>
                                <textarea name="permanent_address" rows="2" class="w-full px-4 py-2 border rounded-lg"><?= htmlspecialchars($adm['permanent_address']) ?></textarea>
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
                            <input type="text" name="college_name" value="<?= htmlspecialchars($adm['college_name']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Course Enrolled (Training)</label>
                            <input type="text" name="course_name" value="<?= htmlspecialchars($adm['course_name']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">College Degree (e.g. B.Tech, BCA)</label>
                            <input type="text" name="degree" value="<?= htmlspecialchars($adm['degree']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Branch / Specialization</label>
                            <input type="text" name="branch" value="<?= htmlspecialchars($adm['branch']) ?>" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Current Semester/Year</label>
                            <input type="text" name="current_semester" value="<?= htmlspecialchars($adm['current_semester']) ?>" class="w-full px-4 py-2 border rounded-lg">
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
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="hostel_required" value="Yes" <?= $adm['hostel_required']=='Yes'?'checked':'' ?> class="text-primary focus:ring-primary"> Yes</label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="hostel_required" value="No" <?= $adm['hostel_required']=='No'?'checked':'' ?> class="text-primary focus:ring-primary"> No</label>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 border p-4 rounded-xl flex-1">
                            <span class="material-symbols-outlined text-slate-400">laptop_mac</span>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Laptop Required (Rent)?</p>
                                <div class="mt-2 flex gap-4">
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="laptop_required" value="Yes" <?= $adm['laptop_required']=='Yes'?'checked':'' ?> class="text-primary focus:ring-primary"> Yes</label>
                                    <label class="flex items-center gap-2 text-sm cursor-pointer"><input type="radio" name="laptop_required" value="No" <?= $adm['laptop_required']=='No'?'checked':'' ?> class="text-primary focus:ring-primary"> No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 5. Fee Update -->
                <section>
                    <div class="bg-indigo-900 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
                        <h3 class="flex items-center gap-2 text-xl font-bold mb-6">
                            <span class="material-symbols-outlined">payments</span> 5. Fee Manager
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-2">Total Fees</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-indigo-400 font-bold">₹</span>
                                    <input type="number" id="total_fees" name="total_fees" value="<?= $adm['total_fees'] ?>" required min="0" class="w-full pl-8 pr-3 py-2.5 bg-indigo-800/50 border border-indigo-700 rounded-lg text-white font-bold text-lg focus:border-white focus:ring-0">
                                </div>
                            </div>
                            <div class="hidden md:flex items-center justify-center mt-6">
                                <span class="material-symbols-outlined text-indigo-400">remove</span>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-emerald-300 uppercase tracking-widest mb-2">Total Paid So Far</label>
                                <?php $paid_so_far = $adm['registered_amount'] + $adm['paid_amount']; ?>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-emerald-400 font-bold">₹</span>
                                    <input type="number" id="paid_so_far" value="<?= $paid_so_far ?>" readonly class="w-full pl-8 pr-3 py-2.5 bg-emerald-900/40 border border-emerald-800/50 rounded-lg text-emerald-300 font-bold text-lg focus:outline-none">
                                    <input type="hidden" name="registered_amount" value="<?= $adm['registered_amount'] ?>">
                                    <input type="hidden" id="previous_paid" value="<?= $adm['paid_amount'] ?>">
                                </div>
                            </div>
                            <div class="hidden md:flex items-center justify-center mt-6">
                                <span class="material-symbols-outlined text-indigo-400">remove</span>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-blue-300 uppercase tracking-widest mb-2">Add New Payment</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-blue-400 font-bold">₹</span>
                                    <input type="number" id="new_payment" name="new_payment" value="0" min="0" class="w-full pl-8 pr-3 py-2.5 bg-blue-900/40 border border-blue-700 rounded-lg text-white font-bold text-lg focus:border-white focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-indigo-700/50 flex flex-wrap items-end justify-between gap-6">
                            <div>
                                <label class="block text-xs font-bold text-indigo-200 uppercase tracking-widest mb-2">Payment Mode</label>
                                <select name="payment_mode" class="px-4 py-2.5 bg-indigo-800/50 border border-indigo-700 rounded-lg text-white font-bold focus:border-white focus:ring-0">
                                    <option value="CASH">CASH</option>
                                    <option value="UPI">UPI / QR</option>
                                    <option value="BANK_TRANSFER">Bank Transfer</option>
                                    <option value="CHEQUE">Cheque</option>
                                </select>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-rose-300 uppercase tracking-widest mb-1">New Balance Amount</p>
                                <div class="flex items-center gap-1 justify-end">
                                    <span class="text-2xl font-bold text-rose-400">₹</span>
                                    <input type="text" id="balance_amount" readonly class="w-32 bg-transparent border-0 text-3xl font-bold text-white text-right focus:ring-0 p-0" value="<?= $adm['balance_amount'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-4 pt-6">
                    <a href="admissions.php" class="px-8 py-3 rounded-xl border-2 border-slate-200 font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="px-10 py-3 rounded-xl bg-blue-600 text-white font-bold text-lg shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Update Admission
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
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

const totalInput = document.getElementById('total_fees');
const paidSoFarInput = document.getElementById('paid_so_far');
const newPaymentInput = document.getElementById('new_payment');
const balanceInput = document.getElementById('balance_amount');

function calculateFees() {
    const total = parseFloat(totalInput.value) || 0;
    const paidSoFar = parseFloat(paidSoFarInput.value) || 0;
    const newPayment = parseFloat(newPaymentInput.value) || 0;
    
    let balance = total - (paidSoFar + newPayment);
    if(balance < 0) balance = 0;
    
    balanceInput.value = balance;
}

totalInput.addEventListener('input', calculateFees);
newPaymentInput.addEventListener('input', calculateFees);
calculateFees();
</script>
</body>
</html>
