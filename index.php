<?php 

ini_set('display_errors', 0); // Production me errors hide karo
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

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
        header("Location: /?submitted=1");
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
            <h2 style='color:#03c4ce;'>New Counseling Request</h2>
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
<html lang="hi-IN">
<!-- 
    ✅ lang="hi-IN" — Hindi-English mixed content ke liye better
    Google Kanpur/Lucknow users ke liye yahi prefer karta hai
-->
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- ============================================= -->
<!-- ✅ SEO: PRIMARY META TAGS                     -->
<!-- ============================================= -->
<title>LogixCode Training - Best IT Training Institute in Kanpur & Lucknow | Python, Java, Full Stack, Data Science Courses</title>
<meta name="description" content="LogixCode is Kanpur & Lucknow's top IT training institute offering Python, Java, MERN Stack, Data Science, AI/ML, Android Development courses. 500+ students trained, 100% placement support. Join now at ₹5,000 only!">
<meta name="keywords" content="IT training institute Kanpur, IT training institute Lucknow, Python course Kanpur, Java training Lucknow, full stack development course UP, MERN stack training Kanpur, data science course Lucknow, summer training B.Tech Kanpur, computer training institute Kanpur, LogixCode training, best coaching for programming Lucknow">
<meta name="author" content="LogixCode Training - Dr. Ritesh Kumar Tiwari">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="https://training.logixcode.com/">

<!-- ============================================= -->
<!-- ✅ SEO: OPEN GRAPH (Facebook/WhatsApp share)  -->
<!-- ============================================= -->
<meta property="og:type" content="website">
<meta property="og:title" content="LogixCode Training - Best IT Training Institute in Kanpur & Lucknow">
<meta property="og:description" content="Transform your career with professional tech training in Kanpur & Lucknow. Python, Java, Full Stack, AI/ML courses. 500+ students trained. ₹5,000 onwards.">
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
<meta name="twitter:title" content="LogixCode Training - Best IT Training Kanpur & Lucknow">
<meta name="twitter:description" content="Python, Java, MERN Stack, Data Science courses in Kanpur & Lucknow. 500+ students trained, 100% placement support.">
<meta name="twitter:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">

