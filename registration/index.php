<?php
/**
 * Student Registration Form - 3 Step Wizard
 * registration/index.php
 */ 
session_start();
require_once __DIR__ . '/../config/db.php';

// Handle clear session request
if (isset($_GET['clear']) && $_GET['clear'] == '1') {
    unset($_SESSION['registration_data']);
    unset($_SESSION['razorpay_order']);
    unset($_SESSION['registration_timestamp']);
}

// Generate CSRF token
$csrfToken = generateCSRF();

// Payment configuration
define('PAYMENT_AMOUNT', REGISTRATION_FEE); // Using global config (699)
define('PAYMENT_CURRENCY', 'INR');
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration | <?php echo INSTITUTE_NAME; ?></title>
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
        .step-transition {
            transition: opacity 0.25s ease-in-out, transform 0.25s ease-in-out;
        }
        .step-hidden {
            display: none;
            opacity: 0;
            transform: translateX(12px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow py-8 md:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Page Header -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 mb-6 text-center relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-36 h-36 bg-[#03c4ce]/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-[#004f54]/10 rounded-full blur-2xl"></div>
                
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#03c4ce]/10 text-[#03c4ce] mb-3">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight font-heading mb-2">
                    Student Registration
                </h1>
                <p class="text-slate-600 max-w-xl mx-auto text-sm sm:text-base">
                    Quick 3-step application form to enroll in training programs with <?php echo INSTITUTE_NAME; ?>.
                </p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'session_expired'): ?>
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600">warning</span>
                <div class="text-sm">
                    <strong>Session Expired:</strong> Your previous session timed out. Please complete the form to proceed.
                </div>
            </div>
            <?php endif; ?>

            <!-- Fee Notice Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-[#004f54] text-white rounded-2xl p-4 sm:p-6 shadow-md mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-center sm:text-left">
                    <div class="hidden sm:flex items-center justify-center w-12 h-12 rounded-xl bg-[#03c4ce]/20 text-[#03c4ce]">
                        <span class="material-symbols-outlined text-2xl">verified</span>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#03c4ce]/20 text-[#03c4ce] text-xs font-bold uppercase tracking-wider mb-1">
                            Official Admission 2026
                        </div>
                        <h3 class="text-base sm:text-lg font-bold">Fast & Easy Registration</h3>
                        <p class="text-slate-300 text-xs sm:text-sm">Complete all 3 simple steps to secure your seat.</p>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-xl border border-white/10 text-center min-w-[150px]">
                    <span class="block text-[11px] uppercase text-slate-300 font-semibold tracking-wider">Registration Fee</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#03c4ce]">₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                </div>
            </div>

            <!-- Stepper Navigation Header -->
            <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-md border border-slate-100 mb-6">
                <div class="relative flex items-center justify-between max-w-2xl mx-auto">
                    <!-- Step Progress Line -->
                    <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1 bg-slate-200 z-0" style="top: 22px;"></div>
                    <div id="stepProgressBar" class="absolute left-6 top-1/2 -translate-y-1/2 h-1 bg-[#03c4ce] transition-all duration-300 z-0" style="top: 22px; width: 0%;"></div>

                    <!-- Step 1 Button -->
                    <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(1)">
                        <div id="stepDot-1" class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all duration-200 bg-[#03c4ce] text-white ring-4 ring-[#03c4ce]/20">
                            <span id="stepIcon-1" class="material-symbols-outlined text-lg">person</span>
                        </div>
                        <span id="stepLabel-1" class="mt-2 text-xs sm:text-sm font-bold text-[#03c4ce]">1. Personal</span>
                    </div>

                    <!-- Step 2 Button -->
                    <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(2)">
                        <div id="stepDot-2" class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all duration-200 bg-white text-slate-400 border-2 border-slate-200">
                            <span id="stepIcon-2" class="material-symbols-outlined text-lg">school</span>
                        </div>
                        <span id="stepLabel-2" class="mt-2 text-xs sm:text-sm font-semibold text-slate-500">2. Education</span>
                    </div>

                    <!-- Step 3 Button -->
                    <div class="relative z-10 flex flex-col items-center cursor-pointer" onclick="goToStep(3)">
                        <div id="stepDot-3" class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all duration-200 bg-white text-slate-400 border-2 border-slate-200">
                            <span id="stepIcon-3" class="material-symbols-outlined text-lg">verified</span>
                        </div>
                        <span id="stepLabel-3" class="mt-2 text-xs sm:text-sm font-semibold text-slate-500">3. Review</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form Card -->
            <div id="formCard" class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl border border-slate-100">
                <form id="registrationForm" method="POST" class="space-y-6" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

                    <!-- Global Error Alert -->
                    <div id="formErrorAlert" class="hidden p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium flex items-center gap-3">
                        <span class="material-symbols-outlined text-rose-500">error</span>
                        <span id="formErrorMessage">Please correct the errors below.</span>
                    </div>

                    <!-- ============================================== -->
                    <!-- STEP 1: PERSONAL INFORMATION -->
                    <!-- ============================================== -->
                    <div id="stepSection-1" class="step-transition">
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">person</span>
                            </span>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 font-heading">Step 1: Personal Information</h3>
                                <p class="text-xs sm:text-sm text-slate-500">Enter your basic identity and contact details</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="firstName" class="block text-sm font-semibold text-slate-700 mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" id="firstName" name="firstName" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="e.g. Rahul">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-firstName">Please enter your first name.</span>
                            </div>

                            <div>
                                <label for="lastName" class="block text-sm font-semibold text-slate-700 mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" id="lastName" name="lastName" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="e.g. Sharma">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-lastName">Please enter your last name.</span>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" id="email" name="email" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="name@example.com">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-email">Please enter a valid email address.</span>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Mobile Number <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold text-sm">+91</span>
                                    <input type="tel" id="phone" name="phone" inputmode="numeric" maxlength="10" required
                                           class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 pl-14 pr-4 shadow-sm text-sm"
                                           placeholder="10-digit mobile number">
                                </div>
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-phone">Please enter a valid 10-digit number starting with 6-9.</span>
                            </div>

                            <div>
                                <label for="dob" class="block text-sm font-semibold text-slate-700 mb-1.5">Date of Birth <span class="text-rose-500">*</span></label>
                                <input type="date" id="dob" name="dob" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm text-sm">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-dob">Please select your date of birth (minimum 10 years old).</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gender <span class="text-rose-500">*</span></label>
                                <div class="flex items-center gap-6 pt-2">
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-slate-700 font-medium">
                                        <input type="radio" name="gender" value="male" required class="text-[#03c4ce] focus:ring-[#03c4ce] w-4 h-4">
                                        <span>Male</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-slate-700 font-medium">
                                        <input type="radio" name="gender" value="female" class="text-[#03c4ce] focus:ring-[#03c4ce] w-4 h-4">
                                        <span>Female</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-slate-700 font-medium">
                                        <input type="radio" name="gender" value="other" class="text-[#03c4ce] focus:ring-[#03c4ce] w-4 h-4">
                                        <span>Other</span>
                                    </label>
                                </div>
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-gender">Please select your gender.</span>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Address <span class="text-rose-500">*</span></label>
                            <textarea id="address" name="address" rows="2" required
                                      class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-2.5 px-4 shadow-sm text-sm"
                                      placeholder="Enter your residential address"></textarea>
                            <span class="text-rose-500 text-xs mt-1 hidden" id="err-address">Please enter your address.</span>
                        </div>

                        <!-- Step 1 Next Button -->
                        <div class="pt-6 flex justify-end">
                            <button type="button" onclick="nextStep(1)"
                                    class="py-3.5 px-8 rounded-xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-bold text-base shadow-lg shadow-[#03c4ce]/25 transition-all duration-200 flex items-center gap-2">
                                <span>Next: Education & Course</span>
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- STEP 2: EDUCATION & COURSE SELECTION -->
                    <!-- ============================================== -->
                    <div id="stepSection-2" class="step-transition step-hidden">
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">school</span>
                            </span>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 font-heading">Step 2: Educational Background & Course</h3>
                                <p class="text-xs sm:text-sm text-slate-500">Provide your qualification details and choose your desired program</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="qualification" class="block text-sm font-semibold text-slate-700 mb-1.5">Highest Qualification <span class="text-rose-500">*</span></label>
                                <select id="qualification" name="qualification" required
                                        class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm text-sm">
                                    <option value="">-- Select Qualification --</option>
                                    <option value="10th">10th</option>
                                    <option value="12th">12th</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="B.Tech / B.E.">B.Tech / B.E.</option>
                                    <option value="BCA">BCA</option>
                                    <option value="B.Sc">B.Sc</option>
                                    <option value="MCA">MCA</option>
                                    <option value="M.Tech">M.Tech</option>
                                    <option value="M.Sc">M.Sc</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-qualification">Please select your qualification.</span>
                            </div>

                            <div>
                                <label for="percentage" class="block text-sm font-semibold text-slate-700 mb-1.5">Percentage / CGPA <span class="text-rose-500">*</span></label>
                                <input type="text" id="percentage" name="percentage" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="e.g., 85% or 8.5 CGPA">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-percentage">Please enter your percentage or CGPA.</span>
                            </div>

                            <div>
                                <label for="college" class="block text-sm font-semibold text-slate-700 mb-1.5">College / University</label>
                                <input type="text" id="college" name="college"
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="e.g. AKTU / Delhi University">
                            </div>

                            <div>
                                <label for="yearOfPassing" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    Year of Passing (Past / Upcoming)
                                </label>
                                <input type="number" id="yearOfPassing" name="yearOfPassing" min="1950" max="2040"
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm text-sm"
                                       placeholder="e.g. 2024, 2026, 2028">
                                <p class="text-[11px] text-slate-500 mt-1">Accepts any past or future graduation year (e.g. 2028).</p>
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-yearOfPassing">Please enter a valid 4-digit year (1950-2040).</span>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label for="program" class="block text-sm font-semibold text-slate-700 mb-1.5">Select Desired Program <span class="text-rose-500">*</span></label>
                            <select id="program" name="program" required
                                    class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm text-sm">
                                <option value="">-- Select Program --</option>
                                <optgroup label="Training Programs">
                                    <option value="Summer Training (45-60 Days)">Summer Training (45-60 Days)</option>
                                    <option value="Winter Training (45 Days)">Winter Training (45 Days)</option>
                                    <option value="Industrial Training (6 Months)">Industrial Training (6 Months)</option>
                                    <option value="Apprenticeship Program (3-6 Months)">Apprenticeship Program (3-6 Months)</option>
                                </optgroup>
                                <optgroup label="Technology Courses">
                                    <option value="Full Stack Development">Full Stack Development</option>
                                    <option value="Java Programming">Java Programming</option>
                                    <option value="Python Development">Python Development</option>
                                    <option value="MERN Stack Development">MERN Stack Development</option>
                                    <option value="Android Development">Android Development</option>
                                    <option value="Data Science & Analytics">Data Science & Analytics</option>
                                    <option value="AI & Machine Learning">AI & Machine Learning</option>
                                    <option value="Frontend Development">Frontend Development</option>
                                    <option value="Cloud Computing (AWS)">Cloud Computing (AWS)</option>
                                </optgroup>
                            </select>
                            <span class="text-rose-500 text-xs mt-1 hidden" id="err-program">Please select a program.</span>
                        </div>

                        <!-- Step 2 Navigation Buttons -->
                        <div class="pt-6 flex items-center justify-between gap-4">
                            <button type="button" onclick="prevStep(2)"
                                    class="py-3 px-6 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-sm transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">arrow_back</span>
                                <span>Previous</span>
                            </button>
                            <button type="button" onclick="nextStep(2)"
                                    class="py-3.5 px-8 rounded-xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-bold text-base shadow-lg shadow-[#03c4ce]/25 transition-all duration-200 flex items-center gap-2">
                                <span>Next: Review & Submit</span>
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- STEP 3: REVIEW & CONFIRMATION -->
                    <!-- ============================================== -->
                    <div id="stepSection-3" class="step-transition step-hidden">
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">verified</span>
                            </span>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 font-heading">Step 3: Review & Submission</h3>
                                <p class="text-xs sm:text-sm text-slate-500">Confirm your details and proceed to secure payment</p>
                            </div>
                        </div>

                        <!-- Live Summary Box -->
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-[#03c4ce]">fact_check</span>
                                    Application Summary
                                </h4>
                                <button type="button" onclick="goToStep(1)" class="text-xs text-[#03c4ce] hover:underline font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">edit</span> Edit Details
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Full Name</span>
                                    <span id="sumName" class="font-bold text-slate-800">-</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Email & Phone</span>
                                    <span id="sumContact" class="font-semibold text-slate-800">-</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Qualification / Score</span>
                                    <span id="sumQual" class="font-semibold text-slate-800">-</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-xs text-slate-400 block font-medium">Selected Program</span>
                                    <span id="sumProgram" class="font-bold text-[#004f54]">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Optional Info -->
                        <div class="space-y-4 mb-6">
                            <div>
                                <label for="experience" class="block text-sm font-semibold text-slate-700 mb-1.5">Prior Technical Experience (Optional)</label>
                                <textarea id="experience" name="experience" rows="2"
                                          class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-2.5 px-4 shadow-sm text-sm"
                                          placeholder="Describe any programming experience or projects..."></textarea>
                            </div>

                            <div>
                                <label for="motivation" class="block text-sm font-semibold text-slate-700 mb-1.5">Why do you want to join this program? (Optional)</label>
                                <textarea id="motivation" name="motivation" rows="2"
                                          class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-2.5 px-4 shadow-sm text-sm"
                                          placeholder="Share your goals and expectations..."></textarea>
                            </div>
                        </div>

                        <!-- Terms & Updates Checkboxes -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 mb-6">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="terms" name="terms" required
                                       class="mt-1 rounded text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                                <label for="terms" class="text-sm text-slate-700 font-medium cursor-pointer">
                                    I agree to the <a href="<?= BASE_URL ?>terms-and-condition.php" target="_blank" class="text-[#03c4ce] hover:underline font-semibold">Terms & Conditions</a> and certify that all details entered are correct. <span class="text-rose-500">*</span>
                                </label>
                            </div>
                            <span class="text-rose-500 text-xs block hidden" id="err-terms">You must agree to the terms to proceed.</span>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="updates" name="updates" checked
                                       class="mt-1 rounded text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                                <label for="updates" class="text-xs sm:text-sm text-slate-600 cursor-pointer">
                                    Keep me updated about batch schedules, internship opportunities & workshops.
                                </label>
                            </div>
                        </div>

                        <!-- Step 3 Navigation Buttons -->
                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <button type="button" onclick="prevStep(3)"
                                    class="w-full sm:w-auto py-3 px-6 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold text-sm transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">arrow_back</span>
                                <span>Previous</span>
                            </button>

                            <button type="submit" id="submitBtn"
                                    class="w-full sm:w-auto py-4 px-8 rounded-2xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-extrabold text-base sm:text-lg shadow-xl shadow-[#03c4ce]/30 transition-all duration-300 flex items-center justify-center gap-3">
                                <span>Proceed to Payment (₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?>)</span>
                                <span class="material-symbols-outlined text-2xl">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
        // Current active step tracker (1, 2, or 3)
        let currentStep = 1;

        // Set maximum allowed date of birth (minimum 10 years old)
        const dobInput = document.getElementById('dob');
        if (dobInput) {
            const maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - 10);
            dobInput.max = maxDate.toISOString().split('T')[0];
        }

        // Phone input filter — only digits allowed
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 10);
            });
        }

        // Hide all error spans helper
        function hideAllErrors() {
            document.querySelectorAll('[id^="err-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('formErrorAlert').classList.add('hidden');
        }

        // Step 1 Validation
        function validateStep1() {
            let valid = true;
            hideAllErrors();

            const firstName = document.getElementById('firstName').value.trim();
            if (!firstName) {
                document.getElementById('err-firstName').classList.remove('hidden');
                valid = false;
            }

            const lastName = document.getElementById('lastName').value.trim();
            if (!lastName) {
                document.getElementById('err-lastName').classList.remove('hidden');
                valid = false;
            }

            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('err-email').classList.remove('hidden');
                valid = false;
            }

            const rawPhone = phoneInput.value.replace(/\D/g, '');
            if (rawPhone.length !== 10 || !/^[6-9][0-9]{9}$/.test(rawPhone)) {
                document.getElementById('err-phone').classList.remove('hidden');
                valid = false;
            }

            const dob = document.getElementById('dob').value;
            if (!dob) {
                document.getElementById('err-dob').classList.remove('hidden');
                valid = false;
            }

            const genderSelected = document.querySelector('input[name="gender"]:checked');
            if (!genderSelected) {
                document.getElementById('err-gender').classList.remove('hidden');
                valid = false;
            }

            const address = document.getElementById('address').value.trim();
            if (!address) {
                document.getElementById('err-address').classList.remove('hidden');
                valid = false;
            }

            return valid;
        }

        // Step 2 Validation
        function validateStep2() {
            let valid = true;
            hideAllErrors();

            const qualification = document.getElementById('qualification').value;
            if (!qualification) {
                document.getElementById('err-qualification').classList.remove('hidden');
                valid = false;
            }

            const percentage = document.getElementById('percentage').value.trim();
            if (!percentage) {
                document.getElementById('err-percentage').classList.remove('hidden');
                valid = false;
            }

            const yearOfPassing = document.getElementById('yearOfPassing').value.trim();
            if (yearOfPassing) {
                const y = parseInt(yearOfPassing, 10);
                if (isNaN(y) || y < 1950 || y > 2040) {
                    document.getElementById('err-yearOfPassing').classList.remove('hidden');
                    valid = false;
                }
            }

            const program = document.getElementById('program').value;
            if (!program) {
                document.getElementById('err-program').classList.remove('hidden');
                valid = false;
            }

            return valid;
        }

        // Update Review Summary in Step 3
        function updateSummary() {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            const rawPhone = phoneInput.value.replace(/\D/g, '');
            const qualification = document.getElementById('qualification').value;
            const percentage = document.getElementById('percentage').value.trim();
            const program = document.getElementById('program').value;

            document.getElementById('sumName').textContent = (firstName || lastName) ? `${firstName} ${lastName}` : '-';
            document.getElementById('sumContact').textContent = email ? `${email} | +91 ${rawPhone}` : '-';
            document.getElementById('sumQual').textContent = qualification ? `${qualification} (${percentage || 'N/A'})` : '-';
            document.getElementById('sumProgram').textContent = program || '-';
        }

        // Stepper UI Renderer
        function updateStepperUI(targetStep) {
            currentStep = targetStep;

            // Update Progress Bar width
            const progressBar = document.getElementById('stepProgressBar');
            if (targetStep === 1) progressBar.style.width = '0%';
            if (targetStep === 2) progressBar.style.width = '50%';
            if (targetStep === 3) progressBar.style.width = '100%';

            // Update Step Badges & Labels
            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById(`stepDot-${i}`);
                const label = document.getElementById(`stepLabel-${i}`);
                const icon = document.getElementById(`stepIcon-${i}`);

                if (i < targetStep) {
                    // Completed step
                    dot.className = "w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all duration-200 bg-emerald-500 text-white ring-4 ring-emerald-500/20";
                    icon.textContent = "check";
                    label.className = "mt-2 text-xs sm:text-sm font-semibold text-emerald-600";
                } else if (i === targetStep) {
                    // Active step
                    dot.className = "w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-all duration-200 bg-[#03c4ce] text-white ring-4 ring-[#03c4ce]/30 scale-105";
                    if (i === 1) icon.textContent = "person";
                    if (i === 2) icon.textContent = "school";
                    if (i === 3) icon.textContent = "verified";
                    label.className = "mt-2 text-xs sm:text-sm font-bold text-[#03c4ce]";
                } else {
                    // Pending step
                    dot.className = "w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shadow-sm transition-all duration-200 bg-white text-slate-400 border-2 border-slate-200";
                    if (i === 1) icon.textContent = "person";
                    if (i === 2) icon.textContent = "school";
                    if (i === 3) icon.textContent = "verified";
                    label.className = "mt-2 text-xs sm:text-sm font-medium text-slate-400";
                }

                // Show/hide step sections
                const section = document.getElementById(`stepSection-${i}`);
                if (i === targetStep) {
                    section.classList.remove('step-hidden');
                } else {
                    section.classList.add('step-hidden');
                }
            }

            // Smooth scroll to top of form card
            document.getElementById('formCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Navigation Handler: Next Step
        function nextStep(fromStep) {
            if (fromStep === 1) {
                if (!validateStep1()) {
                    showErrorBanner('Please fill in all required personal information fields.');
                    return;
                }
                updateStepperUI(2);
            } else if (fromStep === 2) {
                if (!validateStep2()) {
                    showErrorBanner('Please complete all required education & course details.');
                    return;
                }
                updateSummary();
                updateStepperUI(3);
            }
        }

        // Navigation Handler: Previous Step
        function prevStep(fromStep) {
            hideAllErrors();
            updateStepperUI(fromStep - 1);
        }

        // Stepper Header click: allows navigating back to completed steps
        function goToStep(targetStep) {
            if (targetStep === currentStep) return;

            if (targetStep < currentStep) {
                // Navigating back is always allowed
                hideAllErrors();
                updateStepperUI(targetStep);
            } else if (targetStep === 2) {
                if (validateStep1()) {
                    updateStepperUI(2);
                } else {
                    showErrorBanner('Please complete Step 1 first.');
                }
            } else if (targetStep === 3) {
                if (validateStep1() && validateStep2()) {
                    updateSummary();
                    updateStepperUI(3);
                } else {
                    showErrorBanner('Please complete Steps 1 and 2 first.');
                }
            }
        }

        function showErrorBanner(msg) {
            const errorAlert = document.getElementById('formErrorAlert');
            const errorMsg = document.getElementById('formErrorMessage');
            errorMsg.textContent = msg;
            errorAlert.classList.remove('hidden');
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Final Form Submission Handler (Step 3 Submit)
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all steps before submitting
            if (!validateStep1()) {
                updateStepperUI(1);
                showErrorBanner('Please complete all required personal details in Step 1.');
                return;
            }

            if (!validateStep2()) {
                updateStepperUI(2);
                showErrorBanner('Please complete all required education details in Step 2.');
                return;
            }

            const terms = document.getElementById('terms').checked;
            if (!terms) {
                document.getElementById('err-terms').classList.remove('hidden');
                showErrorBanner('You must accept the Terms & Conditions to proceed.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalBtnHTML = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing Application...
            `;

            // Prepare Form Data with +91 prefix
            const form = this;
            const formData = new FormData(form);
            const rawPhone = phoneInput.value.replace(/\D/g, '');
            formData.set('phone', '+91' + rawPhone);

            fetch('process_registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    showErrorBanner(data.message || 'An error occurred. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                }
            })
            .catch(error => {
                console.error('Registration AJAX Error:', error);
                showErrorBanner('A network error occurred. Please check your connection and try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            });
        });
    </script>
</body>
</html>
