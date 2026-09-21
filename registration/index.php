<?php
/**
 * Student Registration Form
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
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Page Header Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 mb-8 text-center relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-[#03c4ce]/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-[#004f54]/10 rounded-full blur-2xl"></div>
                
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#03c4ce]/10 text-[#03c4ce] mb-4">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight font-heading mb-3">
                    Student Registration
                </h1>
                <p class="text-slate-600 max-w-2xl mx-auto text-base">
                    Begin your career acceleration journey with <?php echo INSTITUTE_NAME; ?>. Fill out the application form below to register.
                </p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'session_expired'): ?>
            <!-- Session Expired Alert -->
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center gap-3">
                <span class="material-symbols-outlined text-amber-600">warning</span>
                <div>
                    <strong>Session Expired:</strong> Your previous registration session timed out. Please fill out the form again to proceed.
                </div>
            </div>
            <?php endif; ?>

            <!-- Important Fee Banner -->
            <div class="bg-gradient-to-r from-slate-900 to-[#004f54] text-white rounded-2xl p-6 md:p-8 shadow-lg mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#03c4ce]/20 text-[#03c4ce] text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-sm">verified</span> Official Application
                    </div>
                    <h3 class="text-xl font-bold">Mandatory Registration Fee</h3>
                    <p class="text-slate-300 text-sm">
                        Complete payment is required to finalize your registration and secure your seat.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-xl border border-white/10 text-center min-w-[180px]">
                    <span class="block text-xs uppercase text-slate-300 font-semibold tracking-wider mb-1">Registration Fee</span>
                    <span class="text-3xl font-extrabold text-[#03c4ce]">₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></span>
                </div>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl border border-slate-100">
                <form id="registrationForm" method="POST" class="space-y-8" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

                    <!-- Error Alert Container -->
                    <div id="formErrorAlert" class="hidden p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium flex items-center gap-3">
                        <span class="material-symbols-outlined text-rose-500">error</span>
                        <span id="formErrorMessage">Please correct the errors below.</span>
                    </div>

                    <!-- Section 1: Personal Information -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">person</span>
                            </span>
                            <h3 class="text-xl font-bold text-slate-900 font-heading">1. Personal Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="firstName" class="block text-sm font-semibold text-slate-700 mb-2">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" id="firstName" name="firstName" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="Enter your first name">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-firstName">Please enter your first name.</span>
                            </div>

                            <div>
                                <label for="lastName" class="block text-sm font-semibold text-slate-700 mb-2">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" id="lastName" name="lastName" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="Enter your last name">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-lastName">Please enter your last name.</span>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" id="email" name="email" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="name@example.com">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-email">Please enter a valid email address.</span>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold text-sm">+91</span>
                                    <input type="tel" id="phone" name="phone" inputmode="numeric" maxlength="10" required
                                           class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 pl-14 pr-4 shadow-sm transition-all"
                                           placeholder="10-digit mobile number">
                                </div>
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-phone">Please enter a valid 10-digit mobile number starting with 6-9.</span>
                            </div>

                            <div>
                                <label for="dob" class="block text-sm font-semibold text-slate-700 mb-2">Date of Birth <span class="text-rose-500">*</span></label>
                                <input type="date" id="dob" name="dob" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-dob">Please select your date of birth (minimum 10 years old).</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Gender <span class="text-rose-500">*</span></label>
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

                        <div class="mt-6">
                            <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Address <span class="text-rose-500">*</span></label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                      placeholder="Enter your full residential address"></textarea>
                            <span class="text-rose-500 text-xs mt-1 hidden" id="err-address">Please enter your address.</span>
                        </div>
                    </div>

                    <!-- Section 2: Educational Background -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">workspace_premium</span>
                            </span>
                            <h3 class="text-xl font-bold text-slate-900 font-heading">2. Educational Background</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="qualification" class="block text-sm font-semibold text-slate-700 mb-2">Highest Qualification <span class="text-rose-500">*</span></label>
                                <select id="qualification" name="qualification" required
                                        class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all">
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
                                <label for="percentage" class="block text-sm font-semibold text-slate-700 mb-2">Percentage / CGPA <span class="text-rose-500">*</span></label>
                                <input type="text" id="percentage" name="percentage" required
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="e.g., 85% or 8.5 CGPA">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-percentage">Please enter your percentage or CGPA.</span>
                            </div>

                            <div>
                                <label for="college" class="block text-sm font-semibold text-slate-700 mb-2">College / University</label>
                                <input type="text" id="college" name="college"
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="Enter your college or university name">
                            </div>

                            <div>
                                <label for="yearOfPassing" class="block text-sm font-semibold text-slate-700 mb-2">Year of Passing</label>
                                <input type="text" id="yearOfPassing" name="yearOfPassing" maxlength="4" pattern="^[0-9]{4}$"
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                       placeholder="YYYY (e.g. 2024)">
                                <span class="text-rose-500 text-xs mt-1 hidden" id="err-yearOfPassing">Please enter a valid 4-digit year.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Program Selection -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">laptop_mac</span>
                            </span>
                            <h3 class="text-xl font-bold text-slate-900 font-heading">3. Program Selection</h3>
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-semibold text-slate-700 mb-2">Select Desired Program <span class="text-rose-500">*</span></label>
                            <select id="program" name="program" required
                                    class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all">
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
                    </div>

                    <!-- Section 4: Additional Information -->
                    <div>
                        <div class="flex items-center gap-3 pb-3 mb-6 border-b border-slate-100">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                                <span class="material-symbols-outlined text-lg">info</span>
                            </span>
                            <h3 class="text-xl font-bold text-slate-900 font-heading">4. Additional Information</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="experience" class="block text-sm font-semibold text-slate-700 mb-2">Prior Technical Experience (Optional)</label>
                                <textarea id="experience" name="experience" rows="3"
                                          class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                          placeholder="Describe any relevant coding experience, languages, or projects..."></textarea>
                            </div>

                            <div>
                                <label for="motivation" class="block text-sm font-semibold text-slate-700 mb-2">Why do you want to join this program? (Optional)</label>
                                <textarea id="motivation" name="motivation" rows="3"
                                          class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                          placeholder="Share your goals and expectations..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Consent & Submission -->
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms" name="terms" required
                                   class="mt-1 rounded text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <label for="terms" class="text-sm text-slate-700 font-medium cursor-pointer">
                                I agree to the <a href="<?= BASE_URL ?>terms-and-condition.php" target="_blank" class="text-[#03c4ce] hover:underline font-semibold">Terms & Conditions</a> and declare that all provided details are accurate. <span class="text-rose-500">*</span>
                            </label>
                        </div>
                        <span class="text-rose-500 text-xs block hidden" id="err-terms">You must agree to the terms to proceed.</span>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="updates" name="updates"
                                   class="mt-1 rounded text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <label for="updates" class="text-sm text-slate-600 cursor-pointer">
                                Keep me updated about upcoming workshops, webinars, and course offerings.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" id="submitBtn"
                                class="w-full py-4 px-8 rounded-2xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-extrabold text-lg shadow-xl shadow-[#03c4ce]/25 transition-all duration-300 flex items-center justify-center gap-3">
                            <span>Submit Application & Proceed to Payment (₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?>)</span>
                            <span class="material-symbols-outlined text-2xl">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
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

        // Form Submit AJAX Handler
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const errorAlert = document.getElementById('formErrorAlert');
            const errorMsg = document.getElementById('formErrorMessage');
            errorAlert.classList.add('hidden');

            // Hide previous inline errors
            document.querySelectorAll('[id^="err-"]').forEach(el => el.classList.add('hidden'));

            let isValid = true;

            // Validate First Name
            const firstName = document.getElementById('firstName').value.trim();
            if (!firstName) {
                document.getElementById('err-firstName').classList.remove('hidden');
                isValid = false;
            }

            // Validate Last Name
            const lastName = document.getElementById('lastName').value.trim();
            if (!lastName) {
                document.getElementById('err-lastName').classList.remove('hidden');
                isValid = false;
            }

            // Validate Email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('err-email').classList.remove('hidden');
                isValid = false;
            }

            // Validate Phone
            const rawPhone = phoneInput.value.replace(/\D/g, '');
            if (rawPhone.length !== 10 || !/^[6-9][0-9]{9}$/.test(rawPhone)) {
                document.getElementById('err-phone').classList.remove('hidden');
                isValid = false;
            }

            // Validate DOB
            const dob = document.getElementById('dob').value;
            if (!dob) {
                document.getElementById('err-dob').classList.remove('hidden');
                isValid = false;
            }

            // Validate Gender
            const genderSelected = document.querySelector('input[name="gender"]:checked');
            if (!genderSelected) {
                document.getElementById('err-gender').classList.remove('hidden');
                isValid = false;
            }

            // Validate Address
            const address = document.getElementById('address').value.trim();
            if (!address) {
                document.getElementById('err-address').classList.remove('hidden');
                isValid = false;
            }

            // Validate Qualification
            const qualification = document.getElementById('qualification').value;
            if (!qualification) {
                document.getElementById('err-qualification').classList.remove('hidden');
                isValid = false;
            }

            // Validate Percentage
            const percentage = document.getElementById('percentage').value.trim();
            if (!percentage) {
                document.getElementById('err-percentage').classList.remove('hidden');
                isValid = false;
            }

            // Validate Year of Passing if provided
            const yearOfPassing = document.getElementById('yearOfPassing').value.trim();
            if (yearOfPassing && !/^[0-9]{4}$/.test(yearOfPassing)) {
                document.getElementById('err-yearOfPassing').classList.remove('hidden');
                isValid = false;
            }

            // Validate Program
            const program = document.getElementById('program').value;
            if (!program) {
                document.getElementById('err-program').classList.remove('hidden');
                isValid = false;
            }

            // Validate Terms
            const terms = document.getElementById('terms').checked;
            if (!terms) {
                document.getElementById('err-terms').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                errorMsg.textContent = 'Please complete all required fields correctly before proceeding.';
                errorAlert.classList.remove('hidden');
                errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            // Disable submit button & show loading state
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

            // Prepare FormData (ensure phone has +91 prefix sent to backend)
            const formData = new FormData(form);
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
                    errorMsg.textContent = data.message || 'An error occurred during registration. Please try again.';
                    errorAlert.classList.remove('hidden');
                    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                }
            })
            .catch(error => {
                console.error('Registration AJAX Error:', error);
                errorMsg.textContent = 'A network error occurred. Please check your connection and try again.';
                errorAlert.classList.remove('hidden');
                errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            });
        });
    </script>
</body>
</html>