<!-- ============================================= -->
<!-- ✅ SEO: GEO TAGS (Local SEO Kanpur/Lucknow)  -->
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
      "description": "LogixCode is a leading IT training institute in Kanpur and Lucknow offering professional courses in Python, Java, MERN Stack, Data Science, AI/ML, Android Development, and more.",
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
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "1000",
        "bestRating": "5"
      },
      "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "IT Training Courses",
        "itemListElement": [
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Python Development", "description": "Build powerful backend applications using Python and Django framework"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "MERN Stack Development", "description": "Learn MongoDB, Express, React, and Node.js for full-stack web development"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Java Programming", "description": "Master Java and Spring Boot for enterprise-grade applications"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Data Science & Analytics", "description": "Learn data analysis, machine learning and business insights"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Android Development", "description": "Build Android apps using Java, Kotlin and modern mobile development tools"}},
          {"@type": "Offer", "itemOffered": {"@type": "Course", "name": "Cloud Computing AWS", "description": "Master Amazon Web Services, cloud architecture, EC2, S3, Lambda and DevOps"}}
        ]
      }
    },
    {
      "@type": "WebPage",
      "@id": "https://training.logixcode.com/#webpage",
      "url": "https://training.logixcode.com/",
      "name": "Best IT Training Institute in Kanpur & Lucknow | LogixCode",
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

<!-- ✅ PERFORMANCE: Preconnect to external domains -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://res.cloudinary.com">
<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Material Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#03c4ce",
                "primary-accent": "#00B8C8",
                "background-light": "#f5f8f8",
                "background-dark": "#0f2223",
                "accent-deep": "#083344",
                "dark-blue": "#0f172a"
            },
            fontFamily: {
                display: ['Inter', 'sans-serif']
            },
            animation: {
                fadeIn: "fadeIn 0.3s ease-in-out",
                slideDown: "slideDown 0.4s ease-in-out",
                scaleIn: "scaleIn 0.25s ease-in-out"
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0", transform: "translateY(-10px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" }
                },
                slideDown: {
                    "0%": { opacity: "0", transform: "translateY(-20px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" }
                },
                scaleIn: {
                    "0%": { opacity: "0", transform: "scale(0.95)" },
                    "100%": { opacity: "1", transform: "scale(1)" }
                }
            },
            transitionProperty: {
                height: "height",
                spacing: "margin, padding",
                transformOpacity: "transform, opacity",
            },
            transitionDuration: {
                250: "250ms",
                400: "400ms"
            },
            transitionTimingFunction: {
                smooth: "cubic-bezier(0.4, 0, 0.2, 1)",
                bounce: "cubic-bezier(0.68, -0.55, 0.27, 1.55)"
            },
            boxShadow: {
                primary: "0 0 20px rgba(3,196,206,0.3)",
                soft: "0 10px 30px rgba(0,0,0,0.1)"
            },
            backdropBlur: {
                xs: "2px"
            }
        }
    }
}
</script>
<style>
html, body {
    overflow-x: hidden;
    scroll-behavior: smooth;
}
body {
    font-family: 'Inter', sans-serif;
}
.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(13,150,139,0.1);
}
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(13,150,139,0.1);
}
.tech-pattern {
    background-image: radial-gradient(circle at 2px 2px, rgba(13,150,139,0.05) 1px, transparent 0);
    background-size: 24px 24px;
}
.footer-gradient {
    background: linear-gradient(135deg,#081a18 0%,#0d2e2b 100%);
}
.link-hover-effect {
    position: relative;
    text-decoration: none;
}
.link-hover-effect::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: #00B8C8;
    transition: width 0.3s ease;
}
.link-hover-effect:hover::after {
    width: 100%;
}
@keyframes float {
    0%   { transform: translateY(0px); }
    50%  { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}
.animate-float {
    animation: float 4s ease-in-out infinite;
}
.logo-card img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
</head>

<!-- ✅ SEO: itemscope + itemtype on body for better entity recognition -->
<body class="bg-background-light font-display text-slate-900" itemscope itemtype="https://schema.org/WebPage">

<!-- ================================= -->
<!-- HEADER -->
<!-- ================================= -->
<?php include "includes/navbar.php" ?>

<!-- ================================= -->
<!-- MAIN CONTENT -->
<!-- ================================= -->
<main class="lg:mt-20 mt-14">

<!-- ================================= -->
<!-- HERO SECTION -->
<!-- ================================= -->
<section id="hero" class="relative overflow-hidden bg-gradient-to-br from-background-light via-primary/5 to-background-light">
    <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-primary/10 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-[500px] w-[500px] rounded-full bg-primary/5 blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-6 py-16 lg:py-24">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 items-center">

            <!-- LEFT COLUMN -->
            <div class="lg:col-span-4 flex flex-col gap-8">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-primary">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[green] opacity-80"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-[#90EE90] border border-[1px] border-white"></span>
                        </span>
                        Admissions Open 2026
                    </div>

                    <!-- ✅ SEO: H1 mein city names include ki — Kanpur & Lucknow -->
                    <h1 class="text-4xl md:text-5xl xl:text-[55px] font-extrabold leading-[1.1] tracking-tight text-slate-900">
                        <span class="block md:whitespace-nowrap">Best IT Training in</span>
                        <span class="block"><span class="text-primary">Kanpur & Lucknow</span></span>
                    </h1>

                    <!-- ✅ SEO: Description mein local keywords -->
                    <p class="text-lg leading-relaxed text-slate-600">
                        LogixCode — Kanpur aur Lucknow ka #1 IT training institute. Python, Java, MERN Stack, Data Science aur AI courses ke saath apna career transform karein. 500+ students placed, 100% placement support.
                    </p>
                </div>

                <!-- CTA -->
                <div class="flex flex-row gap-4">
                    <a href="#courses">
                        <button class="rounded-2xl bg-primary px-8 py-4 text-base font-bold text-white shadow-xl shadow-primary/30 hover:scale-[1.02] transition-transform">
                            Explore Courses
                        </button>
                    </a>
                    <a href="#benefits">
                        <button class="rounded-2xl bg-white px-8 py-4 text-base font-bold text-slate-900 border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors">
                            Training Benefits
                        </button>
                    </a>
                </div>

                <!-- STATS -->
                <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                    <div class="rounded-2xl border border-primary/10 bg-white/50 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-black text-primary">500+</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-tight">Students Trained</p>
                    </div>
                    <div class="rounded-2xl border border-primary/10 bg-white/50 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-black text-primary">40+</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-tight">Tech Courses</p>
                    </div>
                    <div class="rounded-2xl border border-primary/10 bg-white/50 p-4 backdrop-blur-sm">
                        <p class="text-2xl font-black text-primary">100%</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-tight">Practical Training</p>
                    </div>
                </div>
            </div>

            <!-- CENTER VISUAL -->
            <div class="relative lg:col-span-4 h-[500px] flex items-center justify-center">
                <div class="relative w-full max-w-[400px]">
                    <div class="absolute top-0 left-0 z-20 h-48 w-48 overflow-hidden rounded-2xl shadow-2xl border-4 border-white rotate-[-6deg] animate-float"
                        style="animation-delay: 0s; background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuDZNqrWcwX50-h__QpbM8R_vWOQxRd8vFgM_3wLwR7NyMKwoLP1L-RLgN3tn9kuD9E7j8xa-I2Ouxp_L-fm7STzk6wsk_4TfcPTi45ED4eqcw96r_VJUcHQZCb8riT4sncIe8Ihd0SlNlLFBxAJ3B9g59g8055RiF4IATQGzVj8uEjlCFHwd2brUYDh0j53axpKEwrFjgNzgJMgaHAw_ZCt9DixZdmULArv1j8TI4e6LnokHa5Y3fBeUdv6RF7AG5WZ1jyKt-2MwTI'); background-size:cover;">
                    </div>
                    <div class="absolute top-20 right-0 z-10 h-56 w-56 overflow-hidden rounded-2xl shadow-2xl border-4 border-white rotate-[4deg] animate-float"
                        style="animation-delay: 1.5s; background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuBWyk8kPS3FEbyqvTgte6of93qqrmLyaBEHOROR_ze81ZBcCGPzjLxhDCLRQXwWLLWnBrLc4EXHH1G9GdYT13YwJ1LyBdIoHdO8IICLQguOh9QQ8609ZrBzr5Jzmmz1gdc2_2iKobiK7n7bmGVSr2xgwhbEbHgWwzeVRVIMTq4fc79aa7IemQaYqg_1IP40PESoe3AtdbjstVIcAjvJ2R1-J7W2sY-Zhz_34KVbb3p9naBvC7jNn-akRI0e0GK_LrOHSHBr0Zijtjc'); background-size:cover;">
                    </div>
                    <div class="absolute bottom-0 left-10 z-30 h-44 w-44 overflow-hidden rounded-2xl shadow-2xl border-4 border-white rotate-[-2deg] animate-float"
                        style="animation-delay: 0.8s; background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuC4G6br_1e39TRYYH_PzLbP9zXk_rO6WoArS1fAPJTGiIWsfG3yP2eX8D3s9zzRLChIOClv0tPp2b9VHHsAB0PMBn2aebYaesXidv6bvJFRqKDWzbryHO1VZ7KZK2uhDLk-1AjG4moLYuBPjEU2dnyddq6GTAWmnWwiHwgE6hVjIlglRaw_TRytqeEjkjJkg60d4KUcoILzjjZXozCL0rthwko7LTq_Joflwko7LTq_JoflwqKLFtftk2uIvc_nCu9yj6dq4SbgPznf4DzJk5IpJn4'); background-size:cover;">
                    </div>
                </div>
            </div>

            <!-- FORM -->
            <div class="lg:col-span-4">
                <div class="relative overflow-hidden rounded-2xl bg-white p-8 shadow-2xl shadow-primary/10 border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-1">Start Your Journey</h2>
                    <p class="text-sm text-slate-500 mb-6">Free career counseling — Kanpur & Lucknow</p>

                    <?php if(isset($_GET['submitted'])): ?>
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-semibold">
                        ✅ Request submitted! Hum aapko jald hi contact karenge.
                    </div>
                    <?php endif; ?>

                    <form id="CounselingForm" action="" method="POST" class="space-y-6" onsubmit="return validateCaptcha()">
                        <input class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:ring-2 focus:ring-primary" required placeholder="Full Name" name="full_name">
                        <input class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:ring-2 focus:ring-primary" required placeholder="Phone Number" name="phone" type="tel" pattern="[0-9]{10}" title="10 digit phone number">

                        <select class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:ring-2 focus:ring-primary" name="interest" required>
                            <option value="">Your Interest</option>
                            <option value="summer training">Summer Training</option>
                            <option value="winter training">Winter Training</option>
                            <option value="full stack development">Full Stack Development</option>
                            <option value="java programming">Java Programming</option>
                            <option value="python development">Python Development</option>
                            <!-- ✅ FIXED: "mern stact" → "mern stack" -->
                            <option value="mern stack development">MERN Stack Development</option>
                            <option value="android development">Android Development</option>
                            <option value="data science & analytics">Data Science & Analytics</option>
                            <option value="ai & machine learning">AI & Machine Learning</option>
                            <!-- ✅ FIXED: "frountend" → "frontend" -->
                            <option value="frontend development">Frontend Development</option>
                            <option value="cloud computing aws">Cloud Computing (AWS)</option>
                        </select>

                        <!-- CAPTCHA -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-gray-700">Verification</label>
                                <div class="flex items-center gap-3">
                                    <div id="captchaBox" class="flex-1 flex items-center justify-center h-12 bg-gray-200 border border-gray-300 rounded-lg select-none">
                                        <span id="captchaText" class="text-lg font-bold tracking-widest text-gray-700 line-through"></span>
                                    </div>
                                    <button type="button" onclick="generateCaptcha()" class="h-12 px-4 rounded-lg bg-primary text-white font-semibold hover:opacity-90 transition">↻</button>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-semibold text-gray-700" for="captcha_input">Enter Captcha</label>
                                <input required id="captcha_input" name="captcha_input" type="text" maxlength="6" placeholder="Type Captcha"
                                    class="h-12 w-full px-4 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"/>
                                <p id="captchaError" class="text-sm text-red-500 hidden">Invalid captcha. Please try again.</p>
                            </div>
                        </div>

                        <button class="w-full rounded-2xl bg-primary py-4 text-sm font-bold text-white shadow-lg hover:opacity-90 transition">
                            Register for Free Counseling
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
                        if (userInput.toUpperCase() !== currentCaptcha) { // ✅ FIXED: case-insensitive match
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

    <!-- PARTNERS -->
    <div class="border-y border-slate-100 bg-white py-12">
        <div class="mx-auto max-w-7xl px-6 text-center">
            <p class="mb-8 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                Trusted by world leading tech companies
            </p>
            <div class="flex flex-wrap items-center justify-center gap-12 opacity-40 grayscale hover:grayscale-0 transition">
                <div class="text-2xl font-black text-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">cloud</span> GOOGLE
                </div>
                <div class="text-2xl font-black text-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">database</span> ORACLE
                </div>
                <div class="text-2xl font-black text-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">code</span> MICROSOFT
                </div>
                <div class="text-2xl font-black text-slate-600 flex items-center gap-2">
                    <span class="material-symbols-outlined">api</span> AWS
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TRAINING PROGRAMS -->
<!-- ================================= -->
<section class="py-24 bg-surface-container-low relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-primary/5 rounded-full blur-3xl -z-0 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-tertiary-container/10 rounded-full blur-3xl -z-0 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary-container/30 text-[#03bfd3] font-label text-sm font-semibold tracking-wide mb-5">
                LOGIXCODE TRAINING PROGRAMS
            </div>
            <!-- ✅ SEO: H2 with local keyword -->
            <h2 class="text-3xl md:text-5xl font-extrabold font-headline tracking-[-0.03em] leading-tight mb-5">
                Transform Your Career With <span class="text-[#03bfd3]">World-Class Training</span>
            </h2>
            <p class="text-base md:text-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Kanpur aur Lucknow ke students ke liye — practically seekho, confidence banao aur industry-ready bano. Real projects, expert mentors aur continuous guidance ke saath.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1 — Summer Training -->
            <div class="group bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#03bfd3] to-[#71f5ff]"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-secondary-container/30 flex items-center justify-center group-hover:bg-[#03bfd3] transition-colors duration-300 shrink-0">
                            <span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white transition-colors text-[22px]" style="font-variation-settings:'FILL' 1">wb_sunny</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary-container/40 text-[#03bfd3] text-xs font-semibold font-label whitespace-nowrap ml-3">
                            <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                            45–60 Days
                        </span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight">Summer Training</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed flex-1">
                        B.Tech, BCA & MCA students ke liye hands-on training — real-time projects aur industry mentorship ke saath. Kanpur/Lucknow ke colleges ke students ke liye ideal.
                    </p>
                    <div class="mt-5 pt-4 border-t border-outline-variant/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-on-surface">₹5,000</span>
                            <span class="text-xs text-on-surface-variant ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-[#03bfd3] text-white text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-primary/20">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2 — Vocational Training -->
            <div class="group bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#43a047] to-[#a5d6a7]"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-[#e8f5e9] flex items-center justify-center group-hover:bg-[#43a047] transition-colors duration-300 shrink-0">
                            <span class="material-symbols-outlined text-[#2e7d32] group-hover:text-white transition-colors text-[22px]" style="font-variation-settings:'FILL' 1">school</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#e8f5e9] text-[#2e7d32] text-xs font-semibold font-label whitespace-nowrap ml-3">
                            <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                            45–60 Days
                        </span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight">Vocational Training</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed flex-1">
                        Diploma & PGDCA students ke liye — practical, structured aur employable skills pe focused training program.
                    </p>
                    <div class="mt-5 pt-4 border-t border-outline-variant/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-on-surface">₹5,000</span>
                            <span class="text-xs text-on-surface-variant ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-[#43a047] text-white text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-green-500/20">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3 — Winter Training -->
            <div class="group bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#1e88e5] to-[#90caf9]"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-[#e3f2fd] flex items-center justify-center group-hover:bg-[#1e88e5] transition-colors duration-300 shrink-0">
                            <span class="material-symbols-outlined text-[#1565c0] group-hover:text-white transition-colors text-[22px]" style="font-variation-settings:'FILL' 1">ac_unit</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#e3f2fd] text-[#1565c0] text-xs font-semibold font-label whitespace-nowrap ml-3">
                            <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                            45 Days
                        </span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight">Winter Training</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed flex-1">
                        Corporate-ready bano structured learning, coding drills aur project experience ke saath.
                    </p>
                    <div class="mt-5 pt-4 border-t border-outline-variant/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-on-surface">₹5,000</span>
                            <span class="text-xs text-on-surface-variant ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-[#1e88e5] text-white text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-blue-500/20">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 4 — Apprenticeship (Featured) -->
            <div class="group bg-[#03bfd3] rounded-xl overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/30 flex flex-col">
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-white/20 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">work</span>
                        </div>
                        <div class="flex flex-col items-end gap-1.5 ml-3">
                            <span class="bg-white/25 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full font-label tracking-wide whitespace-nowrap">⭐ PREMIUM</span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold font-label whitespace-nowrap">
                                <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                                6 Months
                            </span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight text-white">Apprenticeship</h3>
                    <p class="text-sm text-white/80 leading-relaxed flex-1">
                        Expert consultants ke saath kaam karo aur graduation ke baad real-world exposure gain karo.
                    </p>
                    <div class="mt-5 pt-4 border-t border-white/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-white">₹25,000</span>
                            <span class="text-xs text-white/70 ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-white text-[#03bfd3] text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 5 — Syllabus Training -->
            <div class="group bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#fb8c00] to-[#ffcc80]"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-[#fff3e0] flex items-center justify-center group-hover:bg-[#fb8c00] transition-colors duration-300 shrink-0">
                            <span class="material-symbols-outlined text-[#e65100] group-hover:text-white transition-colors text-[22px]" style="font-variation-settings:'FILL' 1">menu_book</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#fff3e0] text-[#e65100] text-xs font-semibold font-label whitespace-nowrap ml-3">
                            <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                            30 Days
                        </span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight">Syllabus Training</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed flex-1">
                        Subjects ko practically samjho simplified real-world examples aur guided sessions ke saath.
                    </p>
                    <div class="mt-5 pt-4 border-t border-outline-variant/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-on-surface">₹5,000</span>
                            <span class="text-xs text-on-surface-variant ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-[#fb8c00] text-white text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-orange-500/20">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 6 — PD & Skill Development -->
            <div class="group bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/10 flex flex-col">
                <div class="h-1.5 bg-gradient-to-r from-[#7e57c2] to-[#ce93d8]"></div>
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-lg bg-[#ede7f6] flex items-center justify-center group-hover:bg-[#7e57c2] transition-colors duration-300 shrink-0">
                            <span class="material-symbols-outlined text-[#4527a0] group-hover:text-white transition-colors text-[22px]" style="font-variation-settings:'FILL' 1">psychology</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#ede7f6] text-[#4527a0] text-xs font-semibold font-label whitespace-nowrap ml-3">
                            <span class="material-symbols-outlined text-xs" style="font-size:14px">schedule</span>
                            30 Days
                        </span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2 tracking-tight">PD & Skill Development</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed flex-1">
                        Personality, communication, presentation skills aur self-confidence improve karo expert trainers ke saath.
                    </p>
                    <div class="mt-5 pt-4 border-t border-outline-variant/20 flex flex-col gap-3">
                        <div>
                            <span class="text-2xl font-extrabold font-headline text-on-surface">₹5,000</span>
                            <span class="text-xs text-on-surface-variant ml-1">/ program</span>
                        </div>
                        <a href="/registration" class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-full bg-[#7e57c2] text-white text-sm font-bold hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-purple-500/20">
                            Know More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================================= -->
<!-- POPULAR COURSES -->
<!-- ================================= -->
<section id="courses" class="relative overflow-hidden py-24 bg-gradient-to-br from-background-light via-primary/5 to-white">
    <div aria-hidden="true" class="absolute top-0 right-0 -translate-y-12 translate-x-12 opacity-10 pointer-events-none">
        <span class="material-symbols-outlined text-[200px] text-primary">code</span>
    </div>
    <div aria-hidden="true" class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 opacity-10 pointer-events-none">
        <span class="material-symbols-outlined text-[200px] text-primary">terminal</span>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 mb-4 text-xs font-bold tracking-widest text-primary uppercase bg-primary/10 rounded-full">OUR PROGRAMS</span>
            <!-- ✅ SEO: Course section H2 with keywords -->
            <h2 class="text-3xl md:text-5xl font-black text-dark-blue mb-6">
                IT Courses in Kanpur & Lucknow
            </h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-600 leading-relaxed">
                Industry-focused training programs jo real-world technical skills build karte hain aur Kanpur, Lucknow aur poore UP mein aapka career accelerate karte hain.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Card 1: Data Analytics -->
            <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1773920472/ChatGPT_Image_Mar_19_2026_05_10_15_PM_fuuryh.png"
                        alt="Data Analytics Course in Kanpur Lucknow - LogixCode"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-3xl">analytics</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">Data Analytics</h3>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                        Learn data analysis, visualization, and business insights using modern tools like Tableau and PowerBI.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 6 Weeks
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-100">
                        <button onclick="window.location='/registration'" class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2: Python Development -->
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img alt="Python Development Course Kanpur Lucknow - LogixCode" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://res.cloudinary.com/de7mh41io/image/upload/v1773921071/ChatGPT_Image_Mar_19_2026_05_14_23_PM_jdahz7.png"/>
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">data_object</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">Python Development</h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-2">
                        Build powerful backend applications using Python and Django framework for enterprise scalability.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 45 Days - 6 Months
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">signal_cellular_alt_2_bar</span> Intermediate
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-50">
                        <button class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors" onclick="window.location='/registration'">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3: MERN Stack -->
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img alt="MERN Stack Development Course Kanpur - LogixCode" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://res.cloudinary.com/de7mh41io/image/upload/v1773921345/ChatGPT_Image_Mar_19_2026_05_25_25_PM_yzlrs1.png"/>
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">developer_mode_tv</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">MERN Stack Development</h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-2">
                        Learn MongoDB, Express, React, and Node.js to build modern, performant full-stack web applications.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 6 Months
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">work</span> Job Oriented
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-50">
                        <button class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors" onclick="window.location='/registration'">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4: Java Programming -->
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img alt="Java Programming Course Lucknow Kanpur - LogixCode" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://res.cloudinary.com/de7mh41io/image/upload/v1773921632/ChatGPT_Image_Mar_19_2026_05_29_57_PM_vvgjdl.png"/>
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">coffee</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">Java Programming</h3>
                    <p class="text-slate-600 text-sm mb-6 line-clamp-2">
                        Master Java, Spring Boot, and modern frontend technologies for complex enterprise-grade applications.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 6 Months
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">work</span> Job Oriented
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-50">
                        <button class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors" onclick="window.location='/registration'">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 5: Android Development ✅ FIXED description -->
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img alt="Android Development Course Kanpur - LogixCode" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://res.cloudinary.com/de7mh41io/image/upload/v1773921707/ChatGPT_Image_Mar_19_2026_05_31_14_PM_d5pfv1.png"/>
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">android</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">Android Development</h3>
                    <!-- ✅ FIXED: Correct Android description -->
                    <p class="text-slate-600 text-sm mb-6 line-clamp-2">
                        Build professional Android apps using Java, Kotlin, XML layouts and modern mobile development tools like Jetpack and Supabase.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 3 - 6 Months
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-50">
                        <button class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors" onclick="window.location='/registration'">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 6: Cloud Computing AWS ✅ FIXED description -->
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="h-48 overflow-hidden">
                    <img alt="Cloud Computing AWS Course Kanpur Lucknow - LogixCode" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="https://res.cloudinary.com/de7mh41io/image/upload/v1773921761/ChatGPT_Image_Mar_19_2026_05_32_32_PM_ve94vl.png"/>
                </div>
                <div class="p-6">
                    <div class="mb-6 h-12 w-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">cloud</span>
                    </div>
                    <h3 class="text-xl font-bold text-dark-blue mb-3 group-hover:text-primary transition-colors">Cloud Computing (AWS)</h3>
                    <!-- ✅ FIXED: Correct AWS description (was showing Digital Marketing content!) -->
                    <p class="text-slate-600 text-sm mb-6 line-clamp-2">
                        Master Amazon Web Services — EC2, S3, Lambda, RDS, CloudFormation. Learn cloud architecture, DevOps and deployment for real-world projects.
                    </p>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-slate-100 rounded-md text-[11px] font-bold text-slate-600 flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">schedule</span> 3 Months
                        </span>
                        <span class="px-3 py-1 bg-primary/5 rounded-md text-[11px] font-bold text-primary flex items-center gap-1 uppercase">
                            <span class="material-symbols-outlined text-sm">work</span> Job Oriented
                        </span>
                    </div>
                    <div class="flex gap-2 pt-4 border-t border-slate-50">
                        <button class="flex-1 bg-primary text-white py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors" onclick="window.location='/registration'">Enroll Now</button>
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-lg">info</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-20 text-center">
            <p class="mt-6 text-sm text-slate-500 italic">Join over 500+ students already learning today in Kanpur & Lucknow.</p>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- ABOUT -->
<!-- ================================= -->
<section id="about" class="relative overflow-hidden py-24">
    <div aria-hidden="true" class="absolute top-0 right-0 -z-10 h-[500px] w-[500px] rounded-full bg-primary/5 blur-3xl"></div>
    <div aria-hidden="true" class="absolute bottom-0 left-0 -z-10 h-[400px] w-[400px] rounded-full bg-primary/5 blur-3xl"></div>

    <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="flex flex-col gap-6">
                <div>
                    <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">ABOUT LOGIXCODE</span>
                    <h2 class="mt-4 text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
                        Leading the Way in <span class="text-primary">Tech Education</span>
                    </h2>
                </div>
                <!-- ✅ SEO: About section mein location mention -->
                <p class="text-lg leading-relaxed text-slate-600">
                  LogixCode, headquartered in Kanpur, is a forward-thinking IT training institute that bridges the gap between academia and industry requirements. We empower tech leaders across Lucknow, Uttar Pradesh, and all over North India.
                </p>
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-900">Who We Are</h3>
                    <p class="text-slate-600">
                        We provide industry-ready training through intensive project-based learning. Our curriculum is designed by professionals to ensure students gain practical skills that matter in the real world.
                    </p>
                </div>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <li class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-sm">rocket_launch</span>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Project-Based Training</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-sm">school</span>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Expert Mentors</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-sm">work</span>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Internship Opportunities</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-sm">assignment_turned_in</span>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Placement Assistance</span>
                    </li>
                </ul>
                <div class="pt-4">
                    <a href="/about">
                        <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-[#0b7a71] px-8 py-4 text-base font-bold text-white shadow-xl shadow-primary/20 hover:opacity-90 transition-all">
                            Learn More About Us
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="relative overflow-hidden rounded-xl shadow-2xl">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769591122/Untitled_design_10_jbnn0e.webp"
                        alt="LogixCode students learning at IT training institute in Kanpur"
                        class="h-[500px] w-full object-cover transition-transform duration-500 hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                </div>
                <div class="absolute -bottom-8 -left-4 right-4 sm:-right-8 lg:-right-12">
                    <div class="rounded-xl bg-white p-6 shadow-2xl ring-1 ring-slate-200">
                        <div class="grid grid-cols-3 divide-x divide-slate-100">
                            <div class="px-4 text-center">
                                <div class="text-2xl font-bold text-primary">500+</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Students Trained</div>
                            </div>
                            <div class="px-4 text-center">
                                <div class="text-2xl font-bold text-primary">40+</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Courses Available</div>
                            </div>
                            <div class="px-4 text-center">
                                <div class="text-2xl font-bold text-primary">100%</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Practical Training</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TRAINING BENEFITS -->
<!-- ================================= -->
<section id="benefits" class="relative py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-bold tracking-widest uppercase">Advantages</span>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">Training Benefits</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-600 leading-relaxed">
                Discover why thousands of learners trust LogixCode for professional and career-oriented training. We bridge the gap between education and industry.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">work_outline</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">100% Job Oriented</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Training programs designed according to industry requirements so students become job-ready from day one.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">Expert Mentors</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Learn from experienced industry professionals who bring real-world expertise into every session.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">handshake</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">Placement Support</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Dedicated placement assistance with connections to multiple companies for job opportunities.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">code_blocks</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">Live Projects</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Work on real industry projects to build a strong portfolio and practical experience.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">verified_user</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">Certification</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Receive industry-recognized certification after successfully completing the course.</p>
            </div>
            <div class="group bg-white p-8 rounded-2xl border border-primary/5 hover:border-primary/20 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-3xl">schedule</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-slate-900">Flexible Timing</h3>
                <p class="text-sm lg:text-base text-slate-600 leading-relaxed">Choose weekday, weekend, or online batches that fit your schedule.</p>
            </div>
        </div>

        <div class="mt-20 flex flex-col sm:flex-row items-center justify-center gap-6">
            <div class="flex flex-col items-center sm:items-start">
                <p class="text-sm text-slate-500 mb-1 lg:ml-10">Ready to start your journey?</p>
                <a href="#courses">
                    <button class="bg-primary text-white px-10 py-4 rounded-xl text-lg font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-transform">
                        Explore Our Courses
                    </button>
                </a>
            </div>
            <div class="hidden sm:block h-12 w-px bg-slate-200"></div>
            <div class="text-center sm:text-left">
                <p class="text-sm text-slate-500 mb-1">Have questions?</p>
                <a href="#CounselingForm" class="text-primary font-bold text-lg hover:underline flex items-center gap-2">
                    Talk to a Career Counselor
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- WHY CHOOSE US -->
<!-- ================================= -->
<section id="why-choose-us" class="relative py-24 overflow-hidden">
    <div aria-hidden="true" class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute top-48 -right-24 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16 max-w-3xl mx-auto">
            <span class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-[0.2em] text-primary uppercase bg-primary/10 rounded-full border border-primary/20">WHY CHOOSE LOGIXCODE</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-accent-blue mb-6 leading-tight">What Makes Us Different</h2>
            <p class="text-lg text-slate-600 leading-relaxed">We combine industry-driven curriculum, expert mentorship, and real-world project experience to help students build successful technology careers.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
            <div class="group relative bg-white p-8 rounded-2xl border border-primary/10 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors shadow-inner">
                        <span class="material-symbols-outlined text-3xl">military_tech</span>
                    </div>
                    <h3 class="text-xl font-bold text-accent-blue mb-3">Award Winning Institute</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Recognized for excellence in IT training with innovative teaching methods and student success rates.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-primary group-hover:w-full transition-all duration-300 rounded-b-2xl"></div>
            </div>
            <div class="group relative bg-white p-8 rounded-2xl border border-primary/10 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors shadow-inner">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>
                    <h3 class="text-xl font-bold text-accent-blue mb-3">500+ Students Trained</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Thousands of students have successfully built high-growth careers through our professional programs.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-primary group-hover:w-full transition-all duration-300 rounded-b-2xl"></div>
            </div>
            <div class="group relative bg-white p-8 rounded-2xl border border-primary/10 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors shadow-inner">
                        <span class="material-symbols-outlined text-3xl">auto_stories</span>
                    </div>
                    <h3 class="text-xl font-bold text-accent-blue mb-3">Industry-Ready Curriculum</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Courses designed according to current industry standards and the latest tech stacks and real tools.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-primary group-hover:w-full transition-all duration-300 rounded-b-2xl"></div>
            </div>
            <div class="group relative bg-white p-8 rounded-2xl border border-primary/10 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors shadow-inner">
                        <span class="material-symbols-outlined text-3xl">work_history</span>
                    </div>
                    <h3 class="text-xl font-bold text-accent-blue mb-3">Strong Placement Support</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Dedicated support with resume building, mock interview preparation, and direct partner referrals.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-primary group-hover:w-full transition-all duration-300 rounded-b-2xl"></div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="bg-accent-blue rounded-2xl p-8 md:p-12 mb-16 shadow-2xl relative overflow-hidden">
            <div aria-hidden="true" class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 relative z-10 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-black mb-2">500+</div>
                    <div class="text-primary text-sm uppercase tracking-wider font-semibold">Students Trained</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-black mb-2">40+</div>
                    <div class="text-primary text-sm uppercase tracking-wider font-semibold">Pro Courses</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-black mb-2">100+</div>
                    <div class="text-primary text-sm uppercase tracking-wider font-semibold">Hiring Partners</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-black mb-2">100%</div>
                    <div class="text-primary text-sm uppercase tracking-wider font-semibold">Practical Training</div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="/registration">
                <button class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-[#14b8a6] hover:scale-105 text-white px-10 py-5 rounded-xl text-lg font-bold transition-all shadow-xl shadow-primary/30">
                    Start Your Learning Journey
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </a>
            <p class="mt-6 text-sm text-slate-500">Join the community of developers already learning with us in Kanpur & Lucknow.</p>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- OUR RECRUITERS -->
<!-- ================================= -->
<section id="recruiters" class="relative py-16 md:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-4 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-wider uppercase mb-4">OUR HIRING NETWORK</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6">Our Recruiters</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-600 leading-relaxed">
                Our students get opportunities to work with leading technology companies, from innovative startups to global tech giants. Join the ranks of our successful alumni.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-20 text-center">
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white shadow-sm">
                <div class="text-3xl font-bold text-primary mb-1">500+</div>
                <div class="text-sm text-slate-500 font-medium">Students Placed</div>
            </div>
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white shadow-sm">
                <div class="text-3xl font-bold text-primary mb-1">100+</div>
                <div class="text-sm text-slate-500 font-medium">Hiring Partners</div>
            </div>
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white shadow-sm">
                <div class="text-3xl font-bold text-primary mb-1">40+</div>
                <div class="text-sm text-slate-500 font-medium">Industry Courses</div>
            </div>
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-white shadow-sm">
                <div class="text-3xl font-bold text-primary mb-1">10+</div>
                <div class="text-sm text-slate-500 font-medium">Partner Cities</div>
            </div>
        </div>

        <!-- Company Logos -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3">
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769592753/download_veq7cq.png" alt="Hiring Partner 1"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769592569/websofy-software-pvt-ltd-indira-nagar-lucknow-lucknow-internet-website-designers-syiggg1ogp_idnlu5.avif" alt="Websofy Software Lucknow"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593249/unnamed_zoxwmw.jpg" alt="Hiring Partner 3"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593269/images_dz6wnl.png" alt="Hiring Partner 4"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3">
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593329/codeaspire_logo_qtvjzo.jpg" alt="CodeAspire"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593345/download_fnlauu.jpg" alt="Hiring Partner 6"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593363/images_bljkqe.png" alt="Hiring Partner 7"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593391/techno_friend_logo_q2ma1m.jpg" alt="TechnoFriend"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3">
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593415/k2technologies_logo_hch5yw.jpg" alt="K2 Technologies"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593436/images_g6ptag.png" alt="Hiring Partner 10"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593448/download_g76lla.png" alt="Hiring Partner 11"></div>
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593488/download_tkcz4c.png" alt="Hiring Partner 12"></div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-3">
            <div class="logo-card bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-center h-32"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769593528/download_dlxuol.png" alt="Hiring Partner 13"></div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TECHNOLOGIES & SKILLS -->
<!-- ================================= -->
<section id="technologies" class="relative py-24 overflow-hidden tech-pattern bg-gradient-to-b from-primary/5 to-transparent">
    <div class="max-w-4xl mx-auto text-center px-6 mb-16">
        <span class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest uppercase bg-primary/10 text-primary rounded-full">TECHNOLOGIES & SKILLS</span>
        <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 text-slate-900">What You'll Learn</h2>
        <p class="text-lg md:text-xl text-slate-600 leading-relaxed">
            Master the most in-demand programming languages, frameworks, and technologies used by top companies in real-world software development.
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <!-- Tech cards (unchanged from original - they are correct) -->
            <div class="group hover-lift bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm transition-all duration-300">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769665593/python_zu48cm.png" alt="Python" class="size-10">
                </div>
                <h3 class="font-bold text-lg mb-2">Python</h3>
                <p class="text-xs text-slate-500 mb-4">Powerful backend programming, automation, and data processing.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
            <div class="group hover-lift bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm transition-all duration-300">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg" alt="React" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">React</h3>
                <p class="text-xs text-slate-500 mb-4">Build modern and interactive user interfaces.</p>
                <span class="mt-auto px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-bold rounded uppercase tracking-wider">Frontend</span>
            </div>
            <div class="group hover-lift bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm transition-all duration-300">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg" alt="NodeJS" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">Node.js</h3>
                <p class="text-xs text-slate-500 mb-4">Scalable server-side development using JavaScript.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-4xl text-primary">analytics</span>
                </div>
                <h3 class="font-bold text-lg mb-2">Data Science</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Extract insights and patterns from complex data sets.</p>
                <span class="mt-auto px-3 py-1 bg-purple-100 text-purple-600 text-[10px] font-bold rounded uppercase tracking-wider">Analytics</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg" alt="Angular" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">Angular</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Enterprise-grade framework for robust web apps.</p>
                <span class="mt-auto px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-bold rounded uppercase tracking-wider">Frontend</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg" alt="Flutter" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">Flutter</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Build native apps for mobile, web, and desktop.</p>
                <span class="mt-auto px-3 py-1 bg-indigo-100 text-indigo-600 text-[10px] font-bold rounded uppercase tracking-wider">Mobile</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/django/django-plain.svg" alt="Django" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">Django</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">High-level Python web framework for fast development.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dot-net/dot-net-original.svg" alt="ASP.NET" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">ASP.NET</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Professional web development with C# and .NET.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1769680552/MERN-logo_reyyc8.png" alt="MERN Stack" class="h-auto w-auto"/>
                </div>
                <h3 class="font-bold text-lg mb-2">MERN Stack</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Master MongoDB, Express, React, and Node.js.</p>
                <span class="mt-auto px-3 py-1 bg-orange-100 text-orange-600 text-[10px] font-bold rounded uppercase tracking-wider">Full Stack</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tensorflow/tensorflow-original.svg" alt="AI & Machine Learning" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">AI &amp; Machine Learning</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Build intelligent systems and predictive models.</p>
                <span class="mt-auto px-3 py-1 bg-pink-100 text-pink-600 text-[10px] font-bold rounded uppercase tracking-wider">AI</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg" alt="Java" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">Java</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">The backbone of enterprise software systems.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
            <div class="group hover-lift transition-all duration-300 bg-white p-6 rounded-2xl border border-primary/10 flex flex-col items-center text-center shadow-sm">
                <div class="size-16 mb-4 flex items-center justify-center bg-primary/5 rounded-xl group-hover:scale-110 transition-transform">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" alt="PHP" class="size-10"/>
                </div>
                <h3 class="font-bold text-lg mb-2">PHP</h3>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">Server-side scripting for modern web apps.</p>
                <span class="mt-auto px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider">Backend</span>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- INDUSTRY TOOLS -->
<!-- ================================= -->
<section class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold mb-10">Industry Tools You Will Use</h2>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-70">
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img alt="GitHub" class="size-12 grayscale group-hover:grayscale-0 transition-all" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269881/Industry_Tool_git_ybwef7.svg"/>
                <span class="text-xs font-semibold">GitHub</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img alt="Docker" class="size-12 grayscale group-hover:grayscale-0 transition-all" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_docker_mhqpf7.svg"/>
                <span class="text-xs font-semibold">Docker</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img alt="AWS" class="size-12 grayscale group-hover:grayscale-0 transition-all" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_node_aws_artfzx.svg"/>
                <span class="text-xs font-semibold">AWS</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img alt="VS Code" class="size-12 grayscale group-hover:grayscale-0 transition-all" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269882/Industry_Tool_node_vscode_puvuwj.png"/>
                <span class="text-xs font-semibold">VS Code</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img alt="MySQL" class="size-12 grayscale group-hover:grayscale-0 transition-all" src="https://res.cloudinary.com/de7mh41io/image/upload/v1774269880/Industry_Tool_mysql_txzbon.svg"/>
                <span class="text-xs font-semibold">MySQL</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg" alt="Angular" class="size-12 grayscale group-hover:grayscale-0 transition-all"/>
                <span class="text-xs font-semibold">Angular</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg" alt="Tailwind CSS" class="size-12 grayscale group-hover:grayscale-0 transition-all"/>
                <span class="text-xs font-semibold">Tailwind</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg" alt="MongoDB" class="size-12 grayscale group-hover:grayscale-0 transition-all"/>
                <span class="text-xs font-semibold">MongoDB</span>
            </div>
            <div class="flex flex-col items-center gap-2 group cursor-pointer">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linux/linux-original.svg" alt="Linux" class="size-12 grayscale group-hover:grayscale-0 transition-all"/>
                <span class="text-xs font-semibold">Linux</span>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- DIRECTOR MESSAGE -->
<!-- ================================= -->
<section id="director-message" class="relative py-16 md:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="bg-white border border-primary/10 rounded-2xl shadow-xl shadow-primary/5 overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-2/5 p-8 lg:p-12 flex flex-col items-center justify-center bg-primary/5">
                    <div class="relative group">
                        <div class="absolute -inset-4 border-2 border-primary/20 rounded-2xl scale-105 group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative h-[400px] w-full max-w-[320px] overflow-hidden rounded-2xl shadow-2xl">
                            <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1756809257/WhatsApp_Image_2025-09-02_at_10.53.10_0a7aae5b_j3r6oz.jpg"
                                alt="Dr. Ritesh Kumar Tiwari - Director LogixCode Training Kanpur"
                                class="h-full w-full object-cover">
                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-accent-deep/90 to-transparent p-6">
                                <h3 class="text-white text-xl font-bold">Dr. Ritesh Kumar Tiwari</h3>
                                <p class="text-primary font-medium text-sm">Director, LogixCode</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 grid grid-cols-1 gap-4 w-full max-w-[320px]">
                        <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-primary/10 shadow-sm">
                            <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">verified_user</span>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Experience</p>
                                <p class="text-sm font-bold text-accent-deep">15+ Years Industry Expert</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-3/5 p-8 lg:p-16 flex flex-col justify-center">
                    <div class="mb-8">
                        <span class="inline-flex items-center px-4 py-1 rounded-full bg-primary/10 text-primary text-xs font-extrabold tracking-widest uppercase mb-4">Leadership</span>
                        <h2 class="text-4xl lg:text-5xl font-black text-accent-deep leading-tight mb-4 tracking-tight">
                            Director's <span class="text-primary">Message</span>
                        </h2>
                        <p class="text-lg text-slate-600 font-medium leading-relaxed max-w-xl">
                            Visionary leadership empowering the next generation of IT professionals with future-ready technical expertise.
                        </p>
                    </div>
                    <div class="space-y-6 text-slate-700 leading-relaxed mb-10">
                        <p>At <span class="font-bold text-accent-deep">LogixCode</span>, our mission is to empower students with practical, industry-relevant skills. We believe that the traditional academic model often falls short of the rapid pace of the technology sector.</p>
                        <p>Our curriculum is meticulously designed to <span class="text-primary font-semibold">bridge the gap between academia and industry</span>, ensuring our graduates are competent professionals ready to tackle real-world challenges.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">check_circle</span>
                            <span class="font-semibold text-slate-800">Industry-Focused Training</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">check_circle</span>
                            <span class="font-semibold text-slate-800">500+ Students Mentored</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">check_circle</span>
                            <span class="font-semibold text-slate-800">Placement-Ready Curriculum</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">check_circle</span>
                            <span class="font-semibold text-slate-800">Hands-on Lab Projects</span>
                        </div>
                    </div>
                    <div class="pt-8 border-t border-slate-100 flex items-center justify-between flex-wrap gap-6">
                        <div>
                            <p class="text-2xl italic text-accent-deep" style="font-family:'Brush Script MT',cursive;">R.K. Tiwari</p>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-tight">Dr. Ritesh Kumar Tiwari</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- TESTIMONIALS -->
<!-- ================================= -->
<section id="testimonials" class="relative py-24 overflow-hidden">
    <div aria-hidden="true" class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] bg-gradient-to-b from-primary/5 to-transparent pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold tracking-wider text-primary uppercase border border-primary/20 mb-6">Student Testimonials</span>
            <h2 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">What Our Students Say</h2>
            <p class="text-lg text-slate-600 leading-relaxed">Hear from students who transformed their careers with practical training, expert mentorship, and industry-ready skills at LogixCode.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col" itemscope itemtype="https://schema.org/Review">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">RS</div>
                    <div>
                        <h3 class="font-bold text-slate-900" itemprop="author">Rahul Sharma</h3>
                        <p class="text-xs font-semibold text-primary">Python Django Program</p>
                    </div>
                </div>
                <div class="flex gap-1 text-amber-400 mb-4">
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic grow" itemprop="reviewBody">
                    "LogixCode provided the perfect environment to learn practical programming skills. The mentors explained concepts clearly and the hands-on projects helped me gain real industry experience."
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col" itemscope itemtype="https://schema.org/Review">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">PP</div>
                    <div>
                        <h3 class="font-bold text-slate-900" itemprop="author">Priya Patel</h3>
                        <p class="text-xs font-semibold text-primary">Full Stack Development</p>
                    </div>
                </div>
                <div class="flex gap-1 text-amber-400 mb-4">
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic grow" itemprop="reviewBody">
                    "The live projects and practical training made a huge difference in my learning journey. The instructors were extremely supportive and the curriculum covered everything needed to become a confident developer."
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col" itemscope itemtype="https://schema.org/Review">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">AV</div>
                    <div>
                        <h3 class="font-bold text-slate-900" itemprop="author">Aman Verma</h3>
                        <p class="text-xs font-semibold text-primary">Data Analytics</p>
                    </div>
                </div>
                <div class="flex gap-1 text-amber-400 mb-4">
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                    <span class="material-symbols-outlined text-sm">star</span>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic grow" itemprop="reviewBody">
                    "The course structure was very well designed with real-world datasets and tools. I gained strong analytical skills and practical experience that helped me start my career in data analytics."
                </p>
            </div>
        </div>

        <!-- Trust Bar -->
        <div class="mt-20">
            <div class="bg-white/50 backdrop-blur-sm border border-slate-200 rounded-2xl p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8 md:gap-4">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-2xl">verified</span>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-slate-900">500+</span>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest">Students Trained</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-slate-200 hidden md:block"></div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-2xl">business_center</span>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-slate-900">100+</span>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest">Hiring Companies</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-slate-200 hidden md:block"></div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-2xl">star</span>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-slate-900">4.8</span>
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest">Average Rating</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-slate-200 hidden md:block"></div>
                    <div class="flex items-center bg-white rounded-lg px-4 py-3 border border-slate-200 shadow-sm">
                        <div class="mr-3">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col leading-tight">
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-slate-900">4.8</span>
                                <div class="flex text-amber-400">
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                    <span class="material-symbols-outlined text-[12px]">star</span>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-500 font-medium whitespace-nowrap uppercase tracking-tighter">1000+ Google Reviews</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- SERVICES / WHAT WE OFFER -->
<!-- ================================= -->
<section id="services" class="relative py-24 overflow-hidden">
    <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-br from-background-light via-primary/5 to-primary/10 -z-10"></div>
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16 max-w-3xl mx-auto">
            <span class="inline-block px-4 py-1.5 mb-4 text-xs font-bold tracking-widest text-primary uppercase bg-primary/10 rounded-full">WHAT WE OFFER</span>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight">Our Services</h2>
            <p class="text-lg text-slate-600 leading-relaxed">Comprehensive technology training programs designed to build practical skills, industry knowledge, and career opportunities for students and professionals in Kanpur & Lucknow.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Service cards - same as original but with fixed alt texts -->
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuD0WTShFOuAbD48c285Ct1y2eOM94wqOjoHEqfoJ8M73NtiocuccnNaRqm6OzavfgriXidgR-ShB3AHTEl5Qjn4JsvHWmqbeTmHmHfwP6Ze2ENcabPiytDK5yAR43wC4RKD8aA-BLj5JZcZlylLSCCVV0ZUJv6w_AP1z_PLgWb0Y0Pp6KkCb7EhximwEbafGz-mNERDx8Yh5j-2LMAAFGKHmR_y4xc-RqIrt8fliaXtbfXQpb04sWaEf4u4GAu09eORZ1EJnt9NimE')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Certification</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Training Programs</h3>
                    <p class="text-slate-600 text-sm mb-4">Strategic programs covering fundamental and advanced technical concepts.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Summer & Winter Training</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Internships & Apprenticeship</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">check_circle</span> Vocational Training</li>
                    </ul>
                </div>
            </div>
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuBNLrg8fbNOz94eWh0K97GPGhGjKeHK53TBXGOvH-e9BkPqa8NQ-_3s_mirgWviQCRJbK26tdYQEASi12-6arYD_olx5B9Hx6FsVwvm8b-IoZEmdlECLL1a_fsvAYrbz9j-F1rXFiNFFuD9tepkkvXiS-lI1BRombQe8CL_W9Y7ll_zHEE9HOcbkvaK5E0HnYHwZwddo4JlPeSXGYAsxFl4WyF-wlgPZJM3L3iPtInORcX2JGva2OGX2HSoUl2rAhmxTbgHr-J3e44')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Job Oriented</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Programming Languages</h3>
                    <p class="text-slate-600 text-sm mb-4">Master the languages that power modern software systems.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">code</span> Python for Data & Automation</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">code</span> Java Enterprise Edition</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">code</span> PHP Web Development</li>
                    </ul>
                </div>
            </div>
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuD7TGx2VZNIZ5TzP5KI8sqopQmtJYawFubX60yEVS5ijFxsQwOTSnRJSHlAgM7aIiPY3PPTw5wveyoENSpip9ANOooE7xtOpzdGxvq3tf1N9u6_sVvZdNO_wRG-slmE6MCXkNAGNqjystje8431mi3soNmRE1old5cVYKUN726sZJFUT2v_QU-7f7WoAUI-xgE_ln6IvVP1EwREaNr0C3l-X0p1eUeLgXph5N61LZzZLlJEAE22gL1EnHb_Z99YCUHYITFrMRgRkhc')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Industry Projects</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Web & Mobile Development</h3>
                    <p class="text-slate-600 text-sm mb-4">Build scalable cross-platform applications using modern stacks.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">devices</span> MERN Stack & ASP.NET</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">devices</span> Flutter & Android Apps</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">devices</span> Responsive Web Architecture</li>
                    </ul>
                </div>
            </div>
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuB5k29g4ECv0C585Vrh-sT-ByBEHJ6z5nd-MRKeICLMUhvoHymAdhgu1aPsGTmC6sIEy_iDRIfaqElpFk6lp9FhM9gMKQdh5vqTfarbiZY7mYIhni4NBXjzz4Fkbzljse9nagpcPkV-EBRrz3VXvZhooMSgYXJTd_LMKgERga7VLtyBkIVBTre0z7MxOXNpsRDnLusLhaA1QFON3zpwHDmQqXUeftqP5-ro_MMci0ko2pwVerZpk6oma6cWPRNfKieKG3kWtExbtRw')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Practical Training</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Engineering (CAD)</h3>
                    <p class="text-slate-600 text-sm mb-4">Professional drafting and design for engineering disciplines.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">architecture</span> Mechanical CAD & Design</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">architecture</span> Civil & Structural CAD</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">architecture</span> Electrical System Drafting</li>
                    </ul>
                </div>
            </div>
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuC-kEFN9e9jZn0zALBwB6AfR0uodE2Aco_U2tkAmdR_HgWi-98Xtf_7pnqlQc4tLgvmq4NPCJmkvT1CDS2B6jOHoUPkUHEinlsJisxjy7LXGnhLKtyNmvbokaz0eMTtep-BYuW2tFZt-WEYb9dTTqXr2MzXj9v897w-9HOEnv81bgGsS_TcJbqz_uYM0scYbHoygeP665vJSPTtGoXFG1iUNN2ixzqiRiaZODZqX-fRLRY8PBw-_404VCVJ_5QsKn-fH9CYoS8Wwjk')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Certification</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Design & Creative</h3>
                    <p class="text-slate-600 text-sm mb-4">Unleash creativity with industry-leading design tools and principles.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">palette</span> Graphic Design Masterclass</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">palette</span> UI/UX Design Workflow</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">palette</span> Visual Communication</li>
                    </ul>
                </div>
            </div>
            <div class="group bg-white rounded-2xl border border-primary/10 shadow-xl shadow-primary/5 p-2 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                <div class="relative h-48 overflow-hidden rounded-xl mb-6">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110" style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuCxUcfgS20KXjehnX_0UDFfP3qPxFAxeYeYL99Gi0etgDF9nhAxpEAP5eaZjz9UkjlKnwzU07Z7KrpV9VxQw8UbDSxyDGsiAvhXbOV_a7pGhK2AG1NOA0cHkarEv5Ie1epHSPpXVsRqfSRIm8XpkZjYPQ-YpIh6LS4_BGKjAWfxHNiO7CZBpRC1WbiumE2MgIldX62MEtiy7zbVc-xD-Wl6woJA6O-wHbiE9a0-h0NPDizzmxg83a1hwCztdbmz_VXkrzTIj_5XiBg')"></div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary text-white text-[10px] font-bold uppercase rounded-full">Industry Projects</span>
                </div>
                <div class="px-4 pb-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Data & Marketing</h3>
                    <p class="text-slate-600 text-sm mb-4">Harness the power of data and digital strategies for growth.</p>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">insights</span> AI & Machine Learning</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">insights</span> Advanced Data Analytics</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">insights</span> Digital Marketing Strategy</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <a href="#courses">
                <button class="inline-flex items-center gap-2 px-10 py-4 bg-gradient-to-r from-primary to-[#12b4a7] text-white font-bold text-lg rounded-full shadow-xl shadow-primary/30 hover:shadow-primary/50 hover:scale-105 transition-all group">
                    Explore All Training Programs
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </a>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- OUR REACH / CITIES -->
<!-- ================================= -->
<section id="reach" class="relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-16 md:py-24">
        <div class="text-center mb-16 space-y-4">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest border border-primary/20">Our Reach</span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight text-slate-900">Cities We <span class="text-primary">Cover</span></h2>
            <p class="max-w-2xl mx-auto text-slate-600 text-lg leading-relaxed">
                Kanpur se Lucknow tak, North & Central India mein professional IT excellence expand kar rahe hain. Hamare community ka hissa banein.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center mb-24">
            <div class="relative group">
                <div class="relative bg-white p-8 rounded-2xl border border-primary/10 shadow-xl overflow-hidden min-h-[500px] flex items-center justify-center">
                    <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1773748714/ChatGPT_Image_Mar_17_2026_05_28_22_PM_jym0nl.png" alt="LogixCode cities coverage map UP MP" class="w-full"/>
                </div>
            </div>

            <div class="space-y-6">
                <!-- UP Card -->
                <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-primary p-2 bg-primary/10 rounded-lg">location_city</span>
                        <h3 class="text-2xl font-bold">Uttar Pradesh</h3>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <?php
                        $up_cities = ['Lucknow','Kanpur','Noida','Ghaziabad','Meerut','Agra','Varanasi','Prayagraj','Bareilly','Aligarh','Moradabad','Saharanpur','Gorakhpur','Firozabad','Jhansi','Muzaffarnagar','Mathura','Rampur','Shahjahanpur','Ayodhya','Etawah','Hapur','Mau','Farrukhabad','Lakhimpur'];
                        foreach($up_cities as $city): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-background-light border border-slate-200 rounded-full text-sm font-medium text-slate-700 hover:-translate-y-1 hover:border-primary hover:text-primary transition-all cursor-default">
                            <span class="material-symbols-outlined text-sm">location_on</span> <?= $city ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- MP Card -->
                <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-primary p-2 bg-primary/10 rounded-lg">map</span>
                        <h3 class="text-2xl font-bold">Madhya Pradesh</h3>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <?php
                        $mp_cities = ['Bhopal','Indore','Gwalior','Jabalpur','Ujjain','Guna','Chhindwara','Bhind','Morena','Khandwa','Burhanpur','Singrauli','Katni','Rewa','Ratlam','Satna','Dewas','Sagar'];
                        foreach($mp_cities as $city): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-background-light border border-slate-200 rounded-full text-sm font-medium text-slate-700 hover:-translate-y-1 hover:border-primary hover:text-primary transition-all cursor-default">
                            <span class="material-symbols-outlined text-sm">location_on</span> <?= $city ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col items-center text-center">
                <span class="text-4xl font-black text-primary mb-2">2</span>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500">States Covered</span>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col items-center text-center">
                <span class="text-4xl font-black text-primary mb-2">40+</span>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Cities Reached</span>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col items-center text-center">
                <span class="text-4xl font-black text-primary mb-2">500+</span>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Students Trained</span>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-primary/10 shadow-sm flex flex-col items-center text-center">
                <span class="text-4xl font-black text-primary mb-2">100%</span>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Placement Support</span>
            </div>
        </div>
    </div>
</section>

<!-- ================================= -->
<!-- ✅ SEO: LOCAL SEO TEXT SECTION    -->
<!-- Google ke liye local content      -->
<!-- ================================= -->
<section class="py-16 bg-white border-t border-slate-100">
    <div class="max-w-5xl mx-auto px-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-6 text-center">
            Best IT Training Institute in Kanpur & Lucknow
        </h2>
        <div class="prose prose-slate max-w-none text-slate-600 text-sm leading-relaxed grid md:grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold text-slate-800 mb-3">IT Training in Kanpur</h3>
                <p>LogixCode Kanpur ka sabse trusted IT training institute hai jo Swarn Jayanti Vihar, Koyla Nagar mein located hai. Hum B.Tech, BCA, MCA aur Diploma students ke liye Python, Java, MERN Stack, Data Science, aur Cloud Computing (AWS) courses offer karte hain. Hamare 500+ placed students aur 100+ hiring partners ke saath, LogixCode Kanpur mein career shuru karne ka best platform hai.</p>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 mb-3">IT Training in Lucknow</h3>
                <p>Lucknow ke students ke liye bhi LogixCode ke saath training available hai. Full Stack Development, Android Development, AI & Machine Learning, aur Data Analytics — ye sab courses Lucknow ke engineering colleges aur universities ke students ke liye designed hain. 100% placement support aur industry-recognized certification ke saath apna tech career start karein.</p>
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