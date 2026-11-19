<!DOCTYPE html>
<html class="scroll-smooth" lang="en-IN">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>IT Courses in Kanpur with 100% Placement | LogixCode</title>
<meta name="description" content="Explore professional IT courses in Kanpur at LogixCode. Master Python, Java, MERN Full Stack, Data Science & Android with live projects. Apply today!"/>
<link rel="canonical" href="https://training.logixcode.com/courses/"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="IT Courses in Kanpur with 100% Placement | LogixCode"/>
<meta property="og:description" content="Explore job-oriented software training courses in Kanpur. Python, Java, MERN Stack, Data Science, and Mobile App Development."/>
<meta property="og:url" content="https://training.logixcode.com/courses/"/>
<meta property="og:image" content="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"/>
<meta name="twitter:card" content="summary_large_image"/>

<!-- ItemList Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "LogixCode Training Programs in Kanpur",
  "description": "Professional job-oriented IT training courses offered in Kanpur.",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "url": "https://training.logixcode.com/courses/python-training-kanpur/",
      "name": "Python Course in Kanpur with Placement"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "url": "https://training.logixcode.com/courses/full-stack-web-development-kanpur/",
      "name": "Full Stack Web Development Course in Kanpur"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "url": "https://training.logixcode.com/courses/java-programming-kanpur/",
      "name": "Java Training in Kanpur with Spring Boot"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "url": "https://training.logixcode.com/courses/data-science-machine-learning-kanpur/",
      "name": "Data Science Course in Kanpur with AI & ML"
    },
    {
      "@type": "ListItem",
      "position": 5,
      "url": "https://training.logixcode.com/courses/android-app-development-kanpur/",
      "name": "Android App Development Course in Kanpur"
    }
  ]
}
</script>

<link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<?php include_once __DIR__ . '/includes/theme.php'; ?>

<style>
    .tab-btn {
        min-height: 44px;
        background-color: var(--surface);
        color: var(--ink);
        border: 1px solid var(--border);
        transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
    }
    .tab-btn:hover {
        background-color: var(--tint);
        border-color: var(--teal);
    }
    .tab-btn.active {
        background-color: var(--teal);
        color: var(--ink);
        border-color: var(--teal);
        font-weight: 600;
    }
    .course-item.hidden-course {
        opacity: 0;
        pointer-events: none;
        position: absolute;
    }
</style>
</head>
<body class="bg-[var(--bg)] text-[var(--ink)] font-body">

<?php include "includes/navbar.php" ?>

<!-- Hero Section (Component 2: --tint bg, </> motif, coral primary + outline secondary) -->
<header class="bg-[var(--tint)] border-b border-[var(--border)] py-12 md:py-20">
    <div class="lc-container text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--surface)] border border-[var(--border)] text-[var(--teal-text)] text-sm font-medium mb-5">
            <span>40+ job-oriented courses available</span>
            <span class="font-mono font-bold text-[var(--teal)]" aria-hidden="true">&lt;/&gt;</span>
        </div>
        <h1 class="text-[var(--ink)] font-headline mb-4">
            Explore our <span class="text-[var(--teal-text)]">courses</span>
        </h1>
        <p class="text-base md:text-lg text-[var(--muted)] max-w-2xl mx-auto mb-8">
            From computer science fundamentals to full-stack web development, networking, and embedded systems — find the right course to launch your career.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?= BASE_URL ?>registration" class="btn-primary">Enroll now</a>
            <a href="<?= BASE_URL ?>contact" class="btn-secondary">Book free demo</a>
        </div>
    </div>
</header>

<!-- Highlight Strip Under Hero (Component 3: --teal bg, dark ink text) -->
<div class="bg-[var(--teal)] text-[var(--ink)] border-b border-[var(--border)]">
    <div class="lc-container py-3 flex flex-wrap items-center justify-between gap-4 text-sm font-medium">
        <span class="font-semibold text-[var(--ink)]">New batch starts Monday at our Kanpur campus &amp; online</span>
        <span class="text-[var(--ink)]">Fees starting from ₹5,000 · 100% placement support · Free 1-day demo</span>
    </div>
</div>

