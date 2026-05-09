<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Courses | LogixCode Institute</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "inverse-on-surface": "#eff1f3",
            "surface-container": "#eceef0",
            "on-secondary": "#ffffff",
            "tertiary-fixed-dim": "#ffb689",
            "error": "#ba1a1a",
            "primary-container": "#03bfd3",
            "error-container": "#ffdad6",
            "surface-dim": "#d8dadc",
            "secondary-container": "#b9ecef",
            "inverse-primary": "#03bfd3",
            "on-tertiary-fixed": "#311300",
            "inverse-surface": "#2d3133",
            "secondary-fixed": "#b9ecef",
            "on-tertiary": "#ffffff",
            "on-secondary-fixed": "#002022",
            "surface-container-low": "#f2f4f6",
            "on-primary-fixed-variant": "#004f54",
            "on-surface": "#191c1e",
            "tertiary-fixed": "#ffdbc8",
            "secondary-fixed-dim": "#9ecfd3",
            "primary-fixed-dim": "#03bfd3",
            "on-secondary-container": "#3b6c70",
            "on-error": "#ffffff",
            "surface-container-high": "#e6e8ea",
            "on-primary-fixed": "#002022",
            "on-primary": "#ffffff",
            "surface-container-lowest": "#ffffff",
            "on-surface-variant": "#3c494a",
            "surface-variant": "#e0e3e5",
            "on-primary-container": "#004c50",
            "secondary": "#356669",
            "background": "#f7f9fb",
            "surface-container-highest": "#e0e3e5",
            "on-tertiary-fixed-variant": "#733500",
            "on-error-container": "#93000a",
            "surface": "#f7f9fb",
            "tertiary": "#974801",
            "primary-fixed": "#71f5ff",
            "on-tertiary-container": "#6f3300",
            "outline": "#6c7a7b",
            "surface-bright": "#f7f9fb",
            "on-background": "#191c1e",
            "primary": "#03bfd3",
            "surface-tint": "#03bfd3",
            "tertiary-container": "#fe9852",
            "on-secondary-fixed-variant": "#1a4e51",
            "outline-variant": "#bbc9ca"
          },
          fontFamily: {
            "headline": ["Manrope"],
            "body": ["Inter"],
            "label": ["Inter"]
          },
          borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
        },
      },
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .bg-grid-pattern {
        background-image: radial-gradient(circle, #03bfd310 1px, transparent 1px);
        background-size: 32px 32px;
    }
    .editorial-shadow {
        box-shadow: 0 40px 60px -20px rgba(3, 191, 211, 0.06);
    }
    .ghost-border {
        border: 1px solid rgba(187, 201, 202, 0.2);
    }
    .course-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 48px -12px rgba(3, 191, 211, 0.18);
    }
    .tab-btn.active {
        background-color: #03bfd3;
        color: #ffffff;
    }
    .tab-btn {
        transition: all 0.25s ease;
    }
    .course-item {
        transition: opacity 0.35s ease, transform 0.35s ease;
    }
    .course-item.hidden-course {
        opacity: 0;
        pointer-events: none;
        position: absolute;
    }
    .badge-cs { background: #e0f7fa; color: #006064; }
    .badge-ec { background: #fce4ec; color: #880e4f; }
    .badge-web { background: #e8f5e9; color: #1b5e20; }
    .badge-app { background: #fff3e0; color: #e65100; }
    .badge-lang { background: #ede7f6; color: #4527a0; }
    .badge-other { background: #f3e5f5; color: #6a1b9a; }
</style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-container selection:text-white">

<?php include "includes/navbar.php" ?>

<!-- Hero Section -->
<header class="relative pt-40 pb-32 overflow-hidden bg-grid-pattern">
    <div class="max-w-7xl mx-auto px-8 text-center relative z-10">
        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary-container/30 text-[#03bfd3] font-label text-sm font-semibold tracking-wide mb-6">
            100+ COURSES AVAILABLE
        </div>
        <h1 class="text-[3.5rem] md:text-[5rem] font-extrabold font-headline leading-tight tracking-[-0.03em] mb-6">
            Explore Our <span class="text-[#03bfd3]">Courses</span>
        </h1>
        <p class="text-xl md:text-2xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            From CS fundamentals to EC electronics — find the perfect course to launch your career.
        </p>
    </div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary/5 rounded-full blur-3xl -z-10"></div>
</header>

<!-- Filter Tabs -->
<section class="sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 py-4">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-wrap gap-3 justify-center">
            <button onclick="filterCourses('all')" class="tab-btn active px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="all">
                All Courses
            </button>
            <button onclick="filterCourses('cs')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="cs">
                <span class="material-symbols-outlined text-base align-middle mr-1">computer</span> CS
            </button>
            <button onclick="filterCourses('ec')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="ec">
                <span class="material-symbols-outlined text-base align-middle mr-1">memory</span> EC
            </button>
            <button onclick="filterCourses('web')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="web">
                <span class="material-symbols-outlined text-base align-middle mr-1">code</span> Web Dev
            </button>
            <button onclick="filterCourses('app')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="app">
                <span class="material-symbols-outlined text-base align-middle mr-1">smartphone</span> App Dev
            </button>
            <button onclick="filterCourses('lang')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="lang">
                <span class="material-symbols-outlined text-base align-middle mr-1">data_object</span> Programming
            </button>
            <button onclick="filterCourses('other')" class="tab-btn px-6 py-2.5 rounded-full text-sm font-semibold font-label border border-outline-variant hover:bg-surface-container" data-filter="other">
                <span class="material-symbols-outlined text-base align-middle mr-1">category</span> Other
            </button>
        </div>
    </div>
</section>

<!-- CS Courses Section -->
<section class="py-20 bg-surface" id="section-cs">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#e0f7fa] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#006064]" style="font-variation-settings:'FILL' 1">computer</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">Computer Science</h2>
                <p class="text-sm text-on-surface-variant">Core CS fundamentals & advanced topics</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">CS STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="courses-grid">

            <!-- CS Card -->
            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="cs">
                <div class="h-2 bg-[#03bfd3]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e0f7fa] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#006064]" style="font-variation-settings:'FILL' 1">hub</span>
                        </div>
                        <span class="badge-cs text-xs font-semibold px-3 py-1 rounded-full">CS</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Data Structures & Algorithms</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Master arrays, trees, graphs, sorting, and problem-solving techniques for interviews.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#03bfd3] text-[#03bfd3] text-sm font-bold hover:bg-[#03bfd3] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="cs">
                <div class="h-2 bg-[#03bfd3]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e0f7fa] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#006064]" style="font-variation-settings:'FILL' 1">database</span>
                        </div>
                        <span class="badge-cs text-xs font-semibold px-3 py-1 rounded-full">CS</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Database Management (DBMS)</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">SQL, NoSQL, normalization, indexing and real-world database design patterns.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 2 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#03bfd3] text-[#03bfd3] text-sm font-bold hover:bg-[#03bfd3] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="cs">
                <div class="h-2 bg-[#03bfd3]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e0f7fa] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#006064]" style="font-variation-settings:'FILL' 1">security</span>
                        </div>
                        <span class="badge-cs text-xs font-semibold px-3 py-1 rounded-full">CS</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Operating Systems & Networks</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Process management, memory, scheduling, TCP/IP, and network security fundamentals.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Advanced</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#03bfd3] text-[#03bfd3] text-sm font-bold hover:bg-[#03bfd3] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- EC Courses Section -->
<section class="py-20 bg-surface-container-low" id="section-ec">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#fce4ec] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#880e4f]" style="font-variation-settings:'FILL' 1">memory</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">Electronics & Communication</h2>
                <p class="text-sm text-on-surface-variant">EC fundamentals, embedded systems & IoT</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">EC STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="ec">
                <div class="h-2 bg-[#ec407a]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fce4ec] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#880e4f]" style="font-variation-settings:'FILL' 1">developer_board</span>
                        </div>
                        <span class="badge-ec text-xs font-semibold px-3 py-1 rounded-full">EC</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Embedded Systems & Arduino</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Hands-on microcontroller programming, sensor interfacing and real-time systems.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ec407a] text-[#ec407a] text-sm font-bold hover:bg-[#ec407a] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="ec">
                <div class="h-2 bg-[#ec407a]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fce4ec] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#880e4f]" style="font-variation-settings:'FILL' 1">sensors</span>
                        </div>
                        <span class="badge-ec text-xs font-semibold px-3 py-1 rounded-full">EC</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">IoT — Internet of Things</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">ESP32, Raspberry Pi, MQTT, cloud connectivity and smart device prototyping.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 4 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ec407a] text-[#ec407a] text-sm font-bold hover:bg-[#ec407a] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="ec">
                <div class="h-2 bg-[#ec407a]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fce4ec] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#880e4f]" style="font-variation-settings:'FILL' 1">electric_bolt</span>
                        </div>
                        <span class="badge-ec text-xs font-semibold px-3 py-1 rounded-full">EC</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Digital Electronics & VLSI</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Logic gates, flip-flops, FPGAs, HDL coding, and VLSI chip design principles.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Advanced</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ec407a] text-[#ec407a] text-sm font-bold hover:bg-[#ec407a] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Web Dev Section -->
<section class="py-20 bg-surface" id="section-web">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#e8f5e9] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#1b5e20]" style="font-variation-settings:'FILL' 1">code</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">Web Development</h2>
                <p class="text-sm text-on-surface-variant">Frontend, backend & full-stack mastery</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">WEB STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="web">
                <div class="h-2 bg-[#43a047]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e8f5e9] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1b5e20]" style="font-variation-settings:'FILL' 1">web</span>
                        </div>
                        <span class="badge-web text-xs font-semibold px-3 py-1 rounded-full">Web Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">HTML, CSS & JavaScript</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Build responsive websites from scratch using modern HTML5, CSS3, and vanilla JS.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 2 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#43a047] text-[#43a047] text-sm font-bold hover:bg-[#43a047] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="web">
                <div class="h-2 bg-[#43a047]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e8f5e9] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1b5e20]" style="font-variation-settings:'FILL' 1">integration_instructions</span>
                        </div>
                        <span class="badge-web text-xs font-semibold px-3 py-1 rounded-full">Web Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">React.js & Node.js</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Full-stack development with React for frontend and Node/Express for REST APIs.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 4 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#43a047] text-[#43a047] text-sm font-bold hover:bg-[#43a047] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="web">
                <div class="h-2 bg-[#43a047]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#e8f5e9] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#1b5e20]" style="font-variation-settings:'FILL' 1">dns</span>
                        </div>
                        <span class="badge-web text-xs font-semibold px-3 py-1 rounded-full">Web Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">PHP & Laravel</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Backend web development with PHP, MySQL, and the Laravel MVC framework.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#43a047] text-[#43a047] text-sm font-bold hover:bg-[#43a047] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- App Dev Section -->
<section class="py-20 bg-surface-container-low" id="section-app">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#fff3e0] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#e65100]" style="font-variation-settings:'FILL' 1">smartphone</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">App Development</h2>
                <p class="text-sm text-on-surface-variant">Android, iOS & cross-platform solutions</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">APP STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="app">
                <div class="h-2 bg-[#fb8c00]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fff3e0] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#e65100]" style="font-variation-settings:'FILL' 1">flutter_dash</span>
                        </div>
                        <span class="badge-app text-xs font-semibold px-3 py-1 rounded-full">App Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Flutter & Dart</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Cross-platform mobile apps for Android & iOS using Flutter with Firebase integration.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 4 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#fb8c00] text-[#fb8c00] text-sm font-bold hover:bg-[#fb8c00] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="app">
                <div class="h-2 bg-[#fb8c00]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fff3e0] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#e65100]" style="font-variation-settings:'FILL' 1">android</span>
                        </div>
                        <span class="badge-app text-xs font-semibold px-3 py-1 rounded-full">App Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Android Development (Kotlin)</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Native Android apps with Kotlin, Jetpack Compose and modern Android architecture.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#fb8c00] text-[#fb8c00] text-sm font-bold hover:bg-[#fb8c00] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="app">
                <div class="h-2 bg-[#fb8c00]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#fff3e0] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#e65100]" style="font-variation-settings:'FILL' 1">devices</span>
                        </div>
                        <span class="badge-app text-xs font-semibold px-3 py-1 rounded-full">App Dev</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">React Native</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Build performant cross-platform apps using React Native and Expo framework.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Advanced</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#fb8c00] text-[#fb8c00] text-sm font-bold hover:bg-[#fb8c00] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Programming Languages Section -->
<section class="py-20 bg-surface" id="section-lang">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#ede7f6] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#4527a0]" style="font-variation-settings:'FILL' 1">data_object</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">Programming Languages</h2>
                <p class="text-sm text-on-surface-variant">Core languages for software development</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">LANG STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="lang">
                <div class="h-2 bg-[#7e57c2]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#ede7f6] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#4527a0]" style="font-variation-settings:'FILL' 1">coffee</span>
                        </div>
                        <span class="badge-lang text-xs font-semibold px-3 py-1 rounded-full">Programming</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Java Programming</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">OOP concepts, collections, multithreading, Spring Boot and enterprise Java.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#7e57c2] text-[#7e57c2] text-sm font-bold hover:bg-[#7e57c2] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="lang">
                <div class="h-2 bg-[#7e57c2]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#ede7f6] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#4527a0]" style="font-variation-settings:'FILL' 1">terminal</span>
                        </div>
                        <span class="badge-lang text-xs font-semibold px-3 py-1 rounded-full">Programming</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Python Programming</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Python for automation, scripting, data analysis, and machine learning fundamentals.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 2 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Beginner</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#7e57c2] text-[#7e57c2] text-sm font-bold hover:bg-[#7e57c2] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="lang">
                <div class="h-2 bg-[#7e57c2]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#ede7f6] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#4527a0]" style="font-variation-settings:'FILL' 1">memory_alt</span>
                        </div>
                        <span class="badge-lang text-xs font-semibold px-3 py-1 rounded-full">Programming</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">C / C++ Programming</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Low-level programming, pointers, memory management, OOP with C++ and STL.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 2 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#7e57c2] text-[#7e57c2] text-sm font-bold hover:bg-[#7e57c2] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Other Courses Section -->
<section class="py-20 bg-surface-container-low" id="section-other">
    <div class="max-w-7xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-12">
            <div class="w-12 h-12 rounded-xl bg-[#f3e5f5] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#6a1b9a]" style="font-variation-settings:'FILL' 1">category</span>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold font-headline tracking-tight">Other Courses</h2>
                <p class="text-sm text-on-surface-variant">AI/ML, cybersecurity, cloud & more</p>
            </div>
            <div class="ml-auto hidden md:flex items-center gap-2">
                <div class="h-px w-32 bg-outline-variant/50"></div>
                <span class="text-xs font-label text-on-surface-variant">MISC STREAM</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="other">
                <div class="h-2 bg-[#ab47bc]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#f3e5f5] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#6a1b9a]" style="font-variation-settings:'FILL' 1">smart_toy</span>
                        </div>
                        <span class="badge-other text-xs font-semibold px-3 py-1 rounded-full">Other</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">AI & Machine Learning</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Supervised learning, neural networks, NLP, and deploying ML models to production.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 5 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Advanced</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ab47bc] text-[#ab47bc] text-sm font-bold hover:bg-[#ab47bc] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="other">
                <div class="h-2 bg-[#ab47bc]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#f3e5f5] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#6a1b9a]" style="font-variation-settings:'FILL' 1">cloud</span>
                        </div>
                        <span class="badge-other text-xs font-semibold px-3 py-1 rounded-full">Other</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Cloud Computing (AWS)</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">AWS core services, EC2, S3, Lambda, DevOps pipelines and cloud architecture.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 3 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Intermediate</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ab47bc] text-[#ab47bc] text-sm font-bold hover:bg-[#ab47bc] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

            <div class="course-card course-item bg-surface-container-lowest rounded-xl editorial-shadow ghost-border overflow-hidden" data-category="other">
                <div class="h-2 bg-[#ab47bc]"></div>
                <div class="p-7">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 rounded-lg bg-[#f3e5f5] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#6a1b9a]" style="font-variation-settings:'FILL' 1">lock</span>
                        </div>
                        <span class="badge-other text-xs font-semibold px-3 py-1 rounded-full">Other</span>
                    </div>
                    <h3 class="text-lg font-bold font-headline mb-2">Cyber Security</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed mb-5">Ethical hacking, penetration testing, network security, and CEH exam prep.</p>
                    <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-5">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> 4 Months</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">signal_cellular_alt</span> Advanced</span>
                    </div>
                    <a href="/registration" class="block w-full text-center py-2.5 rounded-full border-2 border-[#ab47bc] text-[#ab47bc] text-sm font-bold hover:bg-[#ab47bc] hover:text-white transition-colors">Enroll Now</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24">
    <div class="max-w-5xl mx-auto px-8">
        <div class="bg-surface-container-lowest p-16 rounded-xl text-center editorial-shadow relative overflow-hidden ghost-border">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
            <div class="relative z-10">
                <h2 class="text-4xl font-extrabold font-headline mb-6 tracking-tight">Can't decide which course?</h2>
                <p class="text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto">Talk to our counselors for free — we'll match you to the perfect course based on your goals.</p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="/registration">
                        <button class="w-full sm:w-auto px-10 py-4 rounded-full bg-[#03bfd3] text-white font-bold text-lg hover:scale-105 active:scale-95 transition-transform shadow-xl shadow-primary/30">Apply For Admission</button>
                    </a>
                    <a href="/about">
                        <button class="w-full sm:w-auto px-10 py-4 rounded-full border-2 border-outline-variant text-[#03bfd3] font-bold text-lg hover:bg-surface-container-low transition-colors">About LogixCode</button>
                    </a>
                </div>
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