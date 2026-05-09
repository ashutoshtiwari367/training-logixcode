<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
    <title>About Us - LogixCode</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
            padding: 100px 0 80px;
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
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .hero .highlight {
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.3rem;
            color: #555;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Who We Are Section */
        .who-we-are {
            padding: 80px 0;
            background: #ffffff;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-badge {
            display: inline-block;
            background: rgba(3, 193, 209, 0.1);
            color: #03c1d1;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            border: 1px solid rgba(3, 193, 209, 0.3);
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            margin-top: 50px;
        }

        .about-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .about-image img {
            width: 100%;
            height: 450px;
            object-fit: cover;
        }

        .about-text h3 {
            font-size: 2rem;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .about-text p {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        /* Core Values Section */
        .core-values {
            padding: 80px 0;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .value-card {
            background: #ffffff;
            padding: 35px 25px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(3, 193, 209, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
        }

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(3, 193, 209, 0.2);
            border-color: #03c1d1;
        }

        .value-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2.5rem;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .value-card:hover .value-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .value-card h3 {
            font-size: 1.4rem;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .value-card p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        /* What We Offer Section */
        .what-we-offer {
            padding: 80px 0;
            background: #ffffff;
        }

        .offerings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
            margin-top: 50px;
        }

        .offering-card {
            background: #ffffff;
            border: 1px solid rgba(3, 193, 209, 0.25);
            border-radius: 20px;
            padding: 35px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .offering-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(3, 193, 209, 0.2);
            border-color: #03c1d1;
        }

        .offering-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #ffffff;
        }

        .offering-card h3 {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .offering-card p {
            font-size: 1rem;
            color: #555;
            line-height: 1.7;
        }

        /* Vision & Mission Section */
        .vision-mission {
            padding: 80px 0;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }

        .vm-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 50px;
        }

        .vm-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            border: 2px solid rgba(3, 193, 209, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .vm-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, #03c1d1, #0abcd6);
        }

        .vm-card h3 {
            font-size: 2rem;
            color: #03c1d1;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .vm-icon {
            font-size: 2.5rem;
        }

        .vm-card p {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.8;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #03c1d1 0%, #0abcd6 100%);
            color: #ffffff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }

        .stat-card {
            text-align: center;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Why Choose Us Section */
        .why-choose {
            padding: 80px 0;
            background: #ffffff;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .feature-box {
            background: rgba(3, 193, 209, 0.05);
            padding: 30px 25px;
            border-radius: 15px;
            border: 1px solid rgba(3, 193, 209, 0.2);
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            background: rgba(3, 193, 209, 0.1);
            border-color: #03c1d1;
            transform: translateY(-5px);
        }

        .feature-box h4 {
            font-size: 1.2rem;
            color: #1a1a1a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-icon-small {
            font-size: 1.5rem;
        }

        .feature-box p {
            color: #555;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(45deg, #03c1d1, #0abcd6);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(3, 193, 209, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(3, 193, 209, 0.4);
        }

        .btn-secondary {
            display: inline-block;
            padding: 15px 40px;
            background: transparent;
            border: 2px solid #03c1d1;
            color: #03c1d1;
            text-decoration: none;
            font-weight: 600;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #03c1d1;
            color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .about-content,
            .vm-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero {
                padding: 80px 0 60px;
            }

            .who-we-are,
            .core-values,
            .what-we-offer,
            .vision-mission,
            .stats-section,
            .why-choose,
            .cta-section {
                padding: 60px 0;
            }

            .values-grid,
            .offerings-grid,
            .features-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .about-image img {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include './includes/navbar.php';?>
    <!-- Hero Section -->
    <section class="hero" style="margin-top: 80px;">
        <div class="container">
            <div class="hero-content">
                <h1>About <span class="highlight">LogixCode</span></h1>
                <p>Transforming Tech Learners into Industry Leaders</p>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Our Story</span>
                <h2 class="section-title">Who We Are</h2>
            </div>

            <div class="about-content">
                <div class="about-image">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769516391/5a2a74e4-55f5-4439-92d7-412a52eac260_gzj8u4.png" alt="LogixCode Team">
                </div>
                <div class="about-text">
                    <h3>Creating Industry-Ready Professionals</h3>
                    <p>At LogixCode, we focus on creating industry-ready professionals through practical, project-based training designed around current market needs. Our programs are guided by experienced mentors who bring real industry knowledge into every session, ensuring students gain confidence and hands-on exposure.</p>
                    
                    <p>We offer training in <strong>Web Development, Software Development, App Development, Programming Technologies, Internship Programs, and Placement Assistance</strong>. Each course is structured to strengthen problem-solving skills, technical logic, and professional growth.</p>
                    
                    <p>Our core values are <strong>Trust, Quality, Innovation, Integrity, and Transparency</strong>. With these principles, our vision is to become the first choice for students and businesses seeking reliable, result-oriented IT training and technology solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="core-values">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Our Foundation</span>
                <h2 class="section-title">Core Values</h2>
                <p style="color: #555; font-size: 1.1rem; max-width: 700px; margin: 20px auto 0;">The principles that guide everything we do at LogixCode</p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Trust</h3>
                    <p>Building lasting relationships with students and partners through reliability and consistent delivery of quality education.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">⭐</div>
                    <h3>Quality</h3>
                    <p>Maintaining the highest standards in curriculum, training methodology, and student support services.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">💡</div>
                    <h3>Innovation</h3>
                    <p>Continuously evolving our programs to match industry trends and technological advancements.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">✓</div>
                    <h3>Integrity</h3>
                    <p>Operating with honesty and strong moral principles in all our interactions and commitments.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🔍</div>
                    <h3>Transparency</h3>
                    <p>Maintaining open communication and clarity in all aspects of our training programs and processes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Offer Section -->
    <section class="what-we-offer">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Our Services</span>
                <h2 class="section-title">What We Offer</h2>
                <p style="color: #555; font-size: 1.1rem; max-width: 700px; margin: 20px auto 0;">Comprehensive training solutions for aspiring tech professionals</p>
            </div>

            <div class="offerings-grid">
                <div class="offering-card">
                    <div class="offering-icon">🌐</div>
                    <h3>Web Development</h3>
                    <p>Master modern web technologies including HTML, CSS, JavaScript, React, Node.js, and full-stack frameworks. Build responsive, dynamic websites and web applications.</p>
                </div>

                <div class="offering-card">
                    <div class="offering-icon">💻</div>
                    <h3>Software Development</h3>
                    <p>Learn core programming languages like Java, Python, and C++ with emphasis on software design patterns, algorithms, and enterprise application development.</p>
                </div>

                <div class="offering-card">
                    <div class="offering-icon">📱</div>
                    <h3>App Development</h3>
                    <p>Create powerful mobile applications for Android and iOS using modern frameworks like Flutter, React Native, and native development tools.</p>
                </div>

                <div class="offering-card">
                    <div class="offering-icon">⚙️</div>
                    <h3>Programming Technologies</h3>
                    <p>Comprehensive training in cutting-edge technologies including AI/ML, Data Science, Cloud Computing, and DevOps practices.</p>
                </div>

                <div class="offering-card">
                    <div class="offering-icon">🎓</div>
                    <h3>Internship Programs</h3>
                    <p>Gain real-world experience through structured internship programs with live projects and industry mentorship.</p>
                </div>

                <div class="offering-card">
                    <div class="offering-icon">💼</div>
                    <h3>Placement Assistance</h3>
                    <p>Dedicated placement support with resume building, interview preparation, and connections to 500+ hiring partners.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="vision-mission">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Our Direction</span>
                <h2 class="section-title">Vision & Mission</h2>
            </div>

            <div class="vm-grid">
                <div class="vm-card">
                    <h3><span class="vm-icon">🎯</span> Our Vision</h3>
                    <p>To become the first choice for students and businesses seeking reliable, result-oriented IT training and technology solutions. We envision a future where every student who walks through our doors leaves as an industry-ready professional, equipped with the skills, confidence, and connections needed to excel in the tech industry.</p>
                </div>

                <div class="vm-card">
                    <h3><span class="vm-icon">🚀</span> Our Mission</h3>
                    <p>To create industry-ready professionals through practical, project-based training designed around current market needs. We are committed to providing quality education guided by experienced mentors who bring real industry knowledge, ensuring students gain hands-on exposure and develop problem-solving skills essential for their professional growth.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title" style="color: #ffffff;">Our Impact in Numbers</h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Students Trained</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">100+</span>
                    <span class="stat-label">Hiring Partners</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Expert Trainers</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">95%</span>
                    <span class="stat-label">Placement Rate</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Why LogixCode</span>
                <h2 class="section-title">Why Choose Us</h2>
                <p style="color: #555; font-size: 1.1rem; max-width: 700px; margin: 20px auto 0;">What makes us different from other training institutes</p>
            </div>

            <div class="features-grid">
                <div class="feature-box">
                    <h4><span class="feature-icon-small">👨‍🏫</span> Experienced Mentors</h4>
                    <p>Learn from industry professionals with years of real-world experience</p>
                </div>
                <div class="feature-box">
                    <h4><span class="feature-icon-small">🛠️</span> Hands-On Learning</h4>
                    <p>Project-based training with practical implementation</p>
                </div>
                <div class="feature-box">
                    <h4><span class="feature-icon-small">📚</span> Updated Curriculum</h4>
                    <p>Industry-aligned courses matching current market demands</p>
                </div>
                <div class="feature-box">
                    <h4><span class="feature-icon-small">💯</span> 100% Practical</h4>
                    <p>Focus on real-world applications and live projects</p>
                </div>
                <div class="feature-box">
                    <h4><span class="feature-icon-small">🏆</span> Certification</h4>
                    <p>Industry-recognized certificates upon completion</p>
                </div>
                <div class="feature-box">
                    <h4><span class="feature-icon-small">🤝</span> Lifetime Support</h4>
                    <p>Continuous guidance and placement assistance</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Your Journey?</h2>
            <p>Join thousands of successful professionals who have transformed their careers with LogixCode</p>
            <div class="cta-buttons">
                <a href="/registration" class="btn-primary">Apply for Admission</a>
                <a href="/courses" class="btn-secondary">Explore Courses</a>
            </div>
        </div>
    </section>
    <?php include './includes/footer.php';?>
</body>
</html>