<!-- Filter Tabs -->
<section class="sticky top-[69px] z-30 bg-[var(--surface)] border-b border-[var(--border)] py-3">
    <div class="lc-container">
        <div class="flex flex-wrap gap-2 justify-center">
            <button onclick="filterCourses('all')" class="tab-btn active px-5 py-2 rounded-full text-sm font-medium" data-filter="all">
                All courses
            </button>
            <button onclick="filterCourses('cs')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="cs">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">computer</span> CS fundamentals
            </button>
            <button onclick="filterCourses('ec')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="ec">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">memory</span> Electronics (EC)
            </button>
            <button onclick="filterCourses('web')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="web">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">code</span> Web development
            </button>
            <button onclick="filterCourses('app')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="app">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">smartphone</span> App development
            </button>
            <button onclick="filterCourses('lang')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="lang">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">data_object</span> Programming
            </button>
            <button onclick="filterCourses('other')" class="tab-btn px-5 py-2 rounded-full text-sm font-medium inline-flex items-center gap-1.5" data-filter="other">
                <span class="material-symbols-outlined text-base text-[var(--teal-text)]">category</span> AI, cloud &amp; security
            </button>
        </div>
    </div>
</section>

<!-- CS Courses Section -->
<section class="lc-section bg-[var(--bg)] border-b border-[var(--border)]" id="section-cs">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">computer</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">Computer science</h2>
                <p class="text-sm text-[var(--muted)]">Core CS fundamentals &amp; interview preparation</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="courses-grid">

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="cs">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">hub</span>
                    </div>
                    <span class="lc-chip text-xs">CS stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Data structures &amp; algorithms</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Master arrays, trees, graphs, sorting, and problem-solving techniques for technical interviews.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="cs">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">database</span>
                    </div>
                    <span class="lc-chip text-xs">CS stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Database management (DBMS)</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">SQL, NoSQL, normalization, indexing, and real-world relational database design patterns.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 2 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="cs">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">security</span>
                    </div>
                    <span class="lc-chip text-xs">CS stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Operating systems &amp; networks</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Process management, memory, scheduling, TCP/IP, routing, and network security fundamentals.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Advanced</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- EC Courses Section -->
<section class="lc-section bg-[var(--tint)] border-b border-[var(--border)]" id="section-ec">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">memory</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">Electronics &amp; communication</h2>
                <p class="text-sm text-[var(--muted)]">EC fundamentals, embedded systems &amp; IoT</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="ec">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">developer_board</span>
                    </div>
                    <span class="lc-chip text-xs">EC stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Embedded systems &amp; Arduino</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Hands-on microcontroller programming, sensor interfacing, and real-time hardware systems.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="ec">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">sensors</span>
                    </div>
                    <span class="lc-chip text-xs">EC stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">IoT — Internet of Things</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">ESP32, Raspberry Pi, MQTT, cloud connectivity, and smart device prototyping.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="ec">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">electric_bolt</span>
                    </div>
                    <span class="lc-chip text-xs">EC stream</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Digital electronics &amp; VLSI</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Logic gates, flip-flops, FPGAs, HDL coding, and VLSI chip design principles.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Advanced</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Web Dev Section -->
<section class="lc-section bg-[var(--bg)] border-b border-[var(--border)]" id="section-web">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">code</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">Web development</h2>
                <p class="text-sm text-[var(--muted)]">Frontend, backend &amp; full-stack mastery</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="web">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">web</span>
                    </div>
                    <span class="lc-chip text-xs">Web development</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">HTML, CSS &amp; JavaScript</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Build responsive websites from scratch using modern HTML5, CSS3, and vanilla JavaScript.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 2 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/full-stack-web-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="web">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">integration_instructions</span>
                    </div>
                    <span class="lc-chip text-xs">Web development</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">React.js &amp; Node.js (MERN)</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Full-stack development with React for frontend and Node/Express with MongoDB for REST APIs.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4–6 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/full-stack-web-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="web">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">dns</span>
                    </div>
                    <span class="lc-chip text-xs">Web development</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">PHP &amp; Laravel</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Server-side web engineering with PHP, MySQL, MVC architecture, and Laravel framework.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/full-stack-web-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- App Dev Section -->
<section class="lc-section bg-[var(--tint)] border-b border-[var(--border)]" id="section-app">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">smartphone</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">App development</h2>
                <p class="text-sm text-[var(--muted)]">Android, iOS &amp; cross-platform mobile solutions</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="app">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">flutter_dash</span>
                    </div>
                    <span class="lc-chip text-xs">Mobile app</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Flutter &amp; Dart</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Cross-platform mobile apps for Android &amp; iOS using Flutter with Firebase integration.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/android-app-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="app">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">android</span>
                    </div>
                    <span class="lc-chip text-xs">Mobile app</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Android development (Kotlin)</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Native Android apps with Kotlin, Jetpack Compose, and modern Android architecture.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4–6 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/android-app-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="app">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">phone_iphone</span>
                    </div>
                    <span class="lc-chip text-xs">Mobile app</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">React Native</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Build performant cross-platform mobile apps using React Native and Expo framework.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/android-app-development-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Programming Languages Section -->
