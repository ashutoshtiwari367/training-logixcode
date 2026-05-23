<?php
/**
 * admission-form.php
 * Public form – student fills all personal, academic, and contact details (no fees section shown).
 * Automatically links to an existing registration if `reg_id` is provided in URL.
 * Otherwise, generates a registration automatically.
 * Records are added directly to the `admissions` table so they appear in the Admissions Portal.
 */

session_start();
require_once __DIR__ . '/config/db.php';

$success = false;
$error   = '';
$admission_id = '';

// Pre-fill variables if reg_id is provided in the URL
$reg_id = $_GET['reg_id'] ?? '';
$student_name = '';
$email = '';
$phone = '';
$course_name = '';
$registered_amount = 0.00;

if (!empty($reg_id)) {
    $stmt = $pdo->prepare("
        SELECT r.*, p.amount as paid_amount, p.status as p_status 
        FROM registrations r 
        LEFT JOIN payments p ON r.registration_id = p.registration_id 
        WHERE r.registration_id = ?
    ");
    $stmt->execute([$reg_id]);
    $regData = $stmt->fetch();
    
    if ($regData) {
        $student_name = trim($regData['first_name'] . ' ' . $regData['last_name']);
        $email = trim($regData['email']);
        $phone = trim($regData['phone']);
        $course_name = trim($regData['program']);
        if ($regData['p_status'] === 'SUCCESS' || $regData['p_status'] === 'OFFLINE') {
            $registered_amount = (float)$regData['paid_amount'];
        }
    } else {
        $error = "Invalid registration ID in link! You can still fill out the form from scratch.";
        $reg_id = ''; // Clear it so it behaves like direct/scratch admission
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate sequential LGC ID (LGC001, LGC002 ...)
    $lastId = $pdo->query("SELECT MAX(CAST(SUBSTRING(admission_id, 4) AS UNSIGNED)) FROM admissions WHERE admission_id LIKE 'LGC%'")->fetchColumn();
    $nextNum = ($lastId ? (int)$lastId : 0) + 1;
    $admission_id = 'LGC' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    
    $post_reg_id = $_POST['registration_id'] ?? null;
    if (empty($post_reg_id)) {
        $post_reg_id = null;
    }
    
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
    
    // Set Fees to 0 for student submissions – admin will edit this later
    $total_fees = 0.00;
    $reg_amt = (float)($_POST['registered_amount'] ?? 0.00);
    $new_paid = 0.00;
    $payment_mode = 'OFFLINE';
    
    // Balance and status defaults
    $balance_amount = 0.00;
    $fee_status = 'PENDING';

    // Handle File Upload for Student Photo
    $photo_filename = null;
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads/photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['student_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $photo_filename = $admission_id . '_' . time() . '.' . $file_ext;
            $dest_path = $upload_dir . $photo_filename;
            if (!move_uploaded_file($_FILES['student_photo']['tmp_name'], $dest_path)) {
                $error = "Failed to save the uploaded photo.";
            }
        } else {
            $error = "Invalid photo format. Only JPG, JPEG, PNG, or WEBP allowed.";
        }
    }

    if (empty($student_name) || empty($phone) || empty($email)) {
        $error = "Student Name, Mobile Number, and Email Address are required!";
    } elseif (!$error) {
        try {
            $pdo->beginTransaction();

            // If no existing registration ID is linked, create a new one automatically
            if (empty($post_reg_id)) {
                $post_reg_id = generateRegistrationId();

                // Split name for registration
                $parts = explode(' ', $student_name, 2);
                $first_name = trim($parts[0]);
                $last_name = isset($parts[1]) ? trim($parts[1]) : '';

                $reg_dob = !empty($dob) ? $dob : '2000-01-01';
                $reg_gender = !empty($gender) ? strtolower($gender) : 'other';
                if (!in_array($reg_gender, ['male', 'female', 'other'])) {
                    $reg_gender = 'other';
                }
                $reg_address = !empty($permanent_address) ? $permanent_address : (!empty($local_address) ? $local_address : 'N/A');
                $reg_qualification = !empty($degree) ? $degree : 'Other';
                $reg_program = !empty($course_name) ? $course_name : 'Training';

                // Insert registration
                $stmtReg = $pdo->prepare("INSERT INTO registrations (
                    registration_id, first_name, last_name, email, phone, dob, gender,
                    address, qualification, percentage, college, program, payment_mode, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, 'N/A', ?, ?, 'OFFLINE', NOW()
                )");
                $stmtReg->execute([
                    $post_reg_id, $first_name, $last_name, $email, $phone, $reg_dob, $reg_gender,
                    $reg_address, $reg_qualification, $college_name, $reg_program
                ]);

                // Insert dummy offline payment of 0
                $stmtPay = $pdo->prepare("INSERT INTO payments (
                    registration_id, payment_gateway_id, amount, currency, status, created_at
                ) VALUES (
                    ?, ?, 0.00, 'INR', 'OFFLINE', NOW()
                )");
                $stmtPay->execute([
                    $post_reg_id, 'OFFLINE-STUDENT-' . time()
                ]);
            }

            // Insert into admissions
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
            
            $pdo->commit();
            $success = true;
            
        } catch(Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
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
    <title>Admission Form - <?= htmlspecialchars(INSTITUTE_NAME) ?></title>
    <meta name="description" content="Complete your student admission registration by filling out details including personal info, qualifications, and other training requirements." />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d9488',
                        secondary: '#0f766e',
                        accent: '#f59e0b',
                        background: '#f8fafc'
                    },
                    fontFamily: {
                        sans: ['Public Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Logo / Header -->
        <header class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-teal-800 tracking-tight flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-4xl text-primary">school</span>
                <?= htmlspecialchars(INSTITUTE_NAME) ?>
            </h1>
            <p class="text-slate-500 mt-2 text-sm font-medium">Student Admission & Training Registration Portal</p>
        </header>

        <main class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Top Banner -->
            <div class="bg-gradient-to-r from-primary to-secondary p-6 sm:p-8 text-white relative">
                <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-white opacity-5 rounded-full blur-xl"></div>
                <h2 class="text-2xl font-bold">Admission & Verification Form</h2>
                <p class="text-teal-50 mt-2 text-sm opacity-90">Please fill out all the details carefully to submit your admission request. Double-check your contact details so we can reach out.</p>
            </div>

            <div class="p-6 sm:p-10">
                <?php if ($success): ?>
                    <div class="text-center py-12 px-4">
                        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-emerald-500 shadow-md">
                            <span class="material-symbols-outlined text-emerald-600 text-5xl">check_circle</span>
                        </div>
                        <h3 class="text-3xl font-bold text-slate-800">Form Submitted Successfully!</h3>
                        <p class="text-slate-600 mt-4 max-w-md mx-auto text-lg leading-relaxed">
                            Thank you, <span class="font-semibold text-primary"><?= htmlspecialchars($student_name) ?></span>! 
                            Your details have been successfully received and mapped to your registration. 
                        </p>
                        <div class="mt-6 bg-slate-50 p-4 rounded-xl inline-block border border-slate-200">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Admission ID Generated</p>
                            <p class="text-2xl font-bold text-slate-800 tracking-wider mt-1"><?= htmlspecialchars($admission_id) ?></p>
                        </div>
                        <p class="text-slate-400 text-xs mt-8">You can now close this tab. Our operations team will get in touch with you shortly.</p>
                    </div>
                <?php else: ?>

                    <?php if ($error): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm">
                            <span class="material-symbols-outlined shrink-0 text-red-500">error</span>
                            <span class="text-sm font-medium"><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form id="admissionForm" method="POST" action="" enctype="multipart/form-data" class="space-y-10">
                        <!-- Registration Link Info -->
                        <input type="hidden" name="registration_id" value="<?= htmlspecialchars($reg_id) ?>">
                        <input type="hidden" name="registered_amount" value="<?= htmlspecialchars($registered_amount) ?>">

                        <?php if (!empty($reg_id)): ?>
                            <div class="bg-teal-50 border border-teal-200 p-4 rounded-xl flex items-center gap-4 shadow-sm">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary shadow-sm shrink-0">
                                    <span class="material-symbols-outlined">link</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-teal-800 uppercase tracking-wider">Registration Link Active</p>
                                    <p class="text-sm text-teal-700 mt-0.5">Linked Registration ID: <span class="font-bold"><?= htmlspecialchars($reg_id) ?></span></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Section 1: Personal Details -->
                        <section class="space-y-6">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 pb-2 border-b border-slate-100">
                                <span class="material-symbols-outlined text-primary">person</span>
                                1. Personal Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2 space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div>
                                            <label for="student_name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="student_name" name="student_name" value="<?= htmlspecialchars($student_name) ?>" required 
                                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all bg-slate-50/50" 
                                                   placeholder="As in Class X Certificate">
                                        </div>
                                        <div>
                                            <label for="father_name" class="block text-sm font-semibold text-slate-700 mb-2">Father's Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="father_name" name="father_name" required 
                                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                                   placeholder="Father's Full Name">
                                        </div>
                                        <div>
                                            <label for="dob" class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                                            <input type="date" id="dob" name="dob" required 
                                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        </div>
                                        <div>
                                            <label for="gender" class="block text-sm font-semibold text-slate-700 mb-2">Gender <span class="text-red-500">*</span></label>
                                            <select id="gender" name="gender" required 
                                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all bg-white">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="aadhar_number" class="block text-sm font-semibold text-slate-700 mb-2">Aadhar Card Number</label>
                                            <input type="text" id="aadhar_number" name="aadhar_number" maxlength="12" pattern="\d{12}" 
                                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                                   placeholder="12 digit Aadhar number">
                                        </div>
                                        <div>
                                            <label for="medical_condition" class="block text-sm font-semibold text-slate-700 mb-2">Medical Conditions (if any)</label>
                                            <input type="text" id="medical_condition" name="medical_condition" 
                                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                                   placeholder="E.g., allergies, asthma or leave blank">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-center">
                                    <!-- Photo Upload Preview widget -->
                                    <label class="block text-sm font-semibold text-slate-700 mb-2 w-full text-center md:text-left">Student Photo <span class="text-red-500">*</span></label>
                                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center bg-slate-50 hover:bg-slate-100/70 transition-all w-full max-w-[240px] flex flex-col justify-center items-center">
                                        <div id="photoPreview" class="w-32 h-32 rounded-xl bg-slate-200 mb-4 overflow-hidden flex items-center justify-center border-2 border-white shadow-sm shrink-0">
                                            <span class="material-symbols-outlined text-5xl text-slate-400">person</span>
                                        </div>
                                        <input type="file" name="student_photo" id="student_photo" accept="image/jpeg,image/png,image/webp" required 
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[11px] file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                        <p class="text-[10px] text-slate-400 mt-2.5">Format: JPG, PNG, WEBP (Max 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Section 2: Contact Details -->
                        <section class="space-y-6">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 pb-2 border-b border-slate-100">
                                <span class="material-symbols-outlined text-primary">contacts</span>
                                2. Contact Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all bg-slate-50/50">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all bg-slate-50/50">
                                </div>
                                <div>
                                    <label for="father_phone" class="block text-sm font-semibold text-slate-700 mb-2">Parent / Emergency Phone <span class="text-red-500">*</span></label>
                                    <input type="tel" id="father_phone" name="father_phone" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                           placeholder="Parent/Guardian Contact">
                                </div>
                                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="local_address" class="block text-sm font-semibold text-slate-700 mb-2">Local Address (Hostel / PG / Room Address)</label>
                                        <textarea id="local_address" name="local_address" rows="3" 
                                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-slate-400" 
                                                  placeholder="Your current staying address in the city"></textarea>
                                    </div>
                                    <div>
                                        <label for="permanent_address" class="block text-sm font-semibold text-slate-700 mb-2">Permanent Address <span class="text-red-500">*</span></label>
                                        <textarea id="permanent_address" name="permanent_address" rows="3" required 
                                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-slate-400" 
                                                  placeholder="Home address for official communications"></textarea>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Section 3: Academic & Training Details -->
                        <section class="space-y-6">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 pb-2 border-b border-slate-100">
                                <span class="material-symbols-outlined text-primary">school</span>
                                3. Academic & Course Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">College / University Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="college_name" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                           placeholder="College/University Name">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Course Enrolled (Training) <span class="text-red-500">*</span></label>
                                    <input type="text" name="course_name" value="<?= htmlspecialchars($course_name) ?>" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all bg-slate-50/50">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">College Degree <span class="text-red-500">*</span></label>
                                    <input type="text" name="degree" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                           placeholder="E.g. B.Tech, BCA, MCA, Diploma">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Branch / Specialization <span class="text-red-500">*</span></label>
                                    <input type="text" name="branch" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                           placeholder="E.g. CSE, IT, ECE">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Current Semester / Year <span class="text-red-500">*</span></label>
                                    <input type="text" name="current_semester" required 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" 
                                           placeholder="E.g. 6th Sem, 3rd Year">
                                </div>
                            </div>
                        </section>

                        <!-- Section 4: Extra Requirements -->
                        <section class="space-y-6">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800 pb-2 border-b border-slate-100">
                                <span class="material-symbols-outlined text-primary">add_circle</span>
                                4. Additional Facility Requirements
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="flex items-start gap-4 bg-slate-50 border border-slate-200 p-5 rounded-2xl hover:bg-slate-100/50 transition-all">
                                    <span class="material-symbols-outlined text-slate-400 text-3xl">bed</span>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">Hostel Facility Required?</p>
                                        <p class="text-xs text-slate-400 mt-1">Check Yes if you require accommodation facilities.</p>
                                        <div class="mt-3 flex gap-6">
                                            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer">
                                                <input type="radio" name="hostel_required" value="Yes" class="text-primary focus:ring-primary border-slate-300 w-4 h-4"> Yes
                                            </label>
                                            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer">
                                                <input type="radio" name="hostel_required" value="No" checked class="text-primary focus:ring-primary border-slate-300 w-4 h-4"> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 bg-slate-50 border border-slate-200 p-5 rounded-2xl hover:bg-slate-100/50 transition-all">
                                    <span class="material-symbols-outlined text-slate-400 text-3xl">laptop_mac</span>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">Laptop Required (On Rent)?</p>
                                        <p class="text-xs text-slate-400 mt-1">Check Yes if you do not have a personal laptop.</p>
                                        <div class="mt-3 flex gap-6">
                                            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer">
                                                <input type="radio" name="laptop_required" value="Yes" class="text-primary focus:ring-primary border-slate-300 w-4 h-4"> Yes
                                            </label>
                                            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer">
                                                <input type="radio" name="laptop_required" value="No" checked class="text-primary focus:ring-primary border-slate-300 w-4 h-4"> No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-4 flex-wrap">
                            <p class="text-xs text-slate-400 leading-normal max-w-sm">By submitting this form, you certify that all information provided is true and correct to the best of your knowledge.</p>
                            <button type="submit" class="px-8 py-3.5 rounded-xl bg-primary text-white font-bold text-base shadow-lg shadow-primary/20 hover:bg-secondary hover:shadow-teal-600/30 active:scale-[0.98] transition-all flex items-center gap-2 cursor-pointer">
                                <span class="material-symbols-outlined text-xl">send</span> Submit Admission Details
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </main>

        <footer class="mt-8 text-center text-slate-400 text-[11px] font-medium uppercase tracking-widest pb-8">
            &copy; 2026 <?= htmlspecialchars(INSTITUTE_NAME) ?> &bull; All Rights Reserved
        </footer>
    </div>

    <script>
        // Image Preview Script
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
    </script>
</body>
</html>
