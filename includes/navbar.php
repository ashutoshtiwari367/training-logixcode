<?php
if (!defined('BASE_URL')) {
    $docRoot  = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')) : '';
    $projRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    if ($docRoot !== '' && $projRoot !== false && strpos($projRoot, $docRoot) === 0) {
        $subPath = trim(substr($projRoot, strlen($docRoot)), '/');
        define('BASE_URL', $subPath === '' ? '/' : '/' . $subPath . '/');
    } else {
        define('BASE_URL', '/');
    }
}

include_once __DIR__ . '/theme.php';

$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/') ?: '/';

if (!function_exists('isActive')) {
    function isActive($path) {
        global $currentPath;
        $fullPath = BASE_URL . ltrim($path, '/');
        $fullPath = rtrim($fullPath, '/') ?: '/';

        if ($fullPath === '/') {
            return $currentPath === '/' || $currentPath === rtrim(BASE_URL, '/');
        }

        return $currentPath === $fullPath || strpos($currentPath, $fullPath) === 0;
    }

    function navClass($path) {
        return isActive($path) ? 'text-[var(--teal-text)] font-semibold' : 'text-[var(--ink)] hover:text-[var(--teal-text)] font-medium';
    }

    function spanClass($path) {
        return isActive($path) ? 'w-full' : 'w-0';
    }
}
?>

<header class="sticky top-0 z-50 w-full border-b border-[var(--border)] bg-[var(--surface)]">
  <div class="mx-auto flex max-w-[1200px] items-center justify-between px-4 py-3 md:px-6">

    <!-- Logo -->
    <a href="<?= BASE_URL ?>" class="flex items-center gap-2.5 min-h-[44px]">
      <img 
        src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"
        alt="LogixCode logo"
        class="h-8 w-auto"
        width="32"
        height="32"
      />
      <span class="text-xl font-bold tracking-tight text-[var(--ink)] font-headline">
        Logix<span class="text-[var(--teal-text)]">Code</span>
      </span>
    </a>

    <!-- Desktop Nav -->
    <nav class="hidden lg:flex items-center gap-6" aria-label="Main navigation">
      <a href="<?= BASE_URL ?>" class="group relative py-2 text-base transition-colors duration-150 <?= navClass('/') ?>">
        Home
        <span class="absolute left-0 bottom-0 h-[2px] <?= spanClass('/') ?> bg-[var(--teal)] transition-all duration-150"></span>
      </a>

      <a href="<?= BASE_URL ?>courses" class="group relative py-2 text-base transition-colors duration-150 <?= navClass('/courses') ?>">
        Courses
        <span class="absolute left-0 bottom-0 h-[2px] <?= spanClass('/courses') ?> bg-[var(--teal)] transition-all duration-150 group-hover:w-full"></span>
      </a>

      <a href="<?= BASE_URL ?>about" class="group relative py-2 text-base transition-colors duration-150 <?= navClass('/about') ?>">
        About
        <span class="absolute left-0 bottom-0 h-[2px] <?= spanClass('/about') ?> bg-[var(--teal)] transition-all duration-150 group-hover:w-full"></span>
      </a>

      <a href="<?= BASE_URL ?>registration" class="group relative py-2 text-base transition-colors duration-150 <?= navClass('/registration') ?>">
        Registration
        <span class="absolute left-0 bottom-0 h-[2px] <?= spanClass('/registration') ?> bg-[var(--teal)] transition-all duration-150 group-hover:w-full"></span>
      </a>

      <a href="<?= BASE_URL ?>contact" class="group relative py-2 text-base transition-colors duration-150 <?= navClass('/contact') ?>">
        Contact
        <span class="absolute left-0 bottom-0 h-[2px] <?= spanClass('/contact') ?> bg-[var(--teal)] transition-all duration-150 group-hover:w-full"></span>
      </a>
    </nav>

    <!-- Right Actions: Single Coral Primary CTA + Mobile Hamburger -->
    <div class="flex items-center gap-3">
      <a href="<?= BASE_URL ?>Logixcode-Brochure.pdf" download class="hidden xl:inline-flex items-center gap-1.5 text-sm font-medium text-[var(--teal-text)] hover:underline min-h-[44px] px-2">
        Download brochure
      </a>

      <a href="<?= BASE_URL ?>registration" class="btn-primary hidden sm:inline-flex">
        Enroll now
      </a>

      <!-- Mobile Hamburger Toggle (44x44px minimum tap target) -->
      <button id="menuBtn" type="button" class="lg:hidden inline-flex items-center justify-center min-h-[44px] min-w-[44px] rounded-[10px] border border-[var(--border)] text-[var(--ink)] hover:bg-[var(--tint)] transition-colors duration-150" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobileMenu">
        <span class="material-symbols-outlined text-2xl">menu</span>
      </button>
    </div>

  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden lg:hidden px-4 pb-6 bg-[var(--surface)] border-t border-[var(--border)]">
    <nav class="flex flex-col gap-1 mt-3" aria-label="Mobile navigation">
      <a href="<?= BASE_URL ?>" class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--ink)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">Home</a>
      <a href="<?= BASE_URL ?>courses" class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--ink)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">Courses</a>
      <a href="<?= BASE_URL ?>about" class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--ink)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">About</a>
      <a href="<?= BASE_URL ?>registration" class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--ink)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">Registration</a>
      <a href="<?= BASE_URL ?>contact" class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--ink)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">Contact</a>
      <a href="<?= BASE_URL ?>Logixcode-Brochure.pdf" download class="py-2.5 px-3 rounded-[10px] text-base font-medium text-[var(--teal-text)] hover:bg-[var(--tint)] min-h-[44px] flex items-center">Download brochure</a>

      <div class="pt-3 mt-2 border-t border-[var(--border)]">
        <a href="<?= BASE_URL ?>registration" class="btn-primary w-full">
          Enroll now
        </a>
      </div>
    </nav>
  </div>
</header>

<script>
  const menuBtn = document.getElementById("menuBtn");
  const mobileMenu = document.getElementById("mobileMenu");

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener("click", () => {
      const isHidden = mobileMenu.classList.toggle("hidden");
      menuBtn.setAttribute("aria-expanded", !isHidden);
    });
  }
</script>
