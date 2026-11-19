<!-- FontAwesome for Social Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<footer class="bg-[var(--dark)] text-[var(--on-dark)] mt-auto pb-20 md:pb-0">
  <div class="mx-auto max-w-[1200px] px-4 sm:px-6 pt-14 pb-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
      
      <!-- Column 1: Brand & About -->
      <div class="flex flex-col gap-5">
        <div class="flex items-center gap-2.5">
          <div class="flex h-10 w-10 items-center justify-center rounded-[10px] bg-[var(--surface)]">
            <img 
              src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp"
              alt="LogixCode logo"
              class="h-8 w-auto"
              width="32"
              height="32"
            />
          </div>
          <span class="text-xl font-bold tracking-tight text-[var(--on-dark)] font-headline">
            Logix<span class="text-[var(--teal)]">Code</span>
          </span>
        </div>
        <p class="text-[var(--on-dark)] opacity-85 text-sm leading-relaxed">
          LogixCode Training Institute is Kanpur's premier software engineering and IT training academy providing practical project mentorship, industry certifications, and 100% placement assistance.
        </p>
        <div class="flex gap-3">
          <a href="https://www.instagram.com/logix.code/" target="_blank" rel="noopener" aria-label="LogixCode on Instagram" class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-[var(--teal)]/40 text-[var(--on-dark)] hover:bg-[var(--teal)] hover:text-[var(--ink)] transition-colors duration-150">
            <i class="fab fa-instagram text-base"></i>
          </a>
          <a href="https://www.linkedin.com/company/logixcodekanpur/" target="_blank" rel="noopener" aria-label="LogixCode on LinkedIn" class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-[var(--teal)]/40 text-[var(--on-dark)] hover:bg-[var(--teal)] hover:text-[var(--ink)] transition-colors duration-150">
            <i class="fab fa-linkedin-in text-base"></i>
          </a>
          <a href="https://www.facebook.com/people/logixcode/61579420961455/" target="_blank" rel="noopener" aria-label="LogixCode on Facebook" class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-[var(--teal)]/40 text-[var(--on-dark)] hover:bg-[var(--teal)] hover:text-[var(--ink)] transition-colors duration-150">
            <i class="fab fa-facebook-f text-base"></i>
          </a>
          <a href="https://www.youtube.com/@logixcodekanpur" target="_blank" rel="noopener" aria-label="LogixCode on YouTube" class="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full border border-[var(--teal)]/40 text-[var(--on-dark)] hover:bg-[var(--teal)] hover:text-[var(--ink)] transition-colors duration-150">
            <i class="fab fa-youtube text-base"></i>
          </a>
        </div>
      </div>

      <!-- Column 2: Quick links -->
      <div class="flex flex-col gap-4">
        <h3 class="text-[var(--on-dark)] text-lg font-semibold font-headline border-l-2 border-[var(--teal)] pl-3">Quick links</h3>
        <ul class="flex flex-col gap-2.5 text-sm">
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>">Home</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses">All courses</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>about">About us</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>registration">Online registration</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>contact">Contact & Kanpur campus</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>privacy-policy">Privacy policy</a></li>
          <li><a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>terms-and-condition">Terms & conditions</a></li>
        </ul>
      </div>

      <!-- Column 3: Popular courses -->
      <div class="flex flex-col gap-4">
        <h3 class="text-[var(--on-dark)] text-lg font-semibold font-headline border-l-2 border-[var(--teal)] pl-3">Popular courses</h3>
        <ul class="flex flex-col gap-2.5 text-sm">
          <li>
            <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses/python-training-kanpur/">
              Python course in Kanpur
            </a>
          </li>
          <li>
            <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses/full-stack-web-development-kanpur/">
              Full stack MERN course
            </a>
          </li>
          <li>
            <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses/java-programming-kanpur/">
              Java Spring Boot training
            </a>
          </li>
          <li>
            <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses/data-science-machine-learning-kanpur/">
              Data science & machine learning
            </a>
          </li>
          <li>
            <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4 py-1 inline-block" href="<?= BASE_URL ?>courses/android-app-development-kanpur/">
              Android app development
            </a>
          </li>
        </ul>
      </div>

      <!-- Column 4: Contact information -->
      <div class="flex flex-col gap-4">
        <h3 class="text-[var(--on-dark)] text-lg font-semibold font-headline border-l-2 border-[var(--teal)] pl-3">Kanpur campus</h3>
        <div class="flex flex-col gap-3.5 text-sm">
          <div class="flex gap-3 items-start">
            <span class="material-symbols-outlined text-[var(--teal)] mt-0.5 text-lg">location_on</span>
            <p class="text-[var(--on-dark)] leading-snug">Shri Balaji Chauraha, 2/1 Koyla Nagar, Swarn Jayanti Vihar, Kanpur, UP 208013</p>
          </div>
          <div class="flex gap-3 items-center">
            <span class="material-symbols-outlined text-[var(--teal)] text-lg">call</span>
            <a href="tel:+918467898854" class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4">+91 8467898854</a>
          </div>
          <div class="flex gap-3 items-center">
            <span class="material-symbols-outlined text-[var(--teal)] text-lg">mail</span>
            <a href="mailto:info@logixcode.com" class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4">info@logixcode.com</a>
          </div>
          <div class="flex gap-3 items-center">
            <span class="material-symbols-outlined text-[var(--teal)] text-lg">schedule</span>
            <p class="text-[var(--on-dark)]">Mon–Sat: 9:00 AM – 7:00 PM</p>
          </div>
        </div>
      </div>

    </div>

    <!-- Bottom Bar -->
    <div class="mt-12 pt-6 border-t border-[var(--on-dark)]/15 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-[var(--on-dark)]/80">
      <p class="text-center md:text-left">
        © 2025–2026 <span class="text-[var(--on-dark)] font-semibold">LogixCode Training</span>. All rights reserved.
      </p>
      <div class="flex gap-6 items-center">
        <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4" href="<?= BASE_URL ?>privacy-policy">Privacy policy</a>
        <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4" href="<?= BASE_URL ?>terms-and-condition">Terms & conditions</a>
        <a class="text-[var(--on-dark)] hover:underline decoration-[var(--teal)] underline-offset-4" href="<?= BASE_URL ?>contact">Kanpur campus</a>
      </div>
    </div>
  </div>
</footer>

<!-- Sticky Mobile Bottom Bar with "Free demo class" CTA -->
<div class="fixed bottom-0 inset-x-0 z-40 md:hidden bg-[var(--surface)] border-t border-[var(--border)] px-4 py-2.5 flex items-center justify-between gap-3">
  <div class="flex flex-col">
    <span class="text-xs font-medium text-[var(--muted)]">Kanpur & online batches</span>
    <a href="tel:+918467898854" class="text-sm font-semibold text-[var(--ink)]">+91 8467898854</a>
  </div>
  <a href="<?= BASE_URL ?>contact" class="btn-primary text-sm px-5 py-2.5">
    Free demo class
  </a>
</div>
