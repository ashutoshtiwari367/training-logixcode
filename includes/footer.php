<!-- Footer
 <style>
    /* Footer Styles */
.footer {
   background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
    border-top: 1px solid rgba(2, 158, 170, 0.2);
    padding: 60px 0 20px;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 50px;
    margin-bottom: 40px;
}

.footer-brand {
    max-width: 400px;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.footer-logo-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #029eaa, #0abcd6);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #000;
    font-size: 1.5rem;
}

.footer-logo-text {
    font-size: 2rem;
    font-weight: 800;
   color: #2E2E2E;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-description {
    color: #2E2E2E;
    line-height: 1.6;
    margin-bottom: 25px;
    font-size: 1rem;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    width: 45px;
    height: 45px;
    background: rgba(2, 158, 170, 0.1);
    border: 1px solid rgba(2, 158, 170, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2E2E2E;
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #029eaa;
    color: #000;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(2, 158, 170, 0.3);
}

.footer-section h3 {
  color: #2E2E2E;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: #2E2E2E;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
}

.footer-links a:hover {
    color: #029eaa;
    padding-left: 10px;
}

.contact-info {
    list-style: none;
}

.contact-info li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
  color: #2E2E2E;
    font-size: 0.95rem;
}

.contact-icon {
    width: 20px;
    height: 20px;
    background: rgba(2, 158, 170, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #029eaa;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.footer-bottom {
    border-top: 1px solid rgba(2, 158, 170, 0.2);
    padding-top: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.copyright {
  color: #2E2E2E;
    font-size: 0.9rem;
}

.footer-bottom-links {
    display: flex;
    gap: 30px;
    list-style: none;
}

.footer-bottom-links a {
  color: #2E2E2E;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.footer-bottom-links a:hover {
    color: #029eaa;
}

@media (max-width: 968px) { 

    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }

    .footer-bottom-links {
        justify-content: center;
    }
}

.iso{
    display: flex;
    width:100%;
    height: auto;
    
}
.icon-img{
width: 100px;
height: 50px;
}
.icon-img{
    width: 100%;
    height: 100%;
}
 </style>
<footer class="footer" id="contact">
    <div class="container">
        <div class="footer-content">
            <!-- Brand Section
            <div class="footer-brand">
                <div class="footer-logo">
                    <a href="#" class="nav-logo">
                        <div class="logo-icon">
                            <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"
                                alt="logo">
                        </div>
                        <span class="logo-text">Logixcode</span>
                    </a>

                    </a>
                    -
                </div>
                <p class="footer-description">
                    LogixCode is a development-focused company delivering innovative, scalable, and industry-relevant software solutions for businesses worldwide
                </p>
                <div class="social-links">
                    <a href="https://www.instagram.com/logix.code/" class="social-link"><i
                            data-lucide="instagram"></i></a>
                    <a href="https://www.facebook.com/profile.php?id=61579420961455" class="social-link"><i
                            data-lucide="facebook"></i></a>

                    <a href="https://www.linkedin.com/company/logixcodekanpur/" class="social-link"><i
                            data-lucide="linkedin"></i></a>

                    <a href="https://www.youtube.com/@logixcodekanpur" class="social-link"><i
                            data-lucide="youtube"></i></a>
                </div>
            </div>

            <!-- Quick Links
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About Us</a></li>
                    <li><a href="/courses">Courses</a></li>
                   
                    <li><a href="https://logixcode.com/">Contact</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/terms-and-condition">Terms & Condition</a></li>
                </ul>
            </div>

            <!-- Courses
            <div class="footer-section">
                <h3>IT Services</h3>
                <ul class="footer-links">
                    <li><a href="/contact">Software Development</a></li>
                     <li><a href="/contact">Website Development</a></li>
                     <li><a href="/contact">Mobile App Development</a></li>
                 <li><a href="/contact">Digital Marketing</a></li>
                     <li><a href="/contact">Graphics Designing</a></li>
                   <li><a href="/contact">Domain & Hosting</a></li>
               
                </ul>
            </div>

            <!-- Contact Info
            <div class="footer-section">
                <h3>Contact Info</h3>
                <ul class="contact-info">
                    <li>
                        <div class="contact-icon">📍</div>
                        <span>Shri Balaji Chauraha, 2/1, Koyla Nagar, Swarn Jayanti Vihar, Kanpur, Uttar Pradesh
                            208013</span>
                    </li>
                    <li>
                        <div class="contact-icon">📞</div>
                        <span>+91 8467898854</span>
                    </li>
                    <li>
                        <div class="contact-icon">✉️</div>
                        <span>info@logixcode.com</span>
                    </li>
                    <li>
                        <div class="contact-icon">✉️</div>
                        <span>info@training.logixcode.com</span>
                    </li>
                    <li>
                        <div class="contact-icon">🌐</div>
                        <span>www.logixcode.com</span>
                    </li>
                    <li>
                        <div class="contact-icon">🕒</div>
                        <span>Mon - Sat: 9AM - 7PM</span>
                    </li>
                </ul>
            </div>
        </div>
        <!--<div class="container iso">-->
        <!--    <div class="icon-img"><img src="https://res.cloudinary.com/de7mh41io/image/upload/e_background_removal/f_png/v1770138544/png-clipart-government-of-india-ministry-of-micro-small-and-medium-enterprises-small-business-india-text-logo-thumbnail_cu5aag.png"></div>-->
        <!--                <div class="icon-img"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1770138629/images_gbcgvj.png"></div>-->
        <!--                            <div class="icon-img"><img src="https://res.cloudinary.com/de7mh41io/image/upload/v1770138691/gem-1_aroh7r.jpg"></div>-->
        <!--
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                <p>&copy; 2025-2026 LogixCode. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>


<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>





-->




<!-- Material Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@300;400;500;600;700&display=swap" rel="stylesheet">


<style>
    .footer-gradient{
background:linear-gradient(135deg,#081a18 0%,#0d2e2b 100%);
}
</style>


<footer class="footer-gradient text-white mt-auto">
<div class="max-w-[1440px] mx-auto px-6 md:px-20 lg:px-40 pt-20 pb-10">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
<!-- Column 1: Brand & About -->
<div class="flex flex-col gap-6">
<div class="flex items-center gap-4">
<div class="size-8 text-[#03c4ce]-accent">
    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-white">
<img 
src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"
alt="LogixCode Logo"
class="h-8 w-auto"
/>
</div>
</div>
<h2 class="text-2xl font-bold tracking-tight text-white">Logix<span class="text-[#03c4ce]">Code</span></h2>
</div>
<p class="text-slate-300 text-sm leading-relaxed">
                                LogixCode is a technology training and development company focused on delivering industry-relevant skills, practical learning, and scalable digital solutions.
                            </p>
<div class="flex gap-3">
    <!-- Instagram -->
    <a href="https://www.instagram.com/logix.code/" class="group flex size-10 items-center justify-center rounded-full bg-white/10 hover:bg-pink-500 transition-all duration-300">
        <i class="fab fa-instagram text-white text-lg"></i>
    </a>

    <!-- LinkedIn -->
    <a href="https://www.linkedin.com/company/logixcodekanpur/" class="group flex size-10 items-center justify-center rounded-full bg-white/10 hover:bg-blue-600 transition-all duration-300">
        <i class="fab fa-linkedin-in text-white text-lg"></i>
    </a>

    <!-- Facebook -->
    <a href="https://www.facebook.com/people/logixcode/61579420961455/" class="group flex size-10 items-center justify-center rounded-full bg-white/10 hover:bg-blue-500 transition-all duration-300">
        <i class="fab fa-facebook-f text-white text-lg"></i>
    </a>

    <!-- YouTube -->
    <a href="https://www.youtube.com/@logixcodekanpur" class="group flex size-10 items-center justify-center rounded-full bg-white/10 hover:bg-red-600 transition-all duration-300">
        <i class="fab fa-youtube text-white text-lg"></i>
    </a>
</div>
</div>
<!-- Column 2: Quick Links -->
<div class="flex flex-col gap-6">
<h3 class="text-white text-lg font-bold border-l-4 border-[#03c4ce]-accent pl-3">Quick Links</h3>
<ul class="flex flex-col gap-4">
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/">Home</a></li>
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/about">About Us</a></li>
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/courses">Courses</a></li>
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/contact">Contact</a></li>
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/privacy-policy">Privacy Policy</a></li>
<li><a class="text-slate-300 text-sm link-hover-effect hover:text-white transition-colors" href="/terms-and-condition">Terms &amp; Conditions</a></li>
</ul>
</div>
<!-- Column 3: Services -->
<div class="flex flex-col gap-6">
<h3 class="text-white text-lg font-bold border-l-4 border-[#03c4ce]-accent pl-3">Training &amp; IT Services</h3>
<ul class="flex flex-col gap-4">
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Software Development</a></li>
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Website Development</a></li>
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Mobile App Development</a></li>
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Digital Marketing</a></li>
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Graphic Design</a></li>
<li><a class="flex items-center gap-2 text-slate-300 text-sm hover:text-[#03c4ce]-accent transition-colors group" href="https://www.logixcode.com/Contact">
<span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward_ios</span> Domain &amp; Hosting</a></li>
</ul>
</div>
<!-- Column 4: Contact & Newsletter -->
<div class="flex flex-col gap-6">
<h3 class="text-white text-lg font-bold border-l-4 border-[#03c4ce]-accent pl-3">Contact Information</h3>
<div class="flex flex-col gap-4">
<div class="flex gap-3 items-start">
<span class="material-symbols-outlined text-[#03c4ce]-accent">location_on</span>
<p class="text-slate-300 text-sm leading-snug">Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar Kanpur, UP 208013</p>
</div>
<div class="flex gap-3 items-center">
<span class="material-symbols-outlined text-[#03c4ce]-accent">call</span>
<p class="text-slate-300 text-sm">+91 8467898854</p>
</div>
<div class="flex gap-3 items-center">
<span class="material-symbols-outlined text-[#03c4ce]-accent">mail</span>
<p class="text-slate-300 text-sm">info@logixcode.com</p>
</div>
<div class="flex gap-3 items-center">
<span class="material-symbols-outlined text-[#03c4ce]-accent">mail</span>
<p class="text-slate-300 text-sm">info@traning.logixcode.com</p>
</div>
<div class="flex gap-3 items-center">
<span class="material-symbols-outlined text-[#03c4ce]-accent">schedule</span>
<p class="text-slate-300 text-sm">Mon-Sat: 9 AM - 7 PM</p>
</div>
</div>
</div>
</div>
<!-- Bottom Bar -->
<div class="mt-16 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-6">
<p class="text-slate-400 text-xs text-center md:text-left">
                            © 2025–2026 <span class="text-white font-bold tracking-wide">LogixCode</span>. All Rights Reserved.
                        </p>
<div class="flex gap-6 items-center">
<a class="text-slate-400 text-xs hover:text-white transition-colors" href="/privacy-policy">Privacy Policy</a>
<a class="text-slate-400 text-xs hover:text-white transition-colors" href="/terms-and-condition">Terms & Conditions</a>
<a class="text-slate-400 text-xs hover:text-white transition-colors" href="https://www.logixcode.com/Contact">Contact</a>
</div>
</div>
</div>
</footer>
