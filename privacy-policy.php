<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
    <title>Privacy Policy - LogixCode</title>
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


        /* Hero Section */
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

        /* Content Section */
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
            content: '✓';
            position: absolute;
            left: 0;
            color: #03c1d1;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .highlight-box {
            background: rgba(3, 193, 209, 0.08);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #03c1d1;
        }

        .highlight-box p {
            margin-bottom: 0;
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

        /* Responsive */
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

            .intro-text {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <?php include './includes/navbar.php';?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><span class="highlight">Privacy</span> Policy</h1>
                <p class="last-updated">Last Updated: February 5, 2026</p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-box">
                <div class="intro-text">
                    <p><strong>LogixCode</strong> is committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or enroll in our training programs.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">1</span> Information We Collect</h2>
                    <p>We collect information that you provide directly to us when you:</p>
                    <ul>
                        <li>Register for courses or training programs</li>
                        <li>Fill out admission or inquiry forms</li>
                        <li>Subscribe to our newsletters or updates</li>
                        <li>Contact us through email, phone, or contact forms</li>
                        <li>Participate in surveys or feedback forms</li>
                    </ul>
                    <p><strong>Personal Information may include:</strong></p>
                    <ul>
                        <li>Name, email address, phone number</li>
                        <li>Date of birth, gender</li>
                        <li>Educational qualifications and certificates</li>
                        <li>Address and location information</li>
                        <li>Payment and billing information</li>
                        <li>Employment history (if applicable)</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">2</span> How We Use Your Information</h2>
                    <p>LogixCode uses the collected information for the following purposes:</p>
                    <ul>
                        <li><strong>Course Enrollment:</strong> Processing admissions and registrations</li>
                        <li><strong>Communication:</strong> Sending course updates, schedules, and important notifications</li>
                        <li><strong>Placement Services:</strong> Sharing your profile with hiring partners (with consent)</li>
                        <li><strong>Service Improvement:</strong> Analyzing feedback to enhance our training programs</li>
                        <li><strong>Marketing:</strong> Sending promotional materials about new courses (you can opt-out anytime)</li>
                        <li><strong>Legal Compliance:</strong> Meeting regulatory and legal requirements</li>
                        <li><strong>Security:</strong> Protecting against fraud and unauthorized access</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">3</span> Information Sharing and Disclosure</h2>
                    <p>We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:</p>
                    <ul>
                        <li><strong>With Your Consent:</strong> When you authorize us to share information with placement partners</li>
                        <li><strong>Service Providers:</strong> Third-party vendors who assist in our operations (payment processors, email services)</li>
                        <li><strong>Legal Requirements:</strong> When required by law, court order, or government regulations</li>
                        <li><strong>Business Transfers:</strong> In case of merger, acquisition, or sale of assets</li>
                    </ul>
                    <div class="highlight-box">
                        <p><strong>Note:</strong> We ensure all third parties maintain appropriate security measures and use your information only for specified purposes.</p>
                    </div>
                </div>

                <div class="section">
                    <h2><span class="section-number">4</span> Data Security</h2>
                    <p>We implement industry-standard security measures to protect your personal information:</p>
                    <ul>
                        <li>Encrypted data transmission (SSL/TLS)</li>
                        <li>Secure servers with firewall protection</li>
                        <li>Access controls and authentication mechanisms</li>
                        <li>Regular security audits and updates</li>
                        <li>Employee training on data protection</li>
                    </ul>
                    <p>However, no method of transmission over the internet is 100% secure. While we strive to protect your data, we cannot guarantee absolute security.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">5</span> Cookies and Tracking Technologies</h2>
                    <p>Our website uses cookies and similar technologies to:</p>
                    <ul>
                        <li>Remember your preferences and settings</li>
                        <li>Analyze website traffic and user behavior</li>
                        <li>Provide personalized content and advertisements</li>
                        <li>Improve website functionality and user experience</li>
                    </ul>
                    <p>You can control cookie preferences through your browser settings. Disabling cookies may affect website functionality.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">6</span> Your Rights and Choices</h2>
                    <p>You have the following rights regarding your personal information:</p>
                    <ul>
                        <li><strong>Access:</strong> Request a copy of your personal data we hold</li>
                        <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                        <li><strong>Deletion:</strong> Request deletion of your personal data (subject to legal requirements)</li>
                        <li><strong>Opt-Out:</strong> Unsubscribe from marketing communications</li>
                        <li><strong>Data Portability:</strong> Receive your data in a structured format</li>
                        <li><strong>Withdraw Consent:</strong> Revoke consent for data processing</li>
                    </ul>
                </div>

                <div class="section">
                    <h2><span class="section-number">7</span> Data Retention</h2>
                    <p>We retain your personal information for as long as necessary to:</p>
                    <ul>
                        <li>Fulfill the purposes outlined in this policy</li>
                        <li>Comply with legal and regulatory obligations</li>
                        <li>Resolve disputes and enforce agreements</li>
                        <li>Maintain course completion records and certificates</li>
                    </ul>
                    <p>Student records are typically retained for 7 years after course completion.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">8</span> Children's Privacy</h2>
                    <p>Our services are not directed to individuals under 18 years of age. We do not knowingly collect personal information from children. If you are under 18, please have a parent or guardian provide consent before sharing any information.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">9</span> Third-Party Links</h2>
                    <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to review their privacy policies.</p>
                </div>

                <div class="section">
                    <h2><span class="section-number">10</span> Changes to Privacy Policy</h2>
                    <p>We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements. The "Last Updated" date will be revised accordingly. Continued use of our services after changes constitutes acceptance of the updated policy.</p>
                </div>

                <div class="contact-box">
                    <h3>Questions About Privacy?</h3>
                    <p>If you have questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
                    <p><strong>Email:</strong> <a href="mailto:privacy@logixcode.com">privacy@logixcode.com</a></p>
                    <p><strong>Phone:</strong> +91 8467898854</p>
                    <p><strong>Address:</strong> Shri Balaji Chauraha, Kanpur, Uttar Pradesh</p>
                </div>
            </div>
        </div>
    </section>
     <?php include './includes/footer.php';?>
</body>
</html>