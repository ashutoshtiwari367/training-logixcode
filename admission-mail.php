<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission - LogixCode</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #2E2E2E;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(3, 193, 209, 0.1) 0%, transparent 70%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .hero .highlight {
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.1rem;
            color: #555;
        }

        /* Form Container */
        .form-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(3, 193, 209, 0.2);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #03c1d1;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(3, 193, 209, 0.2);
        }

        .form-section {
            margin-bottom: 35px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: #dc3545;
            margin-left: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            color: #2E2E2E;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #03c1d1;
            box-shadow: 0 0 0 3px rgba(3, 193, 209, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Radio and Checkbox */
        .radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: auto;
            cursor: pointer;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-top: 4px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin-bottom: 0;
            font-weight: 500;
            cursor: pointer;
        }

        /* Submit Button */
        .submit-section {
            margin-top: 30px;
            text-align: center;
        }

        .btn-submit {
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            color: #ffffff;
            padding: 15px 50px;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(3, 193, 209, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(3, 193, 209, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Loading Spinner */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid rgba(3, 193, 209, 0.1);
            border-radius: 50%;
            border-top: 4px solid #03c1d1;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Success Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #03c1d1;
            margin-bottom: 15px;
        }

        .application-id {
            background: rgba(3, 193, 209, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #03c1d1;
        }

        .btn-close {
            background: #03c1d1;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }

            .hero {
                padding: 40px 20px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .form-container {
                padding: 25px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-submit {
                width: 100%;
                padding: 15px 20px;
            }

            .modal-content {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <h1>Apply for <span class="highlight">Admission</span></h1>
            <p>Start your journey towards a successful tech career</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div id="alertMessage" class="alert"></div>
            <div id="loadingSpinner" class="loading">
                <div class="spinner"></div>
                <p style="margin-top: 15px; color: #555;">Submitting your application...</p>
            </div>

            <form id="admissionForm">
                <!-- Personal Information -->
                <div class="form-section">
                    <h2 class="section-title">Personal Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" required placeholder="+91 1234567890">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date of Birth <span class="required">*</span></label>
                            <input type="date" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label>Gender <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" name="gender" value="male" id="male" required>
                                    <label for="male">Male</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="gender" value="female" id="female">
                                    <label for="female">Female</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="gender" value="other" id="other">
                                    <label for="other">Other</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address <span class="required">*</span></label>
                        <textarea name="address" required placeholder="Enter your full address"></textarea>
                    </div>
                </div>

                <!-- Educational Background -->
                <div class="form-section">
                    <h2 class="section-title">Educational Background</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Highest Qualification <span class="required">*</span></label>
                            <select name="qualification" required>
                                <option value="">Select Qualification</option>
                                <option value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="diploma">Diploma</option>
                                <option value="btech">B.Tech/B.E.</option>
                                <option value="bca">BCA</option>
                                <option value="bsc">B.Sc</option>
                                <option value="mca">MCA</option>
                                <option value="mtech">M.Tech</option>
                                <option value="msc">M.Sc</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Percentage/CGPA <span class="required">*</span></label>
                            <input type="text" name="percentage" required placeholder="e.g., 75% or 7.5 CGPA">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>College/University</label>
                            <input type="text" name="college" placeholder="Enter college/university name">
                        </div>
                        <div class="form-group">
                            <label>Year of Passing</label>
                            <input type="text" name="yearOfPassing" placeholder="e.g., 2024">
                        </div>
                    </div>
                </div>

                <!-- Program Selection -->
                <div class="form-section">
                    <h2 class="section-title">Program Selection</h2>
                    <div class="form-group">
                        <label>Select Program <span class="required">*</span></label>
                        <select name="program" required>
                            <option value="">Choose a Program</option>
                            <optgroup label="Training Programs">
                                <option value="summer-training">Summer Training (45-60 Days)</option>
                                <option value="winter-training">Winter Training (45 Days)</option>
                                <option value="industrial-training">Industrial Training (6 Months)</option>
                                <option value="apprenticeship">Apprenticeship Program (3-6 Months)</option>
                            </optgroup>
                            <optgroup label="Technology Courses">
                                <option value="full-stack">Full Stack Development</option>
                                <option value="java">Java Programming</option>
                                <option value="python">Python Development</option>
                                <option value="mern">MERN Stack Development</option>
                                <option value="android">Android Development</option>
                                <option value="data-science">Data Science & Analytics</option>
                                <option value="ai-ml">AI & Machine Learning</option>
                                <option value="frontend">Frontend Development</option>
                                <option value="cloud">Cloud Computing (AWS)</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h2 class="section-title">Additional Information</h2>
                    <div class="form-group">
                        <label>Previous Experience (if any)</label>
                        <textarea name="experience" placeholder="Share any relevant work experience or projects"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Why do you want to join this program?</label>
                        <textarea name="motivation" placeholder="Tell us about your motivation and career goals"></textarea>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-section">
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms">I accept the <a href="#" style="color: #03c1d1;">Terms & Conditions</a> and agree to the <a href="#" style="color: #03c1d1;">Privacy Policy</a> <span class="required">*</span></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="updates" id="updates">
                            <label for="updates">I want to receive updates about courses, events, and special offers</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="submit-section">
                    <button type="submit" class="btn-submit" id="submitBtn">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">✅</div>
            <h2 class="modal-title">Application Submitted Successfully!</h2>
            <p style="color: #555; margin-bottom: 15px;">Thank you for applying to LogixCode. Our team will contact you soon.</p>
            <div class="application-id">
                Application ID: <span id="appId"></span>
            </div>
            <p style="color: #555; font-size: 0.9rem;">Please save this ID for future reference. A confirmation email has been sent to your registered email address.</p>
            <button class="btn-close" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        const form = document.getElementById('admissionForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertMessage = document.getElementById('alertMessage');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const successModal = document.getElementById('successModal');

        function showAlert(message, type) {
            alertMessage.className = `alert alert-${type} show`;
            alertMessage.textContent = message;
            setTimeout(() => {
                alertMessage.className = 'alert';
            }, 5000);
        }

        function showModal(applicationId) {
            document.getElementById('appId').textContent = applicationId;
            successModal.classList.add('show');
        }

        function closeModal() {
            successModal.classList.remove('show');
            form.reset();
            window.scrollTo(0, 0);
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get form data
            const formData = new FormData(form);
            const data = {};
            
            formData.forEach((value, key) => {
                if (key === 'terms' || key === 'updates') {
                    data[key] = form.elements[key].checked;
                } else {