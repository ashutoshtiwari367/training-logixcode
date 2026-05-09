<!DOCTYPE html>

<html class="scroll-smooth" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>About Us | LogixCode Institute</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
    </style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-container selection:text-white">
    
    <?php include "includes/navbar.php" ?>
    
    
    <!-- Hero Section -->
<header class="relative pt-40 pb-32 overflow-hidden bg-grid-pattern">
<div class="max-w-7xl mx-auto px-8 text-center relative z-10">
<h1 class="text-[3.5rem] md:text-[5rem] font-extrabold font-headline leading-tight tracking-[-0.03em] mb-6">
                About <span class="text-[#03bfd3]">LogixCode</span>
</h1>
<p class="text-xl md:text-2xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Transforming Tech Learners into Industry Leaders through architectural excellence and hands-on precision.
            </p>
</div>
<div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary/5 rounded-full blur-3xl -z-10"></div>
</header>
<!-- Who We Are -->
<section class="py-24 bg-surface">
<div class="max-w-7xl mx-auto px-8">
<div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
<div class="relative group">
<div class="absolute -inset-4 bg-primary/10 rounded-xl blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
<img alt="Tech workspace" class="rounded-xl relative z-10 editorial-shadow object-cover aspect-[4/3] w-full" data-alt="Modern collaborative tech workspace with diverse developers working on monitors in a bright architectural office environment" src="https://res.cloudinary.com/de7mh41io/image/upload/v1769591122/Untitled_design_10_jbnn0e.webp"/>
</div>
<div class="space-y-8">
<div class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary-container/30 text-[#03bfd3] font-label text-sm font-semibold tracking-wide">
                        ESTABLISHED 2024
                    </div>
<h2 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface leading-tight">
                        Who We Are: <span class="block text-[#03bfd3]">Creating Industry-Ready Professionals</span>
</h2>
<p class="text-lg text-on-surface-variant leading-relaxed font-body">
                        LogixCode isn't just a training institute; it's a premium laboratory where the next generation of engineers is forged. We bridge the gap between academic theory and high-stakes industry practice by immersing our students in real-world codebases. 
                    </p>
<p class="text-lg text-on-surface-variant leading-relaxed font-body">
                        Our curriculum is designed by active tech leads from Silicon Valley, focusing on hands-on learning, architectural patterns, and the soft skills required to thrive in modern agile teams.
                    </p>
</div>
</div>
</div>
</section>
<!-- Core Values -->
<section class="py-24 bg-surface-container-low">
<div class="max-w-7xl mx-auto px-8">
<h2 class="text-3xl font-extrabold font-headline text-center mb-16 tracking-tight">Core Values</h2>
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
<!-- Value Card -->
<div class="bg-surface-container-lowest p-8 rounded-lg editorial-shadow ghost-border transition-all duration-300 hover:-translate-y-2 group">
<div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center mb-6 group-hover:bg-[#03bfd3] group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white" data-icon="shield">shield</span>
</div>
<h3 class="text-lg font-semibold font-headline mb-3">Trust</h3>
<p class="text-sm text-on-surface-variant leading-relaxed">Built on a foundation of reliability and proven outcomes.</p>
</div>
<!-- Value Card -->
<div class="bg-surface-container-lowest p-8 rounded-lg editorial-shadow ghost-border transition-all duration-300 hover:-translate-y-2 group">
<div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center mb-6 group-hover:bg-[#03bfd3] group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white" data-icon="verified">verified</span>
</div>
<h3 class="text-lg font-semibold font-headline mb-3">Quality</h3>
<p class="text-sm text-on-surface-variant leading-relaxed">Uncompromising standards in every line of code taught.</p>
</div>
<!-- Value Card -->
<div class="bg-surface-container-lowest p-8 rounded-lg editorial-shadow ghost-border transition-all duration-300 hover:-translate-y-2 group">
<div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center mb-6 group-hover:bg-[#03bfd3] group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white" data-icon="lightbulb">lightbulb</span>
</div>
<h3 class="text-lg font-semibold font-headline mb-3">Innovation</h3>
<p class="text-sm text-on-surface-variant leading-relaxed">Staying ahead of the tech curve with future-proof tech.</p>
</div>
<!-- Value Card -->
<div class="bg-surface-container-lowest p-8 rounded-lg editorial-shadow ghost-border transition-all duration-300 hover:-translate-y-2 group">
<div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center mb-6 group-hover:bg-[#03bfd3] group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white" data-icon="gavel">gavel</span>
</div>
<h3 class="text-lg font-semibold font-headline mb-3">Integrity</h3>
<p class="text-sm text-on-surface-variant leading-relaxed">Honest mentoring and realistic career guidance.</p>
</div>
<!-- Value Card -->
<div class="bg-surface-container-lowest p-8 rounded-lg editorial-shadow ghost-border transition-all duration-300 hover:-translate-y-2 group">
<div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center mb-6 group-hover:bg-[#03bfd3] group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white" data-icon="visibility">visibility</span>
</div>
<h3 class="text-lg font-semibold font-headline mb-3">Transparency</h3>
<p class="text-sm text-on-surface-variant leading-relaxed">Open communication and clear placement metrics.</p>
</div>
</div>
</div>
</section>
<!-- Vision & Mission -->
<section class="py-24 bg-surface">
<div class="max-w-7xl mx-auto px-8">
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="p-12 rounded-xl bg-surface-container-lowest border-l-8 border-[#03bfd3] editorial-shadow">
<span class="material-symbols-outlined text-[#03bfd3] mb-6 text-4xl" data-icon="visibility_lock" data-weight="fill" style="font-variation-settings: 'FILL' 1;">visibility_lock</span>
<h2 class="text-3xl font-extrabold font-headline mb-6 tracking-tight">Our Vision</h2>
<p class="text-lg text-on-surface-variant leading-relaxed">
                        To become the global architectural core of technical excellence, where engineering talent is refined through the perfect blend of theory and high-impact practical execution.
                    </p>
</div>
<div class="p-12 rounded-xl bg-surface-container-lowest border-l-8 border-tertiary-container editorial-shadow">
<span class="material-symbols-outlined text-tertiary mb-6 text-4xl" data-icon="rocket_launch" data-weight="fill" style="font-variation-settings: 'FILL' 1;">rocket_launch</span>
<h2 class="text-3xl font-extrabold font-headline mb-6 tracking-tight">Our Mission</h2>
<p class="text-lg text-on-surface-variant leading-relaxed">
                        To empower every learner with the tools, mentorship, and real-world exposure necessary to transition from a student to a high-value industry leader in months, not years.
                    </p>
</div>
</div>
</div>
</section>
<!-- Stats Strip -->
<section class="py-20 bg-[#03bfd3]">
<div class="max-w-7xl mx-auto px-8">
<div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center text-white">
<div class="space-y-2">
<div class="text-5xl font-extrabold font-headline tracking-tighter">500+</div>
<div class="text-sm font-label uppercase tracking-widest opacity-80">Students</div>
</div>
<div class="space-y-2">
<div class="text-5xl font-extrabold font-headline tracking-tighter">100+</div>
<div class="text-sm font-label uppercase tracking-widest opacity-80">Hiring Partners</div>
</div>
<div class="space-y-2">
<div class="text-5xl font-extrabold font-headline tracking-tighter">50+</div>
<div class="text-sm font-label uppercase tracking-widest opacity-80">Trainers</div>
</div>
<div class="space-y-2">
<div class="text-5xl font-extrabold font-headline tracking-tighter">95%</div>
<div class="text-sm font-label uppercase tracking-widest opacity-80">Placement Rate</div>
</div>
</div>
</div>
</section>
<!-- What We Offer -->
<section class="py-24 bg-surface-container-low">
<div class="max-w-7xl mx-auto px-8">
<h2 class="text-3xl font-extrabold font-headline text-center mb-16 tracking-tight">What We Offer</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<!-- Offer Card -->
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="code">code</span>
<h3 class="text-xl font-bold font-headline mb-4">Web Development</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Full-stack mastery using React, Node.js, and modern cloud architectures.</p>
</div>
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="terminal">terminal</span>
<h3 class="text-xl font-bold font-headline mb-4">Software Development</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Advanced engineering principles, algorithms, and system design patterns.</p>
</div>
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="smartphone">smartphone</span>
<h3 class="text-xl font-bold font-headline mb-4">App Development</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Native and cross-platform mobile solutions using Flutter and React Native.</p>
</div>
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="data_object">data_object</span>
<h3 class="text-xl font-bold font-headline mb-4">Programming Tech</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Deep dives into Java, Python, and C++ for robust software engineering.</p>
</div>
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="work_history">work_history</span>
<h3 class="text-xl font-bold font-headline mb-4">Internship Programs</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Work on live production-grade projects within our partner network.</p>
</div>
<div class="p-8 rounded-xl bg-surface-container-lowest ghost-border hover:shadow-2xl transition-all duration-500">
<span class="material-symbols-outlined text-[#03bfd3] text-4xl mb-6" data-icon="handshake">handshake</span>
<h3 class="text-xl font-bold font-headline mb-4">Placement Assistance</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">Dedicated HR support, interview prep, and direct hiring pipelines.</p>
</div>
</div>
</div>
</section>
<!-- Why Choose Us -->
<section class="py-24 bg-surface">
<div class="max-w-7xl mx-auto px-8">
<h2 class="text-3xl font-extrabold font-headline text-center mb-16 tracking-tight">Why Choose Us</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Feature -->
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="school">school</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">Experienced Mentors</h4>
<p class="text-sm text-on-surface-variant">Learn from experts with 10+ years in the industry.</p>
</div>
</div>
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="precision_manufacturing">precision_manufacturing</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">Hands-on Learning</h4>
<p class="text-sm text-on-surface-variant">Build real applications, not just toy examples.</p>
</div>
</div>
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="history_edu">history_edu</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">Updated Curriculum</h4>
<p class="text-sm text-on-surface-variant">Syllabus updated quarterly to match market demands.</p>
</div>
</div>
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="science">science</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">100% Practical</h4>
<p class="text-sm text-on-surface-variant">Coding from day one. No dry lectures.</p>
</div>
</div>
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="badge">badge</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">Certification</h4>
<p class="text-sm text-on-surface-variant">Industry-recognized certification upon completion.</p>
</div>
</div>
<div class="flex items-start space-x-6">
<div class="shrink-0 w-12 h-12 rounded-lg bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[#03bfd3]" data-icon="support_agent">support_agent</span>
</div>
<div>
<h4 class="font-bold font-headline mb-2">Lifetime Support</h4>
<p class="text-sm text-on-surface-variant">Access to our alumni network and career portal forever.</p>
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
<h2 class="text-4xl font-extrabold font-headline mb-6 tracking-tight">Ready to Start Your Journey?</h2>
<p class="text-xl text-on-surface-variant mb-12 max-w-2xl mx-auto">Join the next cohort of technical experts and transform your career path with LogixCode.</p>
<div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
    <a href="/registration">
<button class="w-full sm:w-auto px-10 py-4 rounded-full bg-[#03bfd3] text-white font-bold text-lg hover:scale-105 active:scale-95 transition-transform shadow-xl shadow-primary/30">Apply For Admission</button>
</a>
<a href="/courses">
<button class="w-full sm:w-auto px-10 py-4 rounded-full border-2 border-outline-variant text-[#03bfd3] font-bold text-lg hover:bg-surface-container-low transition-colors">Explore Courses</button>
</a>
</div>
</div>
</div>
</div>
</section>


<?php include "includes/footer.php" ?>
</body>
</html>