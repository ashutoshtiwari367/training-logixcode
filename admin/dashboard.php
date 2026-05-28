<?php

/**
 * Admin Dashboard
 * admin/dashboard.php
 * 
 * Shows registrations and payments data
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$currentUser = getCurrentUser();



// ============================================
// EXISTING DB — Filters + Query
// ============================================
$filters = [
    'program'        => $_GET['program'] ?? '',
    'payment_status' => $_GET['payment_status'] ?? '',
    'date_from'      => $_GET['date_from'] ?? '',
    'date_to'        => $_GET['date_to'] ?? '',
    'search'         => $_GET['search'] ?? ''
];

$sql    = "SELECT r.*, p.payment_gateway_id, p.amount, p.status as payment_status 
           FROM registrations r 
           LEFT JOIN payments p ON r.registration_id = p.registration_id 
           WHERE 1=1";
$params = [];

if (!empty($filters['program'])) {
    $sql      .= " AND r.program = ?";
    $params[]  = $filters['program'];
}
if (!empty($filters['payment_status'])) {
    if ($filters['payment_status'] === 'PAID') {
        $sql .= " AND r.payment_mode = 'ONLINE' AND p.status = 'SUCCESS'";
    } elseif ($filters['payment_status'] === 'OFFLINE') {
        $sql .= " AND r.payment_mode = 'OFFLINE'";
    }
}
if (!empty($filters['date_from'])) {
    $sql      .= " AND DATE(r.created_at) >= ?";
    $params[]  = $filters['date_from'];
}
if (!empty($filters['date_to'])) {
    $sql      .= " AND DATE(r.created_at) <= ?";
    $params[]  = $filters['date_to'];
}
if (!empty($filters['search'])) {
    $sql       .= " AND (r.first_name LIKE ? OR r.last_name LIKE ? OR r.email LIKE ? OR r.registration_id LIKE ?)";
    $s          = '%' . $filters['search'] . '%';
    $params[]   = $s; $params[] = $s; $params[] = $s; $params[] = $s;
}

$sql .= " ORDER BY r.created_at DESC";

$stmt          = $pdo->prepare($sql);
$stmt->execute($params);
$registrations = $stmt->fetchAll();

// Stats — existing DB
$statsQuery = "SELECT 
    COUNT(*) as total_registrations,
    SUM(CASE WHEN payment_mode = 'ONLINE' THEN 1 ELSE 0 END) as online_payments,
    SUM(CASE WHEN payment_mode = 'OFFLINE' THEN 1 ELSE 0 END) as offline_payments,
    (SELECT SUM(amount) FROM payments WHERE status IN ('SUCCESS', 'OFFLINE')) as total_revenue
    FROM registrations";
$stats = $pdo->query($statsQuery)->fetch();

$programs = $pdo->query("SELECT DISTINCT program FROM registrations ORDER BY program")->fetchAll(PDO::FETCH_COLUMN);



?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>LogixCode Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="https://res.cloudinary.com/de7mh41io/image/upload/v1749888137/logixcode-logo.webp">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#0d9488",
                    "secondary": "#0284c7",
                    "background-light": "#f8fafd",
                    "background-dark": "#0f172a",
                },
                fontFamily: { "display": ["Public Sans", "sans-serif"] },
                borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
            },
        },
    }
</script>
<style>body { font-family: 'Public Sans', sans-serif; }</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100">
<div class="flex min-h-screen overflow-x-hidden">

<?php include "sidebar.php" ?>

<main class="flex-1 ml-72 min-h-screen flex flex-col min-w-0">

<!-- Header -->
<header class="h-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 sticky top-0 z-40">
<div class="flex items-center gap-4">
  <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Dashboard</h2>
</div>
<div class="flex items-center gap-6">
  <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
    <div class="text-right hidden sm:block">
      <p class="text-sm font-bold text-slate-800 dark:text-white leading-none"><?= htmlspecialchars($currentUser['name']) ?></p>
      <p class="text-xs text-slate-500 font-medium"><?= ucfirst($currentUser['role']) ?></p>
    </div>
    <div class="relative">
      <img alt="Admin" class="w-10 h-10 rounded-full border-2 border-primary/20" 
           src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgs4YCnHldqBXNo5wuIFzK91JkfvI61050N1brxU7Rv_wCycKCXIdJRo5qGj3e3Ju7L1fNxvuRSZSrrXov4ElXrszITnZZRrbViToLOjiNCZdoyW7jUCzQ0Q6JDRIBYh6vwTtGm3toUh1K9VnonIFJIugUhDvfg5JdpR1XO5Qbvy321AxirhAdf91jS4UOaRSdjTQ8Cg38_DHpU0-2hWf-FB0yXPGmzTBYErp5SPdDMIXY9FN88FRk-eQAIze9Oq5jq4r5oLs2JOQ"/>
      <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
    </div>
  </div>
</div>
</header>

<div class="p-4 md:p-8 space-y-8">

<!-- =============================================
     SECTION 1 — EXISTING REGISTRATIONS (Purana)
     ============================================= -->

<!-- Divider Label -->
<div class="flex items-center gap-3">
  <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
  <span class="text-xs font-bold uppercase tracking-widest text-slate-400 px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-full">
    Online Registrations & Payments
  </span>
  <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
</div>

<!-- Stats Grid — Existing -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
  <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col gap-4">
    <div class="p-3 rounded-lg bg-blue-50 text-blue-600 w-fit"><span class="material-symbols-outlined">group</span></div>
    <div>
      <p class="text-slate-500 text-sm font-medium">Total Registrations</p>
      <h3 class="text-2xl font-bold"><?= number_format($stats['total_registrations']) ?></h3>
    </div>
  </div>
  <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col gap-4">
    <div class="p-3 rounded-lg bg-teal-50 text-teal-600 w-fit"><span class="material-symbols-outlined">account_balance_wallet</span></div>
    <div>
      <p class="text-slate-500 text-sm font-medium">Online Payments</p>
      <h3 class="text-2xl font-bold"><?= number_format($stats['online_payments']) ?></h3>
    </div>
  </div>
  <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col gap-4">
    <div class="p-3 rounded-lg bg-slate-100 text-slate-600 w-fit"><span class="material-symbols-outlined">payments</span></div>
    <div>
      <p class="text-slate-500 text-sm font-medium">Offline Payments</p>
      <h3 class="text-2xl font-bold"><?= number_format($stats['offline_payments']) ?></h3>
    </div>
  </div>
  <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col gap-4">
    <div class="p-3 rounded-lg bg-emerald-50 text-emerald-600 w-fit"><span class="material-symbols-outlined">monetization_on</span></div>
    <div>
      <p class="text-slate-500 text-sm font-medium">Total Revenue</p>
      <?php $gross = $stats['total_revenue'] ?? 0; $net = $gross * 0.95; ?>
      <h3 class="text-2xl font-bold">₹<?= number_format($net, 2) ?></h3>
      <p class="text-[11px] text-slate-400 mt-1">After 5% deduction (Gross: ₹<?= number_format($gross, 2) ?>)</p>
    </div>
  </div>
</div>

<!-- Filters — Existing -->
<section class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<form method="GET" action="">
  <div class="flex flex-col lg:flex-row lg:items-end gap-4">
    <div class="flex-1">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Search Students</label>
      <input name="search" value="<?= htmlspecialchars($filters['search']) ?>"
             class="w-full px-4 py-2.5 bg-background-light dark:bg-slate-800 border rounded-lg text-sm"
             placeholder="Name, Email or ID..." type="text"/>
    </div>
    <div class="w-48">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Program</label>
      <select name="program" class="w-full px-4 py-2.5 border rounded-lg text-sm">
        <option value="">All Programs</option>
        <?php foreach ($programs as $p): ?>
          <option value="<?= htmlspecialchars($p) ?>" <?= $filters['program']===$p?'selected':'' ?>><?= htmlspecialchars($p) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="w-48">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
      <select name="payment_status" class="w-full px-4 py-2.5 border rounded-lg text-sm">
        <option value="">All</option>
        <option value="PAID" <?= $filters['payment_status']==='PAID'?'selected':'' ?>>Online/Paid</option>
        <option value="OFFLINE" <?= $filters['payment_status']==='OFFLINE'?'selected':'' ?>>Offline</option>
      </select>
    </div>
    <div class="w-40">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2">From</label>
      <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>" class="w-full px-4 py-2.5 border rounded-lg text-sm"/>
    </div>
    <div class="w-40">
      <label class="block text-xs font-bold text-slate-500 uppercase mb-2">To</label>
      <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>" class="w-full px-4 py-2.5 border rounded-lg text-sm"/>
    </div>
    <div>
      <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-bold">Filter</button>
    </div>
  </div>
</form>
</section>

<!-- Table — Existing Registrations -->
<section class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
<div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
  <h3 class="text-lg font-bold text-slate-800 dark:text-white">Recent Registrations</h3>
  <a href="export-csv.php?<?= http_build_query($filters) ?>"
     class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">download</span> Export CSV
  </a>
</div>
<div class="overflow-auto max-h-[600px] relative">
<table class="w-full text-left border-collapse min-w-[1200px]">
  <thead class="bg-slate-100 dark:bg-slate-800 sticky top-0 z-10 shadow-sm whitespace-nowrap">
    <tr>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Reg ID</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Student Name</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Contact</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Program</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Counselor</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Payment</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Amount</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Date</th>
      <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
    </tr>
  </thead>
  <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    <?php if (count($registrations) > 0): ?>
      <?php foreach ($registrations as $reg): ?>
      <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
        <td class="px-6 py-4 text-sm font-bold text-slate-400"><?= htmlspecialchars($reg['registration_id']) ?></td>
        <td class="px-6 py-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
              <?= strtoupper(substr($reg['first_name'],0,1)) ?>
            </div>
            <span class="text-sm font-semibold"><?= htmlspecialchars($reg['first_name'].' '.$reg['last_name']) ?></span>
          </div>
        </td>
        <td class="px-6 py-4">
          <p class="text-sm"><a target="_blank" href="mailto:<?= htmlspecialchars($reg['email']) ?>"><?= htmlspecialchars($reg['email']) ?></a></p>
          <p class="text-xs text-slate-400"><a target="_blank" href="tel:<?= htmlspecialchars($reg['phone']) ?>"><?= htmlspecialchars($reg['phone']) ?></a></p>
        </td>
        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($reg['program']) ?></td>
        <td class="px-6 py-4">
          <?php
            $saved_counselor = $reg['counselor_name'] ?? 'Direct / Self';
            $predefined_counselors = ["Direct / Self", "Nitin", "Ashutosh", "Kanchan"];
            $is_custom_counselor = !empty($saved_counselor) && !in_array($saved_counselor, $predefined_counselors);
          ?>
          <select onchange="updateCounselor('<?= $reg['registration_id'] ?>', this.value)" 
                  data-prev="<?= htmlspecialchars($saved_counselor) ?>"
                  class="px-2 py-1 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-primary text-slate-700 dark:text-slate-200">
            <?php foreach ($predefined_counselors as $pc): ?>
              <option value="<?= $pc ?>" <?= ($saved_counselor === $pc) ? 'selected' : '' ?>><?= $pc ?></option>
            <?php endforeach; ?>
            <?php if ($is_custom_counselor): ?>
              <option value="<?= htmlspecialchars($saved_counselor) ?>" selected><?= htmlspecialchars($saved_counselor) ?></option>
            <?php endif; ?>
            <option value="Custom_Prompt">+ Add Custom...</option>
          </select>
        </td>
        <td class="px-6 py-4">
          <?php if ($reg['payment_status'] === 'SUCCESS'): ?>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[11px] font-bold">PAID</span>
          <?php elseif ($reg['payment_mode'] === 'OFFLINE'): ?>
            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-bold">OFFLINE</span>
          <?php else: ?>
            <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[11px] font-bold"><?= $reg['payment_status'] ?: 'PENDING' ?></span>
          <?php endif; ?>
        </td>
        <td class="px-6 py-4 text-sm font-semibold">
          <?= (!empty($reg['amount']) && $reg['amount'] > 0) ? '₹'.number_format($reg['amount'],2) : '<span class="text-slate-400">—</span>' ?>
        </td>
        <td class="px-6 py-4 text-sm"><?= date('d M Y', strtotime($reg['created_at'])) ?></td>
        <td class="px-6 py-4 text-right whitespace-nowrap">
          <div class="flex items-center justify-end gap-2">
            <a href="add_admission.php?reg_id=<?= $reg['registration_id'] ?>" class="text-xs bg-purple-100 text-purple-600 px-3 py-1.5 rounded-lg hover:bg-purple-200 font-bold transition-colors">
            Convert
          </a>
          <a href="download_registration_pdf.php?id=<?= $reg['registration_id'] ?>" target="_blank" class="text-slate-400 hover:text-emerald-500 p-1.5" title="Download Registration PDF">
            <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
          </a>
          <a href="download_feeslip_pdf.php?id=<?= $reg['registration_id'] ?>" target="_blank" class="text-slate-400 hover:text-teal-500 p-1.5" title="Download Fee Slip">
            <span class="material-symbols-outlined text-xl">receipt_long</span>
          </a>
          <?php
            $waPhone = preg_replace('/[^0-9]/', '', $reg['phone']);
            // WhatsApp needs country code without +
            if (strlen($waPhone) === 10) $waPhone = '91' . $waPhone;
            
            // Build dynamic URL using server domain name and base path
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            $formUrl = $protocol . $domainName . BASE_URL . 'admission-form.php?reg_id=' . urlencode($reg['registration_id']);
            
            $waMsg = urlencode(
                "Namaste " . $reg['first_name'] . " ji! 🙏\n" .
                "LogixCode IT Solution mein aapka swagat hai.\n\n" .
                "Apna complete admission form details (bina fees details ke) bharne ke liye, neeche diye gaye link par click karke form submit karein:\n" .
                $formUrl . "\n\n" .
                "Koi bhi problem aane par humse contact karein. DHANYAWAD! 😊"
            );
          ?>
          <a href="https://wa.me/<?= $waPhone ?>?text=<?= $waMsg ?>" target="_blank"
             class="flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1.5 rounded-lg hover:bg-emerald-100 hover:text-emerald-800 hover:shadow-sm font-bold transition-all shrink-0" title="Send Admission Form via WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 shrink-0 text-emerald-600">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            WhatsApp
          </a>
          <button onclick="viewDetails('<?= $reg['registration_id'] ?>')" class="text-slate-400 hover:text-primary p-1.5 shrink-0" title="View Registration Details">
            <span class="material-symbols-outlined text-xl">visibility</span>
          </button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="8" class="text-center py-6 text-slate-400">No registrations found</td></tr>
    <?php endif; ?>
  </tbody>
</table>
</div>
</section>




</div><!-- end content -->

<!-- Footer -->
<footer class="mt-auto p-8 border-t border-slate-200 dark:border-slate-800 text-center">
  <p class="text-slate-400 text-xs font-medium uppercase tracking-widest">© 2026 LogixCode IT Solution • All Rights Reserved</p>
</footer>

</main>
</div>

<!-- View Details Modal (existing) -->
<div class="modal fade" id="detailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registration Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalContent">
        <div class="text-center"><div class="spinner-border" role="status"></div></div>
      </div>
    </div>
  </div>
</div>

<!-- Success/Error Toast Notification -->
<div id="counselorToast" class="fixed bottom-5 right-5 z-50 transform translate-y-20 opacity-0 pointer-events-none transition-all duration-300 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg font-bold flex items-center gap-2 text-sm">
  <span class="material-symbols-outlined text-white">check_circle</span>
  <span id="counselorToastMessage">Counselor updated successfully!</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewDetails(registrationId) {
  const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
  modal.show();
  fetch('view-registration.php?id=' + registrationId)
    .then(r => r.text())
    .then(html => { document.getElementById('modalContent').innerHTML = html; })
    .catch(() => { document.getElementById('modalContent').innerHTML = '<div class="alert alert-danger">Error loading details</div>'; });
}

function updateCounselor(registrationId, value) {
  let selectEl = event.target;
  let prevValue = selectEl.getAttribute('data-prev') || 'Direct / Self';
  
  if (value === 'Custom_Prompt') {
    let customName = prompt("Enter Custom Counselor Name:");
    if (customName && customName.trim() !== '') {
      customName = customName.trim();
      // Add custom option to select and set as selected
      let opt = document.createElement('option');
      opt.value = customName;
      opt.textContent = customName;
      opt.selected = true;
      selectEl.insertBefore(opt, selectEl.options[selectEl.options.length - 1]);
      value = customName;
    } else {
      // Revert selection
      selectEl.value = prevValue;
      return;
    }
  }
  
  // Save value in case of success
  selectEl.setAttribute('data-prev', value);

  // Send AJAX request
  let formData = new FormData();
  formData.append('registration_id', registrationId);
  formData.append('counselor_name', value);

  fetch('update-registration-counselor.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showToast(data.message, 'success');
    } else {
      showToast(data.message || 'Error updating counselor', 'error');
      selectEl.value = prevValue;
    }
  })
  .catch(err => {
    showToast('Failed to connect to server', 'error');
    selectEl.value = prevValue;
  });
}

function showToast(msg, type = 'success') {
  const toast = document.getElementById('counselorToast');
  const toastMsg = document.getElementById('counselorToastMessage');
  
  toastMsg.textContent = msg;
  if (type === 'success') {
    toast.className = toast.className.replace('bg-rose-600', 'bg-emerald-600');
  } else {
    toast.className = toast.className.replace('bg-emerald-600', 'bg-rose-600');
  }
  
  // Slide up and fade in
  toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
  
  setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
  }, 3000);
}
</script>
</body>
</html>
