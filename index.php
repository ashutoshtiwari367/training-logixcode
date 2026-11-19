<?php 

ini_set('display_errors', 0); // Production me errors hide karo
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/* =========================
 MAIL CONFIG
 ✅ SECURITY FIX: Password ko .env ya config.php me rakhein
 Is file me kabhi bhi password mat likho!
========================= */

define('MAIL_FROM_EMAIL','info@logixcode.com');
define('MAIL_FROM_NAME','LogixCode Training');
define('MAIL_SMTP_HOST','smtp.hostinger.com');
define('MAIL_SMTP_PORT', 587);
define('MAIL_SMTP_USER','info@logixcode.com');
define('MAIL_SMTP_PASS', getenv('LOGIXCODE_MAIL_PASS') ?: ''); // ✅ FIXED: .env se lo


/* =========================
 FORM HANDLER
========================= */

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $student_name = htmlspecialchars(trim($_POST['full_name'] ?? ''));
    $phone        = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $interest     = htmlspecialchars(trim($_POST['interest'] ?? ''));

    if($student_name && $phone && $interest){
        sendAdmissionEmail($student_name, $phone, $interest);
        header("Location: " . BASE_URL . "?submitted=1");
        exit;
    }
}


/* =========================
 EMAIL FUNCTION
========================= */

function sendAdmissionEmail($student_name, $phone, $interest){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_SMTP_USER;
        $mail->Password   = MAIL_SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_SMTP_PORT;

        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);

        $admins = [
            ['email'=>'ashutoshtiwari9453@gmail.com','name'=>'Ashutosh'],
        ];

        foreach($admins as $admin){
            $mail->addAddress($admin['email'], $admin['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = "New Counseling Request - LogixCode Homepage";
        $mail->Body    = "
            <h2 style='color:#167282;'>New Counseling Request</h2>
            <table cellpadding='8' cellspacing='0' border='1' style='border-collapse:collapse;'>
                <tr><td><b>Name</b></td><td>{$student_name}</td></tr>
                <tr><td><b>Phone</b></td><td>{$phone}</td></tr>
                <tr><td><b>Interest</b></td><td>{$interest}</td></tr>
            </table>
        ";
        $mail->AltBody = "Name: $student_name | Phone: $phone | Interest: $interest";
        $mail->send();

    } catch (Exception $e){
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- ============================================= -->
<!-- ✅ SEO: PRIMARY META TAGS                     -->
<!-- ============================================= -->
<title>IT Training Institute in Kanpur with Placement | LogixCode</title>
<meta name="description" content="Join LogixCode, Kanpur's top IT training institute for Python, Java, MERN Stack & Data Science with 100% placement support. Book your free demo class today!">
<meta name="keywords" content="IT training institute Kanpur, Python course Kanpur, Java training Kanpur, full stack development course Kanpur, MERN stack training Kanpur, data science course Kanpur, computer training institute Kanpur, LogixCode training">
<meta name="author" content="LogixCode Training Institute">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="https://training.logixcode.com/">

<!-- ============================================= -->
<!-- ✅ SEO: OPEN GRAPH (Facebook/WhatsApp share)  -->
<!-- ============================================= -->
<meta property="og:type" content="website">
<meta property="og:title" content="IT Training Institute in Kanpur with Placement | LogixCode">
<meta property="og:description" content="Professional software training in Kanpur. Python, Java, Full Stack, Data Science courses with 100% placement assistance.">
<meta property="og:url" content="https://training.logixcode.com/">
<meta property="og:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_IN">
<meta property="og:site_name" content="LogixCode Training">

<!-- ============================================= -->
<!-- ✅ SEO: TWITTER CARD                          -->
<!-- ============================================= -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="IT Training Institute in Kanpur with Placement | LogixCode">
<meta name="twitter:description" content="Python, Java, MERN Stack, Data Science courses in Kanpur. 500+ students trained, 100% placement support.">
<meta name="twitter:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">

<!-- ============================================= -->
<!-- ✅ SEO: GEO TAGS (Local SEO Kanpur)           -->
<!-- ============================================= -->
<meta name="geo.region" content="IN-UP">
<meta name="geo.placename" content="Kanpur, Uttar Pradesh, India">
<meta name="geo.position" content="26.4499;80.3319">
<meta name="ICBM" content="26.4499, 80.3319">

<!-- ============================================= -->
<!-- ✅ SEO: SCHEMA.ORG (Rich Snippets Google)     -->
<!-- ============================================= -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "EducationalOrganization",
      "@id": "https://training.logixcode.com/#organization",
      "name": "LogixCode Training Institute",
      "alternateName": "LogixCode",
      "url": "https://training.logixcode.com",
      "logo": "https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp",
      "image": "https://res.cloudinary.com/de7mh41io/image/upload/v1769591122/Untitled_design_10_jbnn0e.webp",
      "description": "LogixCode is a leading IT training institute in Kanpur offering professional courses in Python, Java, MERN Stack, Data Science, AI/ML, Android Development, and more.",
      "telephone": "+91-8467898854",
      "email": "info@logixcode.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar",
        "addressLocality": "Kanpur",
        "addressRegion": "Uttar Pradesh",
        "postalCode": "208013",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 26.4499,
        "longitude": 80.3319
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
        "opens": "09:00",
        "closes": "19:00"
      },
      "sameAs": [
        "https://www.logixcode.com"
      ],
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "IT Training Courses in Kanpur",
        "itemListElement": [
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Python Course in Kanpur", "url": "https://training.logixcode.com/courses/python-training-kanpur/", "description": "Build powerful backend applications using Python and Django framework with 100% placement support"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Full Stack MERN Course in Kanpur", "url": "https://training.logixcode.com/courses/full-stack-web-development-kanpur/", "description": "Learn MongoDB, Express, React, and Node.js for full-stack web development"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Java Training in Kanpur", "url": "https://training.logixcode.com/courses/java-programming-kanpur/", "description": "Master Java, Spring Boot, and Hibernate for enterprise applications"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Data Science Course in Kanpur", "url": "https://training.logixcode.com/courses/data-science-machine-learning-kanpur/", "description": "Learn data analysis, machine learning, Python and Power BI insights"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Android App Development Kanpur", "url": "https://training.logixcode.com/courses/android-app-development-kanpur/", "description": "Build Android mobile apps using Kotlin, Jetpack Compose and Firebase"}}
        ]
      }
    },
    {
      "@type": "WebPage",
      "@id": "https://training.logixcode.com/#webpage",
      "url": "https://training.logixcode.com/",
      "name": "IT Training Institute in Kanpur with Placement | LogixCode",
      "isPartOf": {"@id": "https://training.logixcode.com/#organization"},
      "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://training.logixcode.com/"}
        ]
      }
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Best IT training institute in Kanpur?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "LogixCode Training Institute in Kanpur is one of the best IT training institutes offering courses in Python, Java, MERN Stack, Data Science, and more. Located at Swarn Jayanti Vihar, Kanpur."
          }
        },
        {
          "@type": "Question",
          "name": "Best IT training institute in Lucknow?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "LogixCode Training Institute serves students from Lucknow with professional IT courses including Full Stack Development, Android Development, AI & Machine Learning, and Data Analytics."
          }
        },
        {
          "@type": "Question",
          "name": "What is the fee for IT training at LogixCode?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "LogixCode offers training programs starting at just ₹5,000 for summer training, winter training, vocational training, and syllabus training. The 6-month apprenticeship program is ₹25,000."
          }
        },
        {
          "@type": "Question",
          "name": "Does LogixCode provide placement support?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, LogixCode provides 100% placement assistance with connections to 100+ hiring companies. We also offer resume building and mock interview preparation."
          }
        }
      ]
    }
  ]
}
</script>

