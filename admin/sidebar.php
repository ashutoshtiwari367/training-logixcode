<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
.sidebar-gradient {
            background: linear-gradient(180deg, #0284c7 0%, #0d9488 100%);
        }
</style>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Sidebar -->
<aside id="sidebar"
class="w-72 sidebar-gradient text-white flex flex-col fixed inset-y-0 left-0 z-50 shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
<div class="p-6 flex items-center gap-3">
<div class="bg-white p-2 rounded-xl backdrop-blur-md">
<img class="h-10 w-10" src="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp" alt="" srcset="">
</div>
<div>
<h1 class="text-xl font-bold leading-tight">Admin Panel</h1>
<p class="text-white/70 text-xs font-medium uppercase tracking-wider">LogixCode</p>
</div>
</div>
<nav class="flex-1 px-4 mt-4 space-y-2">
<a href="dashboard.php"
class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
<?= $currentPage == 'dashboard.php' 
? 'bg-white/20 text-white font-semibold' 
: 'hover:bg-white/10 text-white/80 hover:text-white' ?>">
<span class="material-symbols-outlined">dashboard</span>
<span>Dashboard</span>
</a>
<?php // if ($currentUser['role'] === 'office' || $currentUser['role'] === 'admin'): ?>
<a href="add-registration.php"
class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
<?= $currentPage == 'add-registration.php' 
? 'bg-white/20 text-white font-semibold' 
: 'hover:bg-white/10 text-white/80 hover:text-white' ?>">
<span class="material-symbols-outlined">person_add</span>
<span>Add Registration</span>
</a>
<?php // endif; ?>
<a href="admissions.php"
class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
<?= $currentPage == 'admissions.php' 
? 'bg-white/20 text-white font-semibold' 
: 'hover:bg-white/10 text-white/80 hover:text-white' ?>">
<span class="material-symbols-outlined">assignment</span>
<span>Admission Portal</span>
</a>
<a href="assign-student.php"
class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
<?= $currentPage == 'assign-student.php' 
? 'bg-white/20 text-white font-semibold' 
: 'hover:bg-white/10 text-white/80 hover:text-white' ?>">
<span class="material-symbols-outlined">key</span>
<span>Student Assign</span>
</a>
</nav>
<div class="p-4 mt-auto">
<div class="rounded-2xl p-4 mb-4">
<p class="text-xs mb-1"></p>
<div class="w-full rounded-full h-1.5 mb-2">
<div class="h-1.5 rounded-full w-3/4"></div>
</div>
<p class=""></p>
</div>
<a href="logout.php">
<button class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-rose-500 text-white transition-all border border-rose-500/30">
<span class="material-symbols-outlined">logout</span>
<span class="font-semibold">Logout</span>
</button>
</a>
</div>
</aside>