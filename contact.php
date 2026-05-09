<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Contact Us | LogixCode Institute</title>
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

    /* Input focus styles */
    .lc-input {
        width: 100%;
        padding: 0.85rem 1.1rem;
        border-radius: 0.75rem;
        border: 1.5px solid #bbc9ca55;
        background: #f2f4f6;
        color: #191c1e;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .lc-input:focus {
        border-color: #03bfd3;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(3, 191, 211, 0.12);
    }
    .lc-input::placeholder {
        color: #6c7a7b;
    }

    /* Map embed rounded */
    .map-frame {
        border-radius: 1.25rem;
        overflow: hidden;
        border: 1.5px solid rgba(187, 201, 202, 0.2);
    }

    /* Success toast */
    #toast {
        transition: opacity 0.4s, transform 0.4s;
    }
</style>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-container selection:text-white">

    <?php include "includes/navbar.php" ?>

    <!-- Hero Section -->
    <header class="relative pt-40 pb-28 overflow-hidden bg-grid-pattern">
        <div class="max-w-7xl mx-auto px-8 text-center relative z-10">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary-container/30 text-[#03bfd3] font-label text-sm font-semibold tracking-wide mb-6">
                GET IN TOUCH
            </div>
            <h1 class="text-[3.5rem] md:text-[5rem] font-extrabold font-headline leading-tight tracking-[-0.03em] mb-6">
                Contact <span class="text-[#03bfd3]">LogixCode</span>
            </h1>
            <p class="text-xl md:text-2xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Have a question, want to enroll, or just want to say hello? We'd love to hear from you.
            </p>
        </div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-primary/5 rounded-full blur-3xl -z-10"></div>
    </header>

    <!-- Contact Info Cards + Form -->
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">

                <!-- Left: Info Cards -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Card: Phone -->
                    <div class="bg-surface-container-lowest p-7 rounded-xl editorial-shadow ghost-border flex items-start space-x-5 group hover:-translate-y-1 transition-transform duration-300">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center group-hover:bg-[#03bfd3] transition-colors duration-300">
                            <span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white transition-colors duration-300">call</span>
                        </div>
                        <div>
                            <h3 class="font-semibold font-headline text-lg mb-1">Call Us</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed">Mon – Sat, 9 AM – 7 PM</p>
                            <a href="tel:+919876543210" class="text-[#03bfd3] font-semibold text-sm mt-1 inline-block hover:underline">+918467898854</a>
                        </div>
                    </div>

                    <!-- Card: Email -->
                    <div class="bg-surface-container-lowest p-7 rounded-xl editorial-shadow ghost-border flex items-start space-x-5 group hover:-translate-y-1 transition-transform duration-300">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center group-hover:bg-[#03bfd3] transition-colors duration-300">
                            <span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white transition-colors duration-300">mail</span>
                        </div>
                        <div>
                            <h3 class="font-semibold font-headline text-lg mb-1">Email Us</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed">We reply within 24 hours</p>
                            <a href="mailto:info@logixcode.in" class="text-[#03bfd3] font-semibold text-sm mt-1 inline-block hover:underline">info@logixcode.com</a>
                        </div>
                    </div>

                    <!-- Card: Address -->
                    <div class="bg-surface-container-lowest p-7 rounded-xl editorial-shadow ghost-border flex items-start space-x-5 group hover:-translate-y-1 transition-transform duration-300">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center group-hover:bg-[#03bfd3] transition-colors duration-300">
                            <span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white transition-colors duration-300">location_on</span>
                        </div>
                        <div>
                            <h3 class="font-semibold font-headline text-lg mb-1">Visit Us</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed">Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar Kanpur, UP 208013</p>
                        </div>
                    </div>

                    <!-- Card: WhatsApp -->
                    <div class="bg-surface-container-lowest p-7 rounded-xl editorial-shadow ghost-border flex items-start space-x-5 group hover:-translate-y-1 transition-transform duration-300">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center group-hover:bg-[#03bfd3] transition-colors duration-300">
                            <span class="material-symbols-outlined text-[#03bfd3] group-hover:text-white transition-colors duration-300">chat</span>
                        </div>
                        <div>
                            <h3 class="font-semibold font-headline text-lg mb-1">WhatsApp</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed">Quick replies, course queries</p>
                            <a href="https://wa.me/8467898854" target="_blank" class="text-[#03bfd3] font-semibold text-sm mt-1 inline-block hover:underline">Chat on WhatsApp →</a>
                        </div>
                    </div>

                </div>

                <!-- Right: Contact Form -->
                <div class="lg:col-span-3">
                    <div class="bg-surface-container-lowest p-10 rounded-xl editorial-shadow ghost-border">
                        <h2 class="text-2xl font-extrabold font-headline mb-2 tracking-tight">Send Us a Message</h2>
                        <p class="text-on-surface-variant text-sm mb-8">Fill in the form below and our team will get back to you shortly.</p>

                        <form id="contactForm" class="space-y-5" onsubmit="handleSubmit(event)">

                            <!-- Name + Phone row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold font-label mb-2 text-on-surface">Full Name <span class="text-[#03bfd3]">*</span></label>
                                    <input type="text" name="name" required placeholder="e.g. Rahul Sharma" class="lc-input"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold font-label mb-2 text-on-surface">Phone Number <span class="text-[#03bfd3]">*</span></label>
                                    <input type="tel" name="phone" required placeholder="+91 XXXXX XXXXX" class="lc-input"/>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold font-label mb-2 text-on-surface">Email Address <span class="text-[#03bfd3]">*</span></label>
                                <input type="email" name="email" required placeholder="you@example.com" class="lc-input"/>
                            </div>

                            <!-- Course Interest -->
                            <div>
                                <label class="block text-sm font-semibold font-label mb-2 text-on-surface">Interested In</label>
                                <select name="course" class="lc-input">
                                    <option value="" disabled selected>Select a course / topic</option>
                                    <option value="web">Web Development</option>
                                    <option value="software">Software Development</option>
                                    <option value="app">App Development</option>
                                    <option value="programming">Programming Tech</option>
                                    <option value="internship">Internship Program</option>
                                    <option value="placement">Placement Assistance</option>
                                    <option value="other">Other / General Query</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-semibold font-label mb-2 text-on-surface">Your Message <span class="text-[#03bfd3]">*</span></label>
                                <textarea name="message" required rows="5" placeholder="Write your query here..." class="lc-input resize-none"></textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit" id="submitBtn"
                                class="w-full py-4 rounded-full bg-[#03bfd3] text-white font-bold text-base font-headline hover:scale-[1.02] active:scale-95 transition-transform shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                                <span id="btnText">Send Message</span>
                                <span class="material-symbols-outlined text-[20px]" id="btnIcon">send</span>
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-8">
            <h2 class="text-3xl font-extrabold font-headline text-center mb-4 tracking-tight">Find Us on the Map</h2>
            <p class="text-center text-on-surface-variant mb-12 text-sm">We're located in the heart of Noida's tech corridor — easy to reach by metro or road.</p>
            <div class="map-frame editorial-shadow">
                <!-- Replace the src URL below with your actual Google Maps embed link -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.3887895174445!2d77.31497427550128!3d28.62539027567374!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce5d1f9a4f3d7%3A0x7d7e21b6e5c2d2ae!2sSector%205%2C%20Noida%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin"
                    width="100%"
                    height="420"
                    style="border:0; display:block;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="LogixCode Location">
                </iframe>
            </div>
        </div>
    </section>

    <!-- FAQ Strip -->
    <section class="py-24 bg-surface">
        <div class="max-w-4xl mx-auto px-8">
            <h2 class="text-3xl font-extrabold font-headline text-center mb-16 tracking-tight">Frequently Asked Questions</h2>
            <div class="space-y-4" id="faqContainer">

                <!-- FAQ Item -->
                <div class="faq-item bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-8 py-5 text-left">
                        <span class="font-semibold font-headline text-base">What is the admission process?</span>
                        <span class="material-symbols-outlined text-[#03bfd3] faq-icon transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-body px-8 pb-6 text-sm text-on-surface-variant leading-relaxed hidden">
                        Simply fill out the registration form on our website or visit our institute. Our counsellors will guide you through batch selection, fees, and documentation.
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-8 py-5 text-left">
                        <span class="font-semibold font-headline text-base">Do you offer online classes?</span>
                        <span class="material-symbols-outlined text-[#03bfd3] faq-icon transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-body px-8 pb-6 text-sm text-on-surface-variant leading-relaxed hidden">
                        Yes! We offer both offline (Noida campus) and live online batches. All sessions are recorded for revision access.
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-8 py-5 text-left">
                        <span class="font-semibold font-headline text-base">Is placement assistance guaranteed?</span>
                        <span class="material-symbols-outlined text-[#03bfd3] faq-icon transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-body px-8 pb-6 text-sm text-on-surface-variant leading-relaxed hidden">
                        We have a 95% placement rate. Our dedicated placement cell connects you with 100+ hiring partners and provides mock interviews, resume building, and direct referrals.
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-8 py-5 text-left">
                        <span class="font-semibold font-headline text-base">What are the batch timings?</span>
                        <span class="material-symbols-outlined text-[#03bfd3] faq-icon transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-body px-8 pb-6 text-sm text-on-surface-variant leading-relaxed hidden">
                        We offer morning (9 AM), afternoon (1 PM), and evening (6 PM) batches from Monday to Saturday. Weekend batches are also available for working professionals.
                    </div>
                </div>

                <!-- FAQ Item -->
                <div class="faq-item bg-surface-container-lowest rounded-xl ghost-border editorial-shadow overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-8 py-5 text-left">
                        <span class="font-semibold font-headline text-base">Can I get a course demo before enrolling?</span>
                        <span class="material-symbols-outlined text-[#03bfd3] faq-icon transition-transform duration-300">expand_more</span>
                    </button>
                    <div class="faq-body px-8 pb-6 text-sm text-on-surface-variant leading-relaxed hidden">
                        Absolutely! We offer a free 1-day demo class for any course. Just fill the contact form above or WhatsApp us to book your slot.
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section (same as About page) -->
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

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 opacity-0 pointer-events-none z-50 bg-[#03bfd3] text-white px-8 py-4 rounded-full shadow-2xl font-semibold font-headline flex items-center gap-3 text-sm"
         style="transform: translate(-50%, 20px);">
        <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
        Message sent! We'll get back to you soon.
    </div>

    <?php include "includes/footer.php" ?>

    <script>
        // FAQ Toggle
        function toggleFaq(btn) {
            const body = btn.nextElementSibling;
            const icon = btn.querySelector('.faq-icon');
            const isOpen = !body.classList.contains('hidden');

            // Close all
            document.querySelectorAll('.faq-body').forEach(b => b.classList.add('hidden'));
            document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = '');

            if (!isOpen) {
                body.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Form Submit Handler (client-side demo — wire to PHP backend as needed)
        function handleSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            // Loading state
            btn.disabled = true;
            btnText.textContent = 'Sending...';
            btnIcon.textContent = 'hourglass_top';

            // Simulate async submit — replace with fetch('/contact-submit.php', ...) for real use
            setTimeout(() => {
                btn.disabled = false;
                btnText.textContent = 'Send Message';
                btnIcon.textContent = 'send';
                document.getElementById('contactForm').reset();
                showToast();
            }, 1500);
        }

        function showToast() {
            const toast = document.getElementById('toast');
            toast.style.opacity = '1';
            toast.style.transform = 'translate(-50%, 0)';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, 20px)';
            }, 3500);
        }
    </script>

</body>
</html>