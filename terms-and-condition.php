<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
    <title>Terms and Conditions - LogixCode</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #2E2E2E;
            line-height: 1.6;
        }

    

        .hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
            padding: 80px 0 60px;
            text-align: center;
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
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .hero .highlight {
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .last-updated {
            font-size: 1rem;
            color: #555;
            margin-top: 10px;
        }

        .content-section {
            padding: 60px 0;
            background: #ffffff;
        }

        .content-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(3, 193, 209, 0.2);
        }

        .intro-text {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 40px;
            padding: 20px;
            background: rgba(3, 193, 209, 0.05);
            border-left: 4px solid #03c1d1;
            border-radius: 8px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #03c1d1;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-number {
            width: 35px;
            height: 35px;
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
        }

        .section p {
            font-size: 1rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .section ul {
            list-style: none;
            margin: 15px 0;
        }

        .section ul li {
            padding: 10px 0 10px 30px;
            position: relative;
            color: #555;
            line-height: 1.7;
        }

        .section ul li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #03c1d1;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .warning-box p {
            color: #856404;
            margin-bottom: 0;
        }

        .important-box {
            background: rgba(220, 53, 69, 0.08);
            border-left: 4px solid #dc3545;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .important-box p {
            color: #721c24;
            margin-bottom: 0;
            font-weight: 600;
        }

        .contact-box {
            background: linear-gradient(135deg, #03c1d1 0%, #0abcd6 100%);
            color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
            text-align: center;
        }

        .contact-box h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .contact-box p {
            color: #ffffff;
            margin-bottom: 10px;
        }

        .contact-box a {
            color: #ffffff;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 60px 0 40px;
            }

            .content-section {
                padding: 40px 0;
            }

            .content-box {
                padding: 25px 20px;
            }

            .section h2 {
                font-size: 1.5rem;
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
     <?php include './includes/navbar.php';?>
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Terms and <span class="highlight">Conditions</span></h1>
                <p class="last-updated">Last Updated: February 5, 2026</p>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="content-box">
                <div class="intro-text">
                    <p><strong>Welcome to LogixCode!</strong> These Terms and Conditions govern your use of our website and enrollment in our training programs. By accessing our services, you agree to be bound by these terms. Please read them carefully.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">1</span> Acceptance of Terms</h2>
                    <p>By registering for any course, using our website, or accessing our services, you agree to:</p>
                    <ul>
                        <li>Comply with all terms and conditions outlined in this document</li>
                        <li>Provide accurate and complete information during registration</li>
                        <li>Maintain the confidentiality of your account credentials</li>
                        <li>Accept responsibility for all activities under your account</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">2</span> Eligibility and Admission</h2>
                    <p><strong>To enroll in LogixCode programs, you must:</strong></p>
                    <ul>
                        <li>Be at least 18 years of age, or have parental/guardian consent</li>
                        <li>Meet the minimum educational qualifications for the chosen course</li>
                        <li>Provide valid identification and educational documents</li>
                        <li>Complete the admission process and pay required fees</li>
                    </ul>
                    <p>LogixCode reserves the right to reject any application without providing specific reasons.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">3</span> Course Fees and Payment</h2>
                    <p><strong>Payment Terms:</strong></p>
                    <ul>
                        <li>All fees must be paid as per the schedule provided at enrollment</li>
                        <li>Payment can be made via cash, bank transfer, UPI, or online payment gateways</li>
                        <li>Fees are subject to change; enrolled students pay the fee applicable at enrollment</li>
                        <li>Late payment may result in suspension of access to course materials</li>
                        <li>Partial payments must be cleared before course completion</li>
                    </ul>
                    <div class="important-box">
                        <p><strong>IMPORTANT:</strong> All course fees are NON-REFUNDABLE once the course has commenced. Please refer to our Refund and Cancellation Policy for details.</p>
                    </div>
                </div>

                <div class="section">
                    <h2><span class="section-number">4</span> Course Duration and Schedule</h2>
                    <ul>
                        <li>Course duration is as specified in the program description</li>
                        <li>LogixCode reserves the right to modify schedules with reasonable notice</li>
                        <li>Students must maintain minimum 75% attendance to be eligible for certification</li>
                        <li>Make-up classes may be provided at the discretion of the institute</li>
                        <li>Course extensions beyond scheduled duration may incur additional fees</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">5</span> Student Responsibilities</h2>
                    <p><strong>As a student, you agree to:</strong></p>
                    <ul>
                        <li>Attend classes regularly and punctually</li>
                        <li>Complete assignments and projects within deadlines</li>
                        <li>Maintain professional conduct and respect towards staff and fellow students</li>
                        <li>Not share or distribute course materials without permission</li>
                        <li>Bring your own laptop/device if required by the course</li>
                        <li>Not engage in any disruptive or illegal activities on premises</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">6</span> Intellectual Property Rights</h2>
                    <p>All course materials, including but not limited to:</p>
                    <ul>
                        <li>Video lectures and recorded sessions</li>
                        <li>Study materials, notes, and presentations</li>
                        <li>Source code, projects, and assignments</li>
                        <li>LogixCode logo, branding, and trademarks</li>
                    </ul>
                    <p>...are the intellectual property of LogixCode and protected by copyright laws. Students are granted a limited, non-transferable license for personal educational use only.</p>
                    <div class="warning-box">
                        <p><strong>Prohibited Actions:</strong> Copying, distributing, selling, or publicly sharing course materials without written permission is strictly prohibited and may result in legal action.</p>
                    </div>
                </div>

                <div class="section">
                    <h2><span class="section-number">7</span> Certification</h2>
                    <p><strong>Certificates will be issued upon:</strong></p>
                    <ul>
                        <li>Successful completion of the course curriculum</li>
                        <li>Maintaining minimum 75% attendance</li>
                        <li>Completion of all assignments and projects</li>
                        <li>Clearing any required assessments</li>
                        <li>Full payment of course fees</li>
                    </ul>
                    <p>Certificates are issued in digital format. Physical certificates may be provided upon request for an additional fee.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">8</span> Placement Assistance</h2>
                    <p><strong>LogixCode provides placement assistance, which includes:</strong></p>
                    <ul>
                        <li>Resume building and interview preparation</li>
                        <li>Referrals to hiring partners</li>
                        <li>Job opportunity notifications</li>
                    </ul>
                    <div class="warning-box">
                        <p><strong>Important Notice:</strong> Placement assistance does NOT guarantee employment. Job placement depends on individual performance, market conditions, and company requirements.</p>
                    </div>
                </div>

                <div class="section">
                    <h2><span class="section-number">9</span> Code of Conduct</h2>
                    <p><strong>Students must adhere to the following:</strong></p>
                    <ul>
                        <li>Professional behavior in all interactions</li>
                        <li>Respect for diversity and inclusion</li>
                        <li>No harassment, discrimination, or bullying</li>
                        <li>No use of offensive language or behavior</li>
                        <li>Compliance with institute rules and regulations</li>
                    </ul>
                    <p>Violation may result in warnings, suspension, or expulsion without refund.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">10</span> Termination and Suspension</h2>
                    <p><strong>LogixCode reserves the right to terminate or suspend your enrollment if:</strong></p>
                    <ul>
                        <li>You violate these Terms and Conditions</li>
                        <li>You engage in misconduct or illegal activities</li>
                        <li>You fail to pay fees as per the schedule</li>
                        <li>You provide false information during admission</li>
                    </ul>
                    <p>No refund will be provided in case of termination due to violation.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">11</span> Limitation of Liability</h2>
                    <p>LogixCode shall not be liable for:</p>
                    <ul>
                        <li>Any indirect, incidental, or consequential damages</li>
                        <li>Loss of data or equipment damage</li>
                        <li>Delays or interruptions in service delivery</li>
                        <li>Third-party actions or failures</li>
                        <li>Job placement outcomes or salary expectations</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">12</span> Privacy and Data Protection</h2>
                    <p>Your personal information will be handled in accordance with our Privacy Policy. By accepting these terms, you consent to the collection, use, and storage of your data as described in our Privacy Policy.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">13</span> Modifications to Terms</h2>
                    <p>LogixCode reserves the right to modify these Terms and Conditions at any time. Changes will be effective upon posting on our website. Continued use of our services after changes constitutes acceptance of the modified terms.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">14</span> Governing Law and Jurisdiction</h2>
                    <p>These Terms and Conditions are governed by the laws of India. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of courts in Kanpur, Uttar Pradesh.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">15</span> Contact Information</h2>
                    <p>For questions regarding these Terms and Conditions, please contact us at:</p>
                </div>

                <div class="contact-box">
                    <h3>Get in Touch</h3>
                    <p><strong>Email:</strong> <a href="mailto:info@logixcode.com">info@logixcode.com</a></p>
                    <p><strong>Phone:</strong> +91 8467898854</p>
                    <p><strong>Address:</strong> Shri Balaji Chauraha, Kanpur, Uttar Pradesh</p>
                </div>
            </div>
        </div>
    </section>
     <?php include './includes/footer.php';?>
</body>
</html>