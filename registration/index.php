<?php
/**
 * Student Registration Form
 * registration/index.php
 */ 
session_start();
require_once __DIR__ . '/../config/db.php';

// Generate CSRF token
$csrfToken = generateCSRF();

// Payment configuration
define('PAYMENT_AMOUNT', REGISTRATION_FEE); // Using global config
define('PAYMENT_CURRENCY', 'INR');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - <?php echo INSTITUTE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .registration-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .form-header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .form-header p {
            color: #666;
        }
        .section-header {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 25px;
            margin-top: 30px;
        }
        .section-header h3 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 50px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .payment-info {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>  

    <div class="registration-container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="bi bi-mortarboard-fill text-primary"></i> Student Registration</h1>
                <p>Please fill out the form completely. All fields marked with <span class="text-danger">*</span> are mandatory.</p>
            </div>

            <div class="payment-info">
                <h5><i class="bi bi-info-circle-fill"></i> Important Information</h5>
                <ul class="mb-0">
                    <li>Registration fee: <strong>₹<?php echo number_format(PAYMENT_AMOUNT, 2); ?></strong></li>
                    <li>Payment is mandatory to complete registration</li>
                    <li>You will receive a confirmation email after successful payment</li>
                </ul>
            </div>

            <form id="registrationForm" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

                <!-- Section 1: Personal Information -->
                <div class="section-header">
                    <h3><i class="bi bi-person-fill"></i> Personal Information</h3>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label required-field">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                        <div class="invalid-feedback">Please enter your first name.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label required-field">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                        <div class="invalid-feedback">Please enter your last name.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label required-field">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label required-field">Phone Number</label>
                        <!-- FIX 1: pattern aur duplicate invalid-feedback hatayi, sirf ek rakhi -->
                        <input type="tel"
                               class="form-control"
                               id="phone"
                               name="phone"
                               inputmode="numeric"
                               maxlength="10"
                               placeholder="10 digit mobile number"
                               required>
                        <!-- FIX 2: Sirf EK invalid-feedback div — duplicate wala hataya -->
                        <div class="invalid-feedback" id="phoneError">Please enter a valid 10-digit mobile number.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label required-field">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                        <div class="invalid-feedback">Please select your date of birth.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required-field">Gender</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                <label class="form-check-label" for="other">Other</label>
                            </div>
                        </div>
                        <div class="invalid-feedback">Please select your gender.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label required-field">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    <div class="invalid-feedback">Please enter your address.</div>
                </div>

                <!-- Section 2: Educational Background -->
                <div class="section-header">
                    <h3><i class="bi bi-book-fill"></i> Educational Background</h3>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="qualification" class="form-label required-field">Highest Qualification</label>
                        <select class="form-select" id="qualification" name="qualification" required>
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
                        <div class="invalid-feedback">Please select your qualification.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="percentage" class="form-label required-field">Percentage / CGPA</label>
                        <input type="text" class="form-control" id="percentage" name="percentage" 
                               placeholder="e.g., 85% or 8.5 CGPA" required>
                        <div class="invalid-feedback">Please enter your percentage or CGPA.</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="college" class="form-label">College / University</label>
                        <input type="text" class="form-control" id="college" name="college">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="yearOfPassing" class="form-label">Year of Passing</label>
                        <input type="text" class="form-control" id="yearOfPassing" name="yearOfPassing" 
                               pattern="^[0-9]{4}$" placeholder="YYYY">
                        <div class="invalid-feedback">Please enter a valid year (YYYY).</div>
                    </div>
                </div>

                <!-- Section 3: Program Selection -->
                <div class="section-header">
                    <h3><i class="bi bi-laptop-fill"></i> Program Selection</h3>
                </div>

                <div class="mb-3">
                    <label for="program" class="form-label required-field">Select Program</label>
                    <select class="form-select" id="program" name="program" required>
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
                    <div class="invalid-feedback">Please select a program.</div>
                </div>

                <!-- Section 4: Additional Information -->
                <div class="section-header">
                    <h3><i class="bi bi-info-circle-fill"></i> Additional Information</h3>
                </div>

                <div class="mb-3">
                    <label for="experience" class="form-label">Prior Experience (if any)</label>
                    <textarea class="form-control" id="experience" name="experience" rows="3" 
                              placeholder="Describe any relevant experience or projects..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="motivation" class="form-label">Why do you want to join this program?</label>
                    <textarea class="form-control" id="motivation" name="motivation" rows="3" 
                              placeholder="Share your motivation and goals..."></textarea>
                </div>

                <!-- Section 5: Consent & Preferences -->
                <div class="section-header">
                    <h3><i class="bi bi-check-circle-fill"></i> Consent & Preferences</h3>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            <span class="required-field">I agree to the terms and conditions</span>
                        </label>
                        <div class="invalid-feedback">You must agree to the terms and conditions.</div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="updates" name="updates">
                        <label class="form-check-label" for="updates">
                            I want to receive updates and notifications about courses and events
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg btn-submit" id="submitBtn">
                        <i class="bi bi-send-fill"></i> Submit Application & Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ---------------------------------------------------------------
        // FIX 3: Phone — sirf numbers allow karo, live validation
        // ---------------------------------------------------------------
        const phoneInput = document.getElementById('phone');

        // Typing ke waqt non-numeric characters block karo
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);

            if (this.value.length === 10 && /^[6-9][0-9]{9}$/.test(this.value)) {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.length > 0) {
                this.setCustomValidity('invalid');
                this.classList.remove('is-valid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // ---------------------------------------------------------------
        // Form submit
        // ---------------------------------------------------------------
        document.getElementById('registrationForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;

            // FIX 4: Phone validate PEHLE karo, checkValidity() se PEHLE
            const rawPhone = phoneInput.value.replace(/\D/g, '');

            if (rawPhone.length !== 10 || !/^[6-9][0-9]{9}$/.test(rawPhone)) {
                phoneInput.setCustomValidity('Please enter a valid 10-digit mobile number.');
                phoneInput.classList.add('is-invalid');
            } else {
                phoneInput.setCustomValidity('');
            }

            // Ab baaki form validate karo
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return false;
            }

            // Gender check
            const genderSelected = document.querySelector('input[name="gender"]:checked');
            if (!genderSelected) {
                alert('Please select your gender.');
                return false;
            }

            // FIX 5: +91 prepend karo submit se pehle (backend expect karta hai)
            phoneInput.value = '+91' + rawPhone;

            // Submit button disable karo
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            // AJAX submit
            const formData = new FormData(form);

            fetch('process_registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'An error occurred. Please try again.');
                    // Phone field reset karo display ke liye (+91 hata do)
                    phoneInput.value = rawPhone;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-fill"></i> Submit Application & Proceed to Payment';
                }
            })
            .catch(error => {
                console.error(error);
                alert('An error occurred. Please try again.');
                phoneInput.value = rawPhone;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send-fill"></i> Submit Application & Proceed to Payment';
            });
        });

        // DOB max date set karo (10 saal se kam nahi)
        const dobInput = document.getElementById('dob');
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - 10);
        dobInput.max = maxDate.toISOString().split('T')[0];
    </script>
</body>
</html>