<section class="lc-section bg-[var(--bg)] border-b border-[var(--border)]" id="section-lang">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">data_object</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">Programming languages</h2>
                <p class="text-sm text-[var(--muted)]">Core languages for software engineering</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="lang">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">coffee</span>
                    </div>
                    <span class="lc-chip text-xs">Programming</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Java programming</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">OOP concepts, collections, multithreading, Spring Boot, and enterprise Java development.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4–6 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner to pro</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/java-programming-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="lang">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">terminal</span>
                    </div>
                    <span class="lc-chip text-xs">Programming</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Python programming</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Python for backend web development (Django), automation, scripting, and data analysis.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4–6 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/python-training-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col" data-category="lang">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">memory_alt</span>
                    </div>
                    <span class="lc-chip text-xs">Programming</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">C &amp; C++ programming</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Pointers, memory management, STL, object-oriented programming, and competitive coding.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 2 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Beginner</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Other Courses Section -->
<section class="lc-section bg-[var(--tint)] border-b border-[var(--border)]" id="section-other">
    <div class="lc-container">
        <div class="flex items-center gap-3.5 mb-8">
            <div class="lc-icon-circle">
                <span class="material-symbols-outlined text-[var(--ink)]">category</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-headline text-[var(--ink)]">Data science, cloud &amp; security</h2>
                <p class="text-sm text-[var(--muted)]">AI/ML, cybersecurity, cloud computing &amp; more</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="other">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">insights</span>
                    </div>
                    <span class="lc-chip text-xs">Data science</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Data science &amp; machine learning</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Supervised learning, Pandas, NumPy, Scikit-Learn, Power BI, and deploying ML models.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4–6 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Advanced</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>courses/data-science-machine-learning-kanpur/" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="other">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">cloud</span>
                    </div>
                    <span class="lc-chip text-xs">Cloud</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Cloud computing (AWS)</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">AWS core services, EC2, S3, Lambda, DevOps pipelines, and cloud architecture.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 3 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Intermediate</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

            <div class="course-card course-item lc-card p-6 flex flex-col bg-[var(--surface)]" data-category="other">
                <div class="flex items-start justify-between mb-4">
                    <div class="lc-icon-circle">
                        <span class="material-symbols-outlined text-[var(--ink)]">lock</span>
                    </div>
                    <span class="lc-chip text-xs">Security</span>
                </div>
                <h3 class="text-lg font-semibold font-headline mb-2 text-[var(--ink)]">Cyber security</h3>
                <p class="text-sm text-[var(--muted)] leading-relaxed mb-4 flex-1">Ethical hacking, penetration testing, network security, and security auditing.</p>
                <div class="flex items-center gap-4 text-xs text-[var(--muted)] mb-4">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">schedule</span> 4 months</span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm text-[var(--teal-text)]">signal_cellular_alt</span> Advanced</span>
                </div>
                <div class="pt-4 border-t border-[var(--border)] flex items-center justify-between">
                    <div>
                        <span class="text-xs text-[var(--muted)] block">Course fee</span>
                        <span class="text-base font-bold text-[var(--ink)]">From ₹5,000</span>
                    </div>
                    <a href="<?= BASE_URL ?>registration" class="text-sm font-semibold text-[var(--teal-text)] hover:underline inline-flex items-center gap-1 min-h-[44px]">View syllabus →</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="lc-section bg-[var(--bg)]">
    <div class="lc-container">
        <div class="lc-card p-8 md:p-12 text-center bg-[var(--surface)] max-w-4xl mx-auto">
            <h2 class="text-[var(--ink)] mb-3">Can't decide which course is right for you?</h2>
            <p class="text-base text-[var(--muted)] mb-8 max-w-2xl mx-auto">Talk to our counselors for free — we'll match you to the right batch and syllabus based on your career goals.</p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="<?= BASE_URL ?>registration" class="btn-primary w-full sm:w-auto">
                    Enroll now
                </a>
                <a href="<?= BASE_URL ?>contact" class="btn-secondary w-full sm:w-auto">
                    Book free demo class
                </a>
            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php" ?>

<script>
function filterCourses(category) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === category) {
            btn.classList.add('active');
        }
    });

    const sections = ['cs','ec','web','app','lang','other'];

    if (category === 'all') {
        sections.forEach(s => {
            const sec = document.getElementById('section-' + s);
            if (sec) sec.style.display = '';
        });
    } else {
        sections.forEach(s => {
            const sec = document.getElementById('section-' + s);
            if (!sec) return;
            sec.style.display = (s === category) ? '' : 'none';
        });
    }

    // Smooth scroll to first visible section
    const target = category === 'all'
        ? document.getElementById('section-cs')
        : document.getElementById('section-' + category);
    if (target) {
        setTimeout(() => {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 50);
    }
}
</script>

</body>
</html>
