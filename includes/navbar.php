<!-- 
<html>
<head>
    <link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1756286798/logixcode-icon.webp" type="image/x-icon">


<style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f9fa;
      padding-top: 80px;
    }
    .register-sidebar {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #00a651 0%, #008541 100%);
            padding: 20px 15px;
            border-radius: 0 8px 8px 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .register-sidebar:hover {
            padding-left: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
        }

        .register-sidebar-right {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #00a651 0%, #008541 100%);
            padding: 20px 15px;
            border-radius: 8px 0 0 8px;
            box-shadow: -3px 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .register-sidebar-right:hover {
            padding-right: 20px;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
        }

        .register-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            color: white;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
     
    /* NAVBAR */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(2, 158, 170, 0.2);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    /* Scrolled effect */
    .navbar.scrolled {
      background: rgba(255, 255, 255, 0.98);
      box-shadow: 0 5px 20px rgba(2, 158, 170, 0.15);
    }

    /* Container */
    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 80px;
    }

    /* Logo */
    .nav-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      gap: 12px;
    }

    .logo-icon {
      width: 45px;
      height: 45px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      font-size: 1.3rem;
      transition: transform 0.3s ease;
    }

    .logo-icon img {
      width: 100%;
      height: 100%;
    }

    .nav-logo:hover .logo-icon {
      transform: rotate(5deg) scale(1.1);
    }

    .logo-text {
      font-size: 1.8rem;
      font-weight: 800;
      color: #03c1d1;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* Menu */
    .nav-menu {
      display: flex;
      list-style: none;
      gap: 35px;
      align-items: center;
    }

    /* Menu Links */
    .nav-link {
      color: #2E2E2E;
      text-decoration: none;
      font-weight: 500;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 10px 0;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(45deg, #03c1d1, #0abcd6);
      transition: width 0.3s ease;
    }

    .nav-link:hover {
      color: #029eaa;
    }

    .nav-link:hover::before,
    .nav-link.active::before {
      width: 100%;
    }

    .nav-link.active {
      color: #03c1d1;
      font-weight: 600;
    }

    /* CTA Button */
    .nav-cta {
      padding: 12px 25px;
      background: linear-gradient(45deg, #03c1d1, #0abcd6);
      border: none;
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
      border-radius: 30px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(2, 158, 170, 0.3);
      position: relative;
      overflow: hidden;
    }

    .nav-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(2, 158, 170, 0.4);
    }

    /* Mobile Toggle */
    .mobile-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 5px;
    }

    .toggle-bar {
      width: 25px;
      height: 3px;
      background: #03c1d1;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    /* Active Toggle Effect */
    .mobile-toggle.active .toggle-bar:nth-child(1) {
      transform: rotate(45deg) translate(5px, 6px);
    }

    .mobile-toggle.active .toggle-bar:nth-child(2) {
      opacity: 0;
    }

    .mobile-toggle.active .toggle-bar:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Responsive */
    @media (max-width: 968px) {
      .nav-menu {
        position: fixed;
        top: 80px;
        left: -100%;
        width: 100%;
        height: calc(100vh - 80px);
        background: rgba(255, 255, 255, 0.98);
        flex-direction: column;
        padding-top: 60px;
        gap: 30px;
        transition: left 0.3s ease;
        backdrop-filter: blur(20px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      }

      .nav-menu.active {
        left: 0;
      }

      .mobile-toggle {
        display: flex;
      }

      .nav-cta {
        margin-top: 20px;
      }

      .nav-link {
        font-size: 1.2rem;
        padding: 10px 0;
      }
    }

    @media (max-width: 480px) {
      .nav-container {
        padding: 0 15px;
      }

      .logo-text {
        font-size: 1.5rem;
      }

      .logo-icon {
        width: 40px;
        height: 40px;
      }
    }

    /* Neural Network Animation */
    .neural-network {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }

    .node {
      position: absolute;
      width: 4px;
      height: 4px;
      background: #029eaa;
      border-radius: 50%;
      opacity: 0.4;
      animation: pulse-node 3s ease-in-out infinite;
    }

    .node:nth-child(1) {
      top: 25%;
      left: 15%;
      animation-delay: 0s;
    }

    .node:nth-child(2) {
      top: 45%;
      left: 25%;
      animation-delay: 0.5s;
    }

    .node:nth-child(3) {
      top: 35%;
      left: 75%;
      animation-delay: 1s;
    }

    .node:nth-child(4) {
      top: 65%;
      left: 85%;
      animation-delay: 1.5s;
    }

    .node:nth-child(5) {
      top: 75%;
      left: 15%;
      animation-delay: 2s;
    }

    @keyframes pulse-node {
      0%, 100% {
        opacity: 0.4;
        transform: scale(1);
      }
      50% {
        opacity: 0.7;
        transform: scale(1.5);
      }
    }

    /* Demo Content */
    .demo-content {
      padding: 60px 20px;
      max-width: 1200px;
      margin: 0 auto;
      min-height: 200vh;
    }

    .demo-section {
      background: white;
      padding: 40px;
      border-radius: 20px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .demo-section h2 {
      color: #1a1a1a;
      margin-bottom: 20px;
      font-size: 2rem;
    }

    .demo-section p {
      color: #555555;
      line-height: 1.6;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <!-- NAVBAR
  <nav class="navbar" id="navbar">
        <!--<div class="top-bar">-->
        <!--<div class="top-bar-container">-->
        <!--    <div class="contact-info-n">-->
        <!--        <a href="tel:+911234567890" class="phone-number">-->
        <!--            <svg class="phone-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">-->
        <!--                <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>-->
        <!--            </svg>-->
        <!--            +918467898854 +918808123160-->
        <!--        </a>-->
        <!--    </div>-->
            
        <!--    <div class="top-buttons">-->
        <!--        <a href="https://training.logixcode.com " class="top-btn training-btn">Development Website</a>-->
        <!--          <a href="../assets/Logixcode-Brochure.pdf" download class="top-btn download-btn">Download Brochure</a>-->
        <!--    </div>-->
        <!--</div>-->
<!--    
</div>
    <div class="nav-container">
      <a href="/" class="nav-logo">
        <div class="logo-icon">
          <img src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="logo">
        </div>
        <span class="logo-text">Logixcode</span>
      </a>

      <ul class="nav-menu" id="nav-menu">
        <li class="nav-item"><a href="/" class="nav-link active">Home</a></li>
        <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="/courses" class="nav-link">Courses</a></li>
     <li class="nav-item"><a href="/registration" class="nav-link">Registration</a></li>
       
        <li class="nav-item"><a href="https://logixcode.com/Contact" class="nav-link">Contact</a></li>
        <li class="nav-item"><a href="/Logixcode-Brochure.pdf" download" download class="nav-cta">Download Brochure</a></li>
      </ul>

      <div class="mobile-toggle" id="mobile-toggle">
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
      </div>
    </div>
  </nav>

  <a href="https://training.logixcode.com/registration"><div class="register-sidebar">
        <div class="register-text">Register for Training</div>
    </div></a>

    <!-- Register for Training Sidebar - Right Side
    
  <a href="https://training.logixcode.com/assesment/">
    <div class="register-sidebar-right">
        <div class="register-text">Assesment Portal</div>
    </div>
   </a>
 
  <script>
    // navbar scroll effect
    const navbar = document.getElementById("navbar");
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });

    // mobile menu toggle
    const toggle = document.getElementById("mobile-toggle");
    const navMenu = document.getElementById("nav-menu");

    toggle.addEventListener("click", () => {
      toggle.classList.toggle("active");
      navMenu.classList.toggle("active");
    });

    // close menu when clicking link
    document.querySelectorAll(".nav-link").forEach(link =>
      link.addEventListener("click", () => {
        toggle.classList.remove("active");
        navMenu.classList.remove("active");
      })
    );
  </script>

</html>

-->

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<header class="fixed top-0 z-50 w-full border-b border-[#03c4ce]/10 bg-white backdrop-blur-md ease-smooth">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-6 md:py-4">

    <!-- Logo -->
    <div class="flex items-center gap-3">
      <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">
        <img 
          src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"
          alt="LogixCode Logo"
          class="h-8 w-auto"
        />
      </div>
      <h2 class="text-xl md:text-2xl font-bold tracking-tight text-slate-900">
        Logix<span class="text-[#03c4ce]">Code</span>
      </h2>
    </div>
    
    
    <?php
        $currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
        
        function isActive($path) {
            global $currentPath;
        
            if ($path === '/') {
                return $currentPath === '/';
            }
        
            return $currentPath === $path || strpos($currentPath, $path) === 0;
        }
        
        function navClass($path) {
            return isActive($path) ? 'text-[#03c4ce]' : 'text-slate-700';
        }
        
        function spanClass($path) {
            return isActive($path) ? 'w-full' : 'w-0';
        }
    ?>

    <!-- Desktop Nav -->
    <nav class="hidden md:flex items-center gap-8">

      <a href="/" class="group relative text-sm font-semibold <?= navClass('/') ?>">
        Home
        <span class="absolute left-0 -bottom-1 h-[2px] <?= spanClass('/') ?> bg-[#03c4ce] transition-all duration-300"></span>
      </a>

      <a href="/about" class="group relative text-sm font-semibold <?= navClass('/about') ?>">
        About
        <span class="absolute left-0 -bottom-1 h-[2px] <?= spanClass('/about') ?> bg-[#03c4ce] transition-all duration-300 group-hover:w-full"></span>
      </a>

      <a href="/courses" class="group relative text-sm font-semibold <?= navClass('/courses') ?>">
        Courses
        <span class="absolute left-0 -bottom-1 h-[2px] <?= spanClass('/courses') ?> bg-[#03c4ce] transition-all duration-300 group-hover:w-full"></span>
      </a>

      <a href="/registration" class="group relative text-sm font-semibold <?= navClass('/registration') ?>">
        Registration
        <span class="absolute left-0 -bottom-1 h-[2px] <?= spanClass('/registration') ?> bg-[#03c4ce] transition-all duration-300 group-hover:w-full"></span>
      </a>

      <a href="/contact" class="group relative text-sm font-semibold <?= navClass('/contact') ?>">
        Contact
        <span class="absolute left-0 -bottom-1 h-[2px] <?= spanClass('/contact') ?> bg-[#03c4ce] transition-all duration-300 group-hover:w-full"></span>
      </a>

    </nav>

    <!-- Right Side -->
    <div class="flex items-center gap-3">

      <!-- Brochure Button -->
      <a href="/Logixcode-Brochure.pdf" download class="hidden md:block">
        <button class="rounded-xl bg-[#03c4ce] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-[#03c4ce]/20 hover:bg-[#03c4ce]/90 transition-all">
          <div class="flex flex-row">
            Download Brochure &nbsp;
            <span class="material-symbols-outlined text-white">download</span>
          </div>
        </button>
      </a>

      <!-- Hamburger -->
      <button id="menuBtn" class="md:hidden text-2xl text-slate-900">
        ☰
      </button>
    </div>

  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden md:hidden px-6 pb-6 animate-fadeIn bg-white">
    <nav class="flex flex-col gap-4 mt-4">
      <a href="/" class="text-sm font-semibold text-slate-700">Home</a>
      <a href="/about" class="text-sm font-semibold text-slate-700">About</a>
      <a href="/courses" class="text-sm font-semibold text-slate-700">Courses</a>
      <a href="/registration" class="text-sm font-semibold text-slate-700">Registration</a>
      <a href="/contact" class="text-sm font-semibold text-slate-700">Contact</a>

      <a href="/Logixcode-Brochure.pdf" download>
        <button class="mt-3 w-full rounded-xl bg-[#03c4ce] px-3 py-2 text-sm font-bold text-white">
          Download Brochure
        </button>
      </a>
    </nav>
  </div>
</header>

<script>
  const menuBtn = document.getElementById("menuBtn");
  const mobileMenu = document.getElementById("mobileMenu");

  menuBtn.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });
</script>