<!-- Favicon -->
<link rel="icon" type="image/webp" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">

<!-- Preconnect & Theme Tokens -->
<?php include_once __DIR__ . '/includes/theme.php'; ?>

<style>
.logo-card img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
</head>

<body class="bg-[var(--bg)] font-body text-[var(--ink)]" itemscope itemtype="https://schema.org/WebPage">

<!-- ================================= -->
<!-- HEADER -->
<!-- ================================= -->
<?php include "includes/navbar.php" ?>

<!-- ================================= -->
<!-- MAIN CONTENT -->
<!-- ================================= -->
<main>

<!-- ================================= -->
<!-- HERO SECTION (Component 2: --tint bg, </> motif, coral primary + outline secondary) -->
<!-- ================================= -->
<section id="hero" class="relative bg-[var(--tint)] border-b border-[var(--border)]">
    <div class="lc-container py-10 md:py-16 lg:py-20">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 items-center">

            <!-- LEFT COLUMN -->
            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 rounded-full bg-[var(--surface)] border border-[var(--border)] px-3.5 py-1.5 text-sm font-medium text-[var(--teal-text)]">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-[var(--teal)]"></span>
                        <span>Admissions open 2026</span>
                        <span class="font-mono font-bold text-[var(--teal)] ml-1" aria-hidden="true">&lt;/&gt;</span>
                    </div>

                    <h1 class="text-[var(--ink)] font-headline">
                        Best IT training in <span class="text-[var(--teal-text)]">Kanpur &amp; Lucknow</span>
                    </h1>

                    <p class="text-base md:text-lg leading-relaxed text-[var(--muted)]">
                        LogixCode — Kanpur aur Lucknow ka #1 IT training institute. Python, Java, MERN Stack, Data Science aur AI courses ke saath apna career transform karein. 500+ students placed, 100% placement support.
                    </p>
                </div>

                <!-- CTA (Coral Primary + Secondary Outline) -->
                <div class="flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>registration" class="btn-primary">
                        Enroll now
                    </a>
                    <a href="#courses" class="btn-secondary">
                        Explore courses
                    </a>
                </div>

                <!-- STATS MINI CARDS -->
                <div class="grid grid-cols-3 gap-3 pt-2">
                    <div class="lc-card p-3.5">
                        <p class="text-xl md:text-2xl font-bold text-[var(--ink)] font-headline">500+</p>
                        <p class="text-xs font-medium text-[var(--muted)]">Students trained</p>
                    </div>
                    <div class="lc-card p-3.5">
                        <p class="text-xl md:text-2xl font-bold text-[var(--ink)] font-headline">40+</p>
                        <p class="text-xs font-medium text-[var(--muted)]">Tech courses</p>
                    </div>
                    <div class="lc-card p-3.5">
                        <p class="text-xl md:text-2xl font-bold text-[var(--ink)] font-headline">100%</p>
                        <p class="text-xs font-medium text-[var(--muted)]">Practical training</p>
                    </div>
                </div>
            </div>

            <!-- CENTER VISUAL (Real Campus & Trainer Photo + Logo </> Motif) -->
            <div class="lg:col-span-3 flex flex-col gap-4">
                <div class="lc-card p-3 bg-[var(--surface)]">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769591122/Untitled_design_10_jbnn0e.webp"
                         alt="Students learning practical coding at LogixCode Kanpur campus"
                         class="w-full h-56 object-cover rounded-[8px]"
                         width="400" height="260" fetchpriority="high">
                    <div class="pt-3 px-1 flex items-center justify-between">
                        <span class="text-xs font-medium text-[var(--muted)]">Live classroom labs, Kanpur</span>
                        <span class="font-mono font-bold text-base text-[var(--teal)]" aria-hidden="true">&lt;/&gt;</span>
                    </div>
                </div>
                <div class="lc-card p-4 bg-[var(--surface)] flex items-center gap-3">
                    <div class="lc-icon-circle font-mono font-bold text-base" aria-hidden="true">&lt;/&gt;</div>
                    <div>
                        <p class="text-sm font-semibold text-[var(--ink)]">Hands-on coding from day one</p>
                        <p class="text-xs text-[var(--muted)]">Small batches · Personal mentor guidance</p>
                    </div>
                </div>
            </div>

            <!-- FORM (Component 7: White inputs, 1px --border, 48px height, visible labels, focus ring 2px --teal-text) -->
            <div class="lg:col-span-4">
                <div class="lc-card p-6 md:p-7 bg-[var(--surface)]">
                    <h2 class="text-xl font-bold text-[var(--ink)] mb-1">Start your journey</h2>
                    <p class="text-sm text-[var(--muted)] mb-5">Free career counseling — Kanpur &amp; Lucknow</p>

                    <?php if(isset($_GET['submitted'])): ?>
                    <div class="mb-4 p-3 bg-[var(--tint)] border border-[var(--teal)] rounded-[10px] text-[var(--ink)] text-sm font-medium">
                        Request submitted! Hum aapko jald hi contact karenge.
                    </div>
                    <?php endif; ?>

                    <form id="CounselingForm" action="" method="POST" class="space-y-4" onsubmit="return validateCaptcha()">
                        <div>
                            <label for="full_name" class="lc-label">Full name <span class="text-[var(--coral)]">*</span></label>
                            <input id="full_name" class="lc-input" required placeholder="e.g. Rahul Sharma" name="full_name" type="text">
                        </div>

                        <div>
                            <label for="phone" class="lc-label">Phone number <span class="text-[var(--coral)]">*</span></label>
                            <input id="phone" class="lc-input" required placeholder="10-digit mobile number" name="phone" type="tel" pattern="[0-9]{10}" title="10 digit phone number">
                        </div>

                        <div>
                            <label for="interest" class="lc-label">Course interest <span class="text-[var(--coral)]">*</span></label>
                            <select id="interest" class="lc-input" name="interest" required>
                                <option value="">Select your interest</option>
                                <option value="summer training">Summer training</option>
                                <option value="winter training">Winter training</option>
                                <option value="full stack development">Full stack development</option>
                                <option value="java programming">Java programming</option>
                                <option value="python development">Python development</option>
                                <option value="mern stack development">MERN stack development</option>
                                <option value="android development">Android development</option>
                                <option value="data science & analytics">Data science &amp; analytics</option>
                                <option value="ai & machine learning">AI &amp; machine learning</option>
                                <option value="frontend development">Frontend development</option>
                                <option value="cloud computing aws">Cloud computing (AWS)</option>
                            </select>
                        </div>

                        <!-- CAPTCHA -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="lc-label">Verification code</label>
                                <div class="flex items-center gap-2">
                                    <div id="captchaBox" class="flex-1 flex items-center justify-center h-12 bg-[var(--tint)] border border-[var(--border)] rounded-[10px] select-none">
                                        <span id="captchaText" class="text-base font-bold tracking-widest text-[var(--ink)] font-mono"></span>
                                    </div>
                                    <button type="button" onclick="generateCaptcha()" aria-label="Refresh verification code" class="h-12 min-w-[44px] px-3 rounded-[10px] bg-[var(--teal)] text-[var(--ink)] font-semibold hover:opacity-90 transition-opacity duration-150">↻</button>
                                </div>
                            </div>
                            <div>
                                <label class="lc-label" for="captcha_input">Enter code <span class="text-[var(--coral)]">*</span></label>
                                <input required id="captcha_input" name="captcha_input" type="text" maxlength="6" placeholder="Type code" class="lc-input"/>
                            </div>
                        </div>
                        <p id="captchaError" class="lc-error-text hidden" role="alert">
                            <span class="material-symbols-outlined text-base">error</span>
                            <span>Invalid verification code. Please try again.</span>
                        </p>

                        <button type="submit" class="btn-secondary w-full">
                            Book free demo &amp; counseling
                        </button>
                    </form>

                    <script>
                    let currentCaptcha = "";
                    function generateCaptcha() {
                        const chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ0123456789";
                        let captcha = "";
                        for (let i = 0; i < 4; i++) {
                            captcha += chars.charAt(Math.floor(Math.random() * chars.length));
                        }
                        currentCaptcha = captcha;
                        document.getElementById("captchaText").innerText = captcha;
                    }
                    window.onload = generateCaptcha;
                    function validateCaptcha() {
                        const userInput = document.getElementById("captcha_input").value;
                        const errorText = document.getElementById("captchaError");
                        if (userInput.toUpperCase() !== currentCaptcha) {
                            errorText.classList.remove("hidden");
                            generateCaptcha();
                            return false;
                        } else {
                            errorText.classList.add("hidden");
                            return true;
                        }
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- COMPONENT 3: HIGHLIGHT STRIP UNDER HERO (--teal bg, dark --ink text) -->
<!-- ================================= -->
<div class="bg-[var(--teal)] text-[var(--ink)] border-b border-[var(--border)]">
    <div class="lc-container py-3.5 flex flex-wrap items-center justify-between gap-4 text-sm font-medium">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-lg text-[var(--ink)]">event_available</span>
            <span class="font-semibold text-[var(--ink)]">New batch starts Monday — offline in Kanpur &amp; live online</span>
        </div>
        <div class="flex flex-wrap items-center gap-6 text-[var(--ink)]">
            <span>• Small batches (15–20 students)</span>
            <span>• Free 1-day demo class</span>
            <a href="<?= BASE_URL ?>courses" class="font-semibold underline text-[var(--ink)]">View batch schedule →</a>
        </div>
    </div>
</div>

<!-- ================================= -->
<!-- PARTNERS STRIP -->
<!-- ================================= -->
<section class="border-b border-[var(--border)] bg-[var(--surface)] py-8">
    <div class="lc-container text-center">
        <p class="mb-6 text-sm font-medium text-[var(--muted)]">
            Technologies and platforms taught across our curriculum
        </p>
        <div class="flex flex-wrap items-center justify-center gap-10 text-[var(--muted)]">
            <div class="text-lg font-bold flex items-center gap-2 text-[var(--ink)]">
                <span class="material-symbols-outlined text-[var(--teal-text)]">cloud</span> Google Cloud
            </div>
            <div class="text-lg font-bold flex items-center gap-2 text-[var(--ink)]">
                <span class="material-symbols-outlined text-[var(--teal-text)]">database</span> Oracle &amp; SQL
            </div>
            <div class="text-lg font-bold flex items-center gap-2 text-[var(--ink)]">
                <span class="material-symbols-outlined text-[var(--teal-text)]">code</span> Microsoft .NET
            </div>
            <div class="text-lg font-bold flex items-center gap-2 text-[var(--ink)]">
                <span class="material-symbols-outlined text-[var(--teal-text)]">api</span> Amazon Web Services
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- COMPONENT 5: LEARNING ROADMAP SECTION (Basics -> Skills -> Projects -> Job) -->
<!-- ================================= -->
<section class="lc-section bg-[var(--bg)] border-b border-[var(--border)]">
    <div class="lc-container">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="lc-chip mb-3">Step-by-step learning path</span>
            <h2 class="text-[var(--ink)] mb-3">How you go from beginner to job-ready</h2>
            <p class="text-base text-[var(--muted)]">
                Every course at LogixCode follows a clear 4-stage practical roadmap designed for students and fresh graduates.
            </p>
        </div>

        <div class="lc-roadmap max-w-4xl mx-auto">
            <div class="lc-roadmap-step">
                <div class="lc-roadmap-dot">1</div>
                <div>
                    <h3 class="text-lg font-semibold text-[var(--ink)] mb-1">Basics</h3>
                    <p class="text-sm text-[var(--muted)]">Core programming logic, syntax, and problem-solving from scratch.</p>
                </div>
            </div>
            <div class="lc-roadmap-step">
                <div class="lc-roadmap-dot">2</div>
                <div>
                    <h3 class="text-lg font-semibold text-[var(--ink)] mb-1">Skills</h3>
                    <p class="text-sm text-[var(--muted)]">Modern frameworks, databases, APIs, and daily lab assignments.</p>
                </div>
            </div>
            <div class="lc-roadmap-step">
                <div class="lc-roadmap-dot">3</div>
                <div>
                    <h3 class="text-lg font-semibold text-[var(--ink)] mb-1">Projects</h3>
                    <p class="text-sm text-[var(--muted)]">Build and deploy real applications on GitHub with mentor code reviews.</p>
                </div>
            </div>
            <div class="lc-roadmap-step">
                <div class="lc-roadmap-dot">4</div>
                <div>
                    <h3 class="text-lg font-semibold text-[var(--ink)] mb-1">Job</h3>
                    <p class="text-sm text-[var(--muted)]">Resume building, mock interviews, and direct placement drives.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TRAINING PROGRAMS (Component 4: White cards, teal icon circle, From ₹X, View syllabus link) -->
<!-- ================================= -->
<section class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="text-center mb-12">
            <div class="lc-chip mb-3">
                LogixCode training programs
            </div>
            <h2 class="text-[var(--ink)] mb-3">
                Transform your career with <span class="text-[var(--teal-text)]">practical training</span>
            </h2>
            <p class="text-base text-[var(--muted)] max-w-2xl mx-auto">
                Kanpur aur Lucknow ke students ke liye — practically seekho, confidence banao aur industry-ready bano. Real projects, expert mentors aur continuous guidance ke saath.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1 — Summer Training -->
            <div class="lc-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">wb_sunny</span>
                    </div>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                        45–60 days · Beginner
                    </span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Summer training</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    B.Tech, BCA &amp; MCA students ke liye hands-on training — real-time projects aur industry mentorship ke saath. Kanpur/Lucknow ke colleges ke students ke liye ideal.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 2 — Vocational Training -->
            <div class="lc-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">school</span>
                    </div>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                        45–60 days · Practical
                    </span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Vocational training</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    Diploma &amp; PGDCA students ke liye — practical, structured aur employable skills pe focused training program.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 3 — Winter Training -->
            <div class="lc-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">ac_unit</span>
                    </div>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                        45 days · Intensive
                    </span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Winter training</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    Corporate-ready bano structured learning, coding drills aur project experience ke saath.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 4 — Apprenticeship (Featured) -->
            <div class="lc-card p-6 flex flex-col border-2 border-[var(--teal)]">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">work</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="px-2.5 py-1 rounded-full bg-[var(--teal)] text-[var(--ink)] text-xs font-semibold">Job guarantee track</span>
                        <span class="lc-chip text-xs">6 months</span>
                    </div>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Apprenticeship program</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    Expert consultants ke saath kaam karo aur graduation ke baad real-world exposure gain karo.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹25,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 5 — Syllabus Training -->
            <div class="lc-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">menu_book</span>
                    </div>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                        30 days · Foundation
                    </span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Syllabus training</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    Subjects ko practically samjho simplified real-world examples aur guided sessions ke saath.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 6 — PD & Skill Development -->
            <div class="lc-card p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)] text-[22px]">record_voice_over</span>
                    </div>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                        30 days · Career prep
                    </span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">PD &amp; skill development</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed flex-1">
                    Personality, communication, presentation skills aur self-confidence improve karo expert trainers ke saath.
                </p>
                <div class="mt-5 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Fee</span>
                        <span class="text-lg font-bold font-headline text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================================= -->
<!-- POPULAR COURSES (Component 4: Course cards grid 1 col mobile, 2 tablet, 3 desktop) -->
<!-- ================================= -->
<section id="courses" class="lc-section bg-[var(--tint)] border-y border-[var(--border)]">
    <div class="lc-container">
        <div class="text-center mb-12">
            <span class="lc-chip bg-[var(--surface)] mb-3">Our programs</span>
            <h2 class="text-[var(--ink)] mb-3">
                IT courses in Kanpur &amp; Lucknow
            </h2>
            <p class="max-w-2xl mx-auto text-base text-[var(--muted)]">
                Industry-focused training programs jo real-world technical skills build karte hain aur Kanpur, Lucknow aur poore UP mein aapka career accelerate karte hain.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Data Analytics -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">analytics</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">4–6 months</span>
                        <span class="lc-chip text-xs">Beginner</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Data science &amp; analytics</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Learn data analysis, Python, Pandas, Machine Learning, and business insights using Power BI and SQL.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/data-science-machine-learning-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 2: Python Development -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">data_object</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">4–6 months</span>
                        <span class="lc-chip text-xs">Beginner to pro</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Python development</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Build powerful backend applications using Python, Django framework, REST APIs, and PostgreSQL.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/python-training-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 3: MERN Stack -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">developer_mode_tv</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">6 months</span>
                        <span class="lc-chip text-xs">Job oriented</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">MERN stack development</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Learn MongoDB, Express, React, and Node.js to build modern, performant full-stack web applications.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/full-stack-web-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 4: Java Programming -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">coffee</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">4–6 months</span>
                        <span class="lc-chip text-xs">Job oriented</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Java programming</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Master Core Java, OOPs, Spring Boot, Hibernate, and microservices for enterprise-grade applications.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/java-programming-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 5: Android Development -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">android</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">3–6 months</span>
                        <span class="lc-chip text-xs">Beginner</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Android development</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Build professional Android apps using Kotlin, Jetpack Compose, REST APIs, and Firebase.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/android-app-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 6: Cloud Computing AWS -->
            <div class="lc-card p-6 flex flex-col bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-2xl text-[var(--ink)]">cloud</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="lc-chip text-xs">3 months</span>
                        <span class="lc-chip text-xs">Job oriented</span>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Cloud computing (AWS)</h3>
                <p class="text-sm text-[var(--muted)] mb-5 flex-1">
                    Master Amazon Web Services — EC2, S3, Lambda, RDS, CloudFormation, DevOps, and production deployment.
                </p>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">
                        View syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

        </div>

        <div class="mt-10 text-center flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= BASE_URL ?>registration" class="btn-primary">Enroll now</a>
            <a href="<?= BASE_URL ?>courses" class="btn-secondary">Browse all 40+ courses</a>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- ABOUT -->
<!-- ================================= -->
<section id="about" class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="flex flex-col gap-5">
                <div>
                    <span class="lc-chip mb-3">About LogixCode</span>
                    <h2 class="text-[var(--ink)]">
                        Leading the way in <span class="text-[var(--teal-text)]">tech education</span>
                    </h2>
                </div>
                <p class="text-base text-[var(--muted)]">
                    LogixCode, headquartered in Kanpur, is a forward-thinking IT training institute that bridges the gap between academia and industry requirements. We empower tech leaders across Lucknow, Uttar Pradesh, and all over North India.
                </p>
                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-[var(--ink)]">Who we are</h3>
                    <p class="text-[var(--muted)] text-base">
                        We provide industry-ready training through intensive project-based learning. Our curriculum is designed by professionals to ensure students gain practical skills that matter in the real world.
                    </p>
                </div>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <li class="flex items-center gap-3">
                        <div class="lc-icon-circle w-9 h-9">
                            <span class="material-symbols-outlined text-base text-[var(--ink)]">rocket_launch</span>
                        </div>
                        <span class="text-sm font-medium text-[var(--ink)]">Project-based training</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="lc-icon-circle w-9 h-9">
                            <span class="material-symbols-outlined text-base text-[var(--ink)]">school</span>
                        </div>
                        <span class="text-sm font-medium text-[var(--ink)]">Expert mentors</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="lc-icon-circle w-9 h-9">
                            <span class="material-symbols-outlined text-base text-[var(--ink)]">work</span>
                        </div>
                        <span class="text-sm font-medium text-[var(--ink)]">Internship opportunities</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="lc-icon-circle w-9 h-9">
                            <span class="material-symbols-outlined text-base text-[var(--ink)]">assignment_turned_in</span>
                        </div>
                        <span class="text-sm font-medium text-[var(--ink)]">Placement assistance</span>
                    </li>
                </ul>
                <div class="pt-3">
                    <a href="<?= BASE_URL ?>about" class="btn-secondary">
                        Learn more about us
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </div>

            <div class="lc-card p-4 bg-[var(--surface)]">
                <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769591122/Untitled_design_10_jbnn0e.webp"
                    alt="LogixCode students learning at IT training institute in Kanpur"
                    class="h-[360px] w-full object-cover rounded-[8px]"
                    loading="lazy">
                <div class="grid grid-cols-3 divide-x divide-[var(--border)] pt-4 mt-4 border-t border-[var(--border)] text-center">
                    <div class="px-2">
                        <div class="text-2xl font-bold text-[var(--teal-text)] font-headline">500+</div>
                        <div class="text-xs font-medium text-[var(--muted)]">Students trained</div>
                    </div>
                    <div class="px-2">
                        <div class="text-2xl font-bold text-[var(--teal-text)] font-headline">40+</div>
                        <div class="text-xs font-medium text-[var(--muted)]">Courses available</div>
                    </div>
                    <div class="px-2">
                        <div class="text-2xl font-bold text-[var(--teal-text)] font-headline">100%</div>
                        <div class="text-xs font-medium text-[var(--muted)]">Practical training</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TRAINING BENEFITS -->
<!-- ================================= -->
<section id="benefits" class="lc-section bg-[var(--tint)] border-y border-[var(--border)]">
    <div class="lc-container">
        <div class="text-center mb-12 space-y-3">
            <span class="lc-chip bg-[var(--surface)]">Advantages</span>
            <h2 class="text-[var(--ink)]">Training benefits</h2>
            <p class="max-w-2xl mx-auto text-base text-[var(--muted)]">
                Discover why thousands of learners trust LogixCode for professional and career-oriented training. We bridge the gap between education and industry.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">work_outline</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">100% job oriented</h3>
                <p class="text-sm text-[var(--muted)]">Training programs designed according to industry requirements so students become job-ready from day one.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">school</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">Expert mentors</h3>
                <p class="text-sm text-[var(--muted)]">Learn from experienced industry professionals who bring real-world expertise into every session.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">handshake</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">Placement support</h3>
                <p class="text-sm text-[var(--muted)]">Dedicated placement assistance with connections to multiple companies for job opportunities.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">code_blocks</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">Live projects</h3>
                <p class="text-sm text-[var(--muted)]">Work on real industry projects to build a strong portfolio and practical experience.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">verified_user</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">Certification</h3>
                <p class="text-sm text-[var(--muted)]">Receive industry-recognized certification after successfully completing the course.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">schedule</span>
                </div>
                <h3 class="text-xl font-semibold mb-2 text-[var(--ink)]">Flexible timing</h3>
                <p class="text-sm text-[var(--muted)]">Choose weekday, weekend, or online batches that fit your college or work schedule.</p>
            </div>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#CounselingForm" class="btn-primary">
                Book free demo
            </a>
            <a href="#courses" class="btn-secondary">
                Explore our courses
            </a>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- WHY CHOOSE US -->
<!-- ================================= -->
<section id="why-choose-us" class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="text-center mb-12 max-w-3xl mx-auto">
            <span class="lc-chip mb-3">Why choose LogixCode</span>
            <h2 class="text-[var(--ink)] mb-3">What makes us different</h2>
            <p class="text-base text-[var(--muted)]">We combine industry-driven curriculum, expert mentorship, and real-world project experience to help students build successful technology careers.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">military_tech</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Trusted institute</h3>
                <p class="text-sm text-[var(--muted)]">Recognized for excellence in IT training with practical teaching methods and strong student outcomes.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">groups</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">500+ students trained</h3>
                <p class="text-sm text-[var(--muted)]">Hundreds of students have built software careers through our structured classroom programs.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">auto_stories</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Industry curriculum</h3>
                <p class="text-sm text-[var(--muted)]">Courses designed according to current industry standards and real developer tools.</p>
            </div>
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="lc-icon-circle mb-4">
                    <span class="material-symbols-outlined text-2xl text-[var(--ink)]">work_history</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Placement support</h3>
                <p class="text-sm text-[var(--muted)]">Dedicated support with resume building, mock interview preparation, and partner referrals.</p>
            </div>
        </div>

        <!-- Component 6: Stats Band (--dark background, big numbers in --teal, text in --on-dark) -->
        <div class="lc-dark-band rounded-[12px] p-8 md:p-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline mb-1">500+</div>
                    <div class="text-[var(--on-dark)] text-sm font-medium">Students trained</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline mb-1">40+</div>
                    <div class="text-[var(--on-dark)] text-sm font-medium">Job-ready courses</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline mb-1">100+</div>
                    <div class="text-[var(--on-dark)] text-sm font-medium">Hiring partners</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline mb-1">100%</div>
                    <div class="text-[var(--on-dark)] text-sm font-medium">Practical training</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- OUR RECRUITERS -->
<!-- ================================= -->
<section id="recruiters" class="lc-section bg-[var(--surface)] border-y border-[var(--border)]">
    <div class="lc-container">
        <div class="text-center mb-12">
            <span class="lc-chip mb-3">Our hiring network</span>
            <h2 class="text-[var(--ink)] mb-3">Our recruiters</h2>
            <p class="max-w-2xl mx-auto text-base text-[var(--muted)]">
                Our students get opportunities to work with leading technology companies, from innovative startups to established IT firms.
            </p>
        </div>

        <!-- Company Logos -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769592753/download_veq7cq.png" alt="Hiring partner 1" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769592569/websofy-software-pvt-ltd-indira-nagar-lucknow-lucknow-internet-website-designers-syiggg1ogp_idnlu5.avif" alt="Websofy Software Lucknow" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593249/unnamed_zoxwmw.jpg" alt="Hiring partner 3" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593269/images_dz6wnl.png" alt="Hiring partner 4" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593329/codeaspire_logo_qtvjzo.jpg" alt="CodeAspire" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593345/download_fnlauu.jpg" alt="Hiring partner 6" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593363/images_bljkqe.png" alt="Hiring partner 7" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593391/techno_friend_logo_q2ma1m.jpg" alt="TechnoFriend" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593415/k2technologies_logo_hch5yw.jpg" alt="K2 Technologies" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593436/images_g6ptag.png" alt="Hiring partner 10" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593448/download_g76lla.png" alt="Hiring partner 11" loading="lazy"></div>
            <div class="logo-card lc-card p-4 flex items-center justify-center h-28"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593488/download_tkcz4c.png" alt="Hiring partner 12" loading="lazy"></div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TECHNOLOGIES & SKILLS -->
<!-- ================================= -->
<section id="technologies" class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="max-w-3xl mx-auto text-center mb-12">
            <span class="lc-chip mb-3">Technologies &amp; skills</span>
            <h2 class="text-[var(--ink)] mb-3">What you'll learn</h2>
            <p class="text-base text-[var(--muted)]">
                Master the most in-demand programming languages, frameworks, and technologies used by top companies in real-world software development.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769665593/python_zu48cm.png" alt="Python" class="w-7 h-7" loading="lazy">
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Python</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Backend programming, automation, and data processing.</p>
                <span class="mt-auto lc-chip text-xs">Backend</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">React</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Build modern and interactive user interfaces.</p>
                <span class="mt-auto lc-chip text-xs">Frontend</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="Node.js" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Node.js</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Scalable server-side development using JavaScript.</p>
                <span class="mt-auto lc-chip text-xs">Backend</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <span class="material-symbols-outlined text-2xl text-[var(--teal-text)]">analytics</span>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Data science</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Extract insights and patterns from complex datasets.</p>
                <span class="mt-auto lc-chip text-xs">Analytics</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg" alt="Angular" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Angular</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Enterprise-grade framework for robust web apps.</p>
                <span class="mt-auto lc-chip text-xs">Frontend</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg" alt="Flutter" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Flutter</h3>
                <p class="text-xs text-[var(--muted)] mb-3">Build native apps for mobile, web, and desktop.</p>
                <span class="mt-auto lc-chip text-xs">Mobile</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg" alt="Django" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Django</h3>
                <p class="text-xs text-[var(--muted)] mb-3">High-level Python web framework for rapid development.</p>
                <span class="mt-auto lc-chip text-xs">Backend</span>
            </div>
            <div class="lc-card p-5 flex flex-col items-center text-center">
                <div class="w-12 h-12 mb-3 flex items-center justify-center bg-[var(--tint)] rounded-[10px]">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" alt="Java" class="w-7 h-7" loading="lazy"/>
                </div>
                <h3 class="font-semibold text-base text-[var(--ink)] mb-1">Java</h3>
                <p class="text-xs text-[var(--muted)] mb-3">The backbone of enterprise software systems.</p>
                <span class="mt-auto lc-chip text-xs">Backend</span>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- INDUSTRY TOOLS -->
<!-- ================================= -->
<section class="bg-[var(--tint)] border-y border-[var(--border)] py-12">
    <div class="lc-container text-center">
        <h2 class="text-xl font-semibold text-[var(--ink)] mb-8">Industry tools you will use</h2>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
            <div class="flex flex-col items-center gap-2">
                <img alt="GitHub" class="w-10 h-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269881/Industry_Tool_git_ybwef7.svg" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">GitHub</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img alt="Docker" class="w-10 h-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_docker_mhqpf7.svg" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">Docker</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img alt="AWS" class="w-10 h-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_node_aws_artfzx.svg" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">AWS</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img alt="VS Code" class="w-10 h-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269882/Industry_Tool_node_vscode_puvuwj.png" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">VS Code</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img alt="MySQL" class="w-10 h-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_mysql_txzbon.svg" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">MySQL</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg" alt="Tailwind CSS" class="w-10 h-10" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">Tailwind</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg" alt="MongoDB" class="w-10 h-10" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">MongoDB</span>
            </div>
            <div class="flex flex-col items-center gap-2">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linux/linux-original.svg" alt="Linux" class="w-10 h-10" loading="lazy"/>
                <span class="text-xs font-medium text-[var(--ink)]">Linux</span>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- DIRECTOR MESSAGE (Real Photo, E-E-A-T) -->
<!-- ================================= -->
<section id="director-message" class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="lc-card overflow-hidden bg-[var(--surface)]">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-2/5 p-6 lg:p-10 flex flex-col items-center justify-center bg-[var(--tint)] border-b lg:border-b-0 lg:border-r border-[var(--border)]">
                    <div class="w-full max-w-[280px] overflow-hidden rounded-[12px] border border-[var(--border)] bg-[var(--surface)]">
                        <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1756809257/WhatsApp_Image_2025-09-02_at_10.53.10_0a7aae5b_j3r6oz.jpg"
                            alt="Dr. Ritesh Kumar Tiwari - Director LogixCode Training Kanpur"
                            class="h-[340px] w-full object-cover"
                            loading="lazy">
                        <div class="p-4 bg-[var(--surface)] border-t border-[var(--border)]">
                            <h3 class="text-[var(--ink)] text-lg font-semibold">Dr. Ritesh Kumar Tiwari</h3>
                            <p class="text-[var(--teal-text)] font-medium text-sm">Director, LogixCode</p>
                        </div>
                    </div>
                    <div class="mt-4 w-full max-w-[280px] flex items-center gap-3 p-3 bg-[var(--surface)] rounded-[10px] border border-[var(--border)]">
                        <div class="lc-icon-circle w-9 h-9">
                            <span class="material-symbols-outlined text-base text-[var(--ink)]">verified_user</span>
                        </div>
                        <div>
                            <p class="text-xs text-[var(--muted)]">Experience</p>
                            <p class="text-sm font-semibold text-[var(--ink)]">15+ years industry &amp; academic expert</p>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-3/5 p-6 lg:p-12 flex flex-col justify-center">
                    <div class="mb-6">
                        <span class="lc-chip mb-3">Leadership</span>
                        <h2 class="text-[var(--ink)] mb-3">
                            Director's <span class="text-[var(--teal-text)]">message</span>
                        </h2>
                        <p class="text-base text-[var(--muted)] font-medium">
                            Visionary leadership empowering the next generation of IT professionals with future-ready technical expertise.
                        </p>
                    </div>
                    <div class="space-y-4 text-[var(--muted)] text-base mb-8">
                        <p>At <strong class="text-[var(--ink)]">LogixCode</strong>, our mission is to empower students with practical, industry-relevant skills. We believe that the traditional academic model often falls short of the rapid pace of the technology sector.</p>
                        <p>Our curriculum is meticulously designed to <strong class="text-[var(--teal-text)]">bridge the gap between academia and industry</strong>, ensuring our graduates are competent professionals ready to tackle real-world challenges.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span>
                            <span class="font-medium text-sm text-[var(--ink)]">Industry-focused training</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span>
                            <span class="font-medium text-sm text-[var(--ink)]">500+ students mentored</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span>
                            <span class="font-medium text-sm text-[var(--ink)]">Placement-ready curriculum</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span>
                            <span class="font-medium text-sm text-[var(--ink)]">Hands-on lab projects</span>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-[var(--border)]">
                        <p class="text-sm font-semibold text-[var(--ink)]">Dr. Ritesh Kumar Tiwari</p>
                        <p class="text-xs text-[var(--muted)]">Director, LogixCode Training Institute, Kanpur</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- COMPONENT 6: STATS AND TESTIMONIALS (--dark bg, big numbers in --teal, quotes in --on-dark, small rating dots in --teal) -->
<!-- ================================= -->
<section id="testimonials" class="lc-section lc-dark-band">
    <div class="lc-container">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center rounded-full bg-[var(--teal)] px-3.5 py-1 text-xs font-semibold text-[var(--ink)] mb-3">Student testimonials</span>
            <h2 class="text-[var(--on-dark)] mb-3">What our students say</h2>
            <p class="text-base text-[var(--on-dark)] opacity-85">Hear from students who transformed their careers with practical training, expert mentorship, and industry-ready skills at LogixCode.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-[12px] border border-[var(--teal)]/30 bg-[var(--ink)] p-6 flex flex-col">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="lc-icon-circle font-bold text-base">RS</div>
                    <div>
                        <h3 class="font-semibold text-base text-[var(--on-dark)]">Rahul Sharma</h3>
                        <p class="text-xs font-medium text-[var(--teal)]">Python Django program</p>
                    </div>
                </div>
                <div class="lc-rating-dots mb-4" aria-label="Rated 5 out of 5">
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                </div>
                <p class="text-[var(--on-dark)] opacity-90 text-sm leading-relaxed grow">
                    "LogixCode provided the perfect environment to learn practical programming skills. The mentors explained concepts clearly and the hands-on projects helped me gain real industry experience."
                </p>
            </div>

            <div class="rounded-[12px] border border-[var(--teal)]/30 bg-[var(--ink)] p-6 flex flex-col">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="lc-icon-circle font-bold text-base">PP</div>
                    <div>
                        <h3 class="font-semibold text-base text-[var(--on-dark)]">Priya Patel</h3>
                        <p class="text-xs font-medium text-[var(--teal)]">Full stack development</p>
                    </div>
                </div>
                <div class="lc-rating-dots mb-4" aria-label="Rated 5 out of 5">
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                </div>
                <p class="text-[var(--on-dark)] opacity-90 text-sm leading-relaxed grow">
                    "The live projects and practical training made a huge difference in my learning journey. The instructors were extremely supportive and the curriculum covered everything needed to become a confident developer."
                </p>
            </div>

            <div class="rounded-[12px] border border-[var(--teal)]/30 bg-[var(--ink)] p-6 flex flex-col">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="lc-icon-circle font-bold text-base">AV</div>
                    <div>
                        <h3 class="font-semibold text-base text-[var(--on-dark)]">Aman Verma</h3>
                        <p class="text-xs font-medium text-[var(--teal)]">Data analytics</p>
                    </div>
                </div>
                <div class="lc-rating-dots mb-4" aria-label="Rated 5 out of 5">
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                    <span class="lc-rating-dot"></span>
                </div>
                <p class="text-[var(--on-dark)] opacity-90 text-sm leading-relaxed grow">
                    "The course structure was very well designed with real-world datasets and tools. I gained strong analytical skills and practical experience that helped me start my career in data analytics."
                </p>
            </div>
        </div>

        <!-- Stats Summary Inside Dark Band -->
        <div class="mt-12 pt-10 border-t border-[var(--teal)]/25 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline">500+</div>
                <div class="text-sm text-[var(--on-dark)] opacity-85 mt-1">Students trained</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline">100+</div>
                <div class="text-sm text-[var(--on-dark)] opacity-85 mt-1">Hiring companies</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline">4.8 / 5</div>
                <div class="text-sm text-[var(--on-dark)] opacity-85 mt-1">Student satisfaction</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-[var(--teal)] font-headline">100%</div>
                <div class="text-sm text-[var(--on-dark)] opacity-85 mt-1">Placement support</div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- SERVICES / WHAT WE OFFER (Clean Flat Cards, No Stock AI Imagery) -->
<!-- ================================= -->
<section id="services" class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="text-center mb-12 max-w-3xl mx-auto">
            <span class="lc-chip mb-3">What we offer</span>
            <h2 class="text-[var(--ink)] mb-3">Our services</h2>
            <p class="text-base text-[var(--muted)]">Comprehensive technology training programs designed to build practical skills, industry knowledge, and career opportunities for students and professionals in Kanpur &amp; Lucknow.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">workspace_premium</span></div>
                    <span class="lc-chip text-xs">Certification</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Training programs</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Strategic programs covering fundamental and advanced technical concepts.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Summer &amp; winter training</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Internships &amp; apprenticeship</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Vocational training</li>
                </ul>
            </div>

            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">terminal</span></div>
                    <span class="lc-chip text-xs">Job oriented</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Programming languages</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Master the languages that power modern software systems.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">code</span> Python for data &amp; automation</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">code</span> Java enterprise edition</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">code</span> PHP web development</li>
                </ul>
            </div>

            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">devices</span></div>
                    <span class="lc-chip text-xs">Industry projects</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Web &amp; mobile development</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Build scalable cross-platform applications using modern stacks.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> MERN stack &amp; ASP.NET</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Flutter &amp; Android apps</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Responsive web architecture</li>
                </ul>
            </div>

            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">architecture</span></div>
                    <span class="lc-chip text-xs">Practical training</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Engineering (CAD)</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Professional drafting and design for engineering disciplines.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Mechanical CAD &amp; design</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Civil &amp; structural CAD</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Electrical system drafting</li>
                </ul>
            </div>

            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">palette</span></div>
                    <span class="lc-chip text-xs">Certification</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Design &amp; creative</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Unleash creativity with industry-leading design tools and principles.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Graphic design masterclass</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> UI/UX design workflow</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Visual communication</li>
                </ul>
            </div>

            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="lc-icon-circle"><span class="material-symbols-outlined text-[var(--ink)]">insights</span></div>
                    <span class="lc-chip text-xs">Industry projects</span>
                </div>
                <h3 class="text-xl font-semibold text-[var(--ink)] mb-2">Data &amp; marketing</h3>
                <p class="text-[var(--muted)] text-sm mb-4">Harness the power of data and digital strategies for growth.</p>
                <ul class="space-y-2 text-sm text-[var(--ink)]">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> AI &amp; machine learning</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Advanced data analytics</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[var(--teal-text)] text-lg">check_circle</span> Digital marketing strategy</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- OUR REACH / CITIES -->
<!-- ================================= -->
<section id="reach" class="lc-section bg-[var(--tint)] border-t border-[var(--border)]">
    <div class="lc-container">
        <div class="text-center mb-12 space-y-3">
            <span class="lc-chip bg-[var(--surface)]">Our reach</span>
            <h2 class="text-[var(--ink)]">Cities we <span class="text-[var(--teal-text)]">cover</span></h2>
            <p class="max-w-2xl mx-auto text-[var(--muted)] text-base">
                Kanpur se Lucknow tak, North &amp; Central India mein professional IT excellence expand kar rahe hain. Hamare community ka hissa banein.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 items-start mb-12">
            <!-- UP Card -->
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center gap-3 mb-5">
                    <div class="lc-icon-circle w-10 h-10"><span class="material-symbols-outlined text-[var(--ink)]">location_city</span></div>
                    <h3 class="text-xl font-semibold text-[var(--ink)]">Uttar Pradesh</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $up_cities = ['Lucknow','Kanpur','Noida','Ghaziabad','Meerut','Agra','Varanasi','Prayagraj','Bareilly','Aligarh','Moradabad','Saharanpur','Gorakhpur','Firozabad','Jhansi','Muzaffarnagar','Mathura','Rampur','Shahjahanpur','Ayodhya','Etawah','Hapur','Mau','Farrukhabad','Lakhimpur'];
                    foreach($up_cities as $city): ?>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs">location_on</span> <?= $city ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MP Card -->
            <div class="lc-card p-6 bg-[var(--surface)]">
                <div class="flex items-center gap-3 mb-5">
                    <div class="lc-icon-circle w-10 h-10"><span class="material-symbols-outlined text-[var(--ink)]">map</span></div>
                    <h3 class="text-xl font-semibold text-[var(--ink)]">Madhya Pradesh</h3>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $mp_cities = ['Bhopal','Indore','Gwalior','Jabalpur','Ujjain','Guna','Chhindwara','Bhind','Morena','Khandwa','Burhanpur','Singrauli','Katni','Rewa','Ratlam','Satna','Dewas','Sagar'];
                    foreach($mp_cities as $city): ?>
                    <span class="lc-chip text-xs">
                        <span class="material-symbols-outlined text-xs">location_on</span> <?= $city ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- ✅ SEO: LOCAL SEO TEXT SECTION    -->
<!-- ================================= -->
<section class="py-12 bg-[var(--surface)] border-t border-[var(--border)]">
    <div class="lc-container">
        <h2 class="text-2xl font-bold text-[var(--ink)] mb-6 text-center">
            Best IT training institute in Kanpur &amp; Lucknow
        </h2>
        <div class="text-[var(--muted)] text-sm leading-relaxed grid md:grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-lg text-[var(--ink)] mb-2">IT training in Kanpur</h3>
                <p>LogixCode Kanpur ka sabse trusted IT training institute hai jo Swarn Jayanti Vihar, Koyla Nagar mein located hai. Hum B.Tech, BCA, MCA aur Diploma students ke liye Python, Java, MERN Stack, Data Science, aur Cloud Computing (AWS) courses offer karte hain. Hamare 500+ placed students aur 100+ hiring partners ke saath, LogixCode Kanpur mein career shuru karne ka best platform hai.</p>
            </div>
            <div>
                <h3 class="font-semibold text-lg text-[var(--ink)] mb-2">IT training in Lucknow</h3>
                <p>Lucknow ke students ke liye bhi LogixCode ke saath training available hai. Full Stack Development, Android Development, AI &amp; Machine Learning, aur Data Analytics — ye sab courses Lucknow ke engineering colleges aur universities ke students ke liye designed hain. 100% placement support aur industry-recognized certification ke saath apna tech career start karein.</p>
            </div>
        </div>
    </div>
</section>

</main>

<!-- ================================= -->
<!-- FOOTER -->
<!-- ================================= -->
<?php include "includes/footer.php" ?>

</body>
</html>
