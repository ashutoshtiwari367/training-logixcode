<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

$currentUser = getCurrentUser();

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM admissions WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (student_name LIKE ? OR admission_id LIKE ? OR phone LIKE ?)";
    $s = "%$search%";
    $params[] = $s; $params[] = $s; $params[] = $s;
}
if ($status) {
    $sql .= " AND fee_status = ?";
    $params[] = $status;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$admissions = $stmt->fetchAll();

// Stats
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN fee_status='PAID' THEN 1 ELSE 0 END) as paid,
        SUM(CASE WHEN fee_status='PARTIAL' THEN 1 ELSE 0 END) as partial,
        SUM(CASE WHEN fee_status='PENDING' THEN 1 ELSE 0 END) as pending,
        SUM(paid_amount + registered_amount) as total_revenue
    FROM admissions
")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admissions Portal - LogixCode Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>body { font-family: 'Public Sans', sans-serif; background-color: #f8fafd; }</style>
</head>
<body class="flex min-h-screen">

<?php include "sidebar.php" ?>

<main class="flex-1 ml-72 min-h-screen flex flex-col p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Admission Portal</h2>
            <p class="text-slate-500 text-sm">Manage final student admissions and full fees</p>
        </div>
        <a href="add_admission.php" class="bg-purple-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-purple-700 flex items-center gap-2">
            <span class="material-symbols-outlined">person_add</span> Direct Admission
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total</p>
        <h3 class="text-2xl font-bold text-slate-800"><?= number_format($stats['total']) ?></h3>
      </div>
      <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Fully Paid</p>
        <h3 class="text-2xl font-bold text-emerald-600"><?= number_format($stats['paid']) ?></h3>
      </div>
      <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Partial</p>
        <h3 class="text-2xl font-bold text-amber-500"><?= number_format($stats['partial']) ?></h3>
      </div>
      <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pending</p>
        <h3 class="text-2xl font-bold text-red-500"><?= number_format($stats['pending']) ?></h3>
      </div>
      <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Revenue Collected</p>
        <h3 class="text-2xl font-bold text-purple-600">₹<?= number_format($stats['total_revenue'], 0) ?></h3>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">All Admissions</h3>
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, phone, ID..." class="px-4 py-2 border rounded-lg text-sm w-64">
                <select name="status" class="px-4 py-2 border rounded-lg text-sm">
                    <option value="">All Status</option>
                    <option value="PAID" <?= $status=='PAID'?'selected':'' ?>>Paid</option>
                    <option value="PARTIAL" <?= $status=='PARTIAL'?'selected':'' ?>>Partial</option>
                    <option value="PENDING" <?= $status=='PENDING'?'selected':'' ?>>Pending</option>
                </select>
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold text-sm">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Adm ID</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Student Name</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Contact</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Course</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Total</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Paid (Reg+New)</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Balance</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Status</th>
                  <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <?php if(count($admissions) > 0): ?>
                  <?php foreach($admissions as $adm): 
                      $badge = match($adm['fee_status']) {
                          'PAID' => 'bg-emerald-100 text-emerald-700',
                          'PARTIAL' => 'bg-amber-100 text-amber-700',
                          'PENDING' => 'bg-red-100 text-red-700',
                          default => 'bg-slate-100 text-slate-700'
                      };
                      $total_paid = $adm['registered_amount'] + $adm['paid_amount'];
                  ?>
                  <tr class="hover:bg-slate-50 transition-colors">
                      <td class="px-6 py-4 text-sm font-bold text-slate-400"><?= $adm['admission_id'] ?></td>
                      <td class="px-6 py-4">
                          <div class="flex items-center gap-3">
                              <?php if(!empty($adm['student_photo'])): ?>
                                  <img src="../uploads/photos/<?= htmlspecialchars($adm['student_photo']) ?>" class="w-9 h-9 rounded-full object-cover border-2 border-purple-100 shrink-0">
                              <?php else: ?>
                                  <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm shrink-0"><?= strtoupper(substr($adm['student_name'],0,1)) ?></div>
                              <?php endif; ?>
                              <span class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($adm['student_name']) ?></span>
                          </div>
                      </td>
                      <td class="px-6 py-4">
                          <div class="text-sm font-medium"><?= htmlspecialchars($adm['phone']) ?></div>
                          <div class="text-xs text-slate-400"><?= htmlspecialchars($adm['email']) ?></div>
                      </td>
                      <td class="px-6 py-4 text-sm"><?= htmlspecialchars($adm['course_name']) ?></td>
                      <td class="px-6 py-4 text-sm font-bold text-slate-700">₹<?= number_format($adm['total_fees'], 0) ?></td>
                      <td class="px-6 py-4 text-sm font-bold text-blue-600">₹<?= number_format($total_paid, 0) ?></td>
                      <td class="px-6 py-4 text-sm font-bold text-red-500">₹<?= number_format($adm['balance_amount'], 0) ?></td>
                      <td class="px-6 py-4">
                          <span class="px-3 py-1 rounded-full text-xs font-bold <?= $badge ?>"><?= $adm['fee_status'] ?></span>
                      </td>
                      <td class="px-6 py-4">
                          <div class="flex items-center gap-2">
                              <a href="edit_admission.php?id=<?= urlencode($adm['admission_id']) ?>" 
                                 class="flex items-center gap-1 text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 font-bold transition-colors" title="Edit Admission">
                                  <span class="material-symbols-outlined" style="font-size:15px;">edit</span> Edit
                              </a>
                              <a href="download_admission_pdf.php?id=<?= urlencode($adm['admission_id']) ?>" 
                                 class="flex items-center gap-1 text-xs bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg hover:bg-emerald-200 font-bold transition-colors" title="Download PDF">
                                  <span class="material-symbols-outlined" style="font-size:15px;">download</span> PDF
                              </a>
                          </div>
                      </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="9" class="text-center py-8 text-slate-400">No admissions found. <a href="add_admission.php" class="text-purple-600 font-bold">Add first admission →</a></td></tr>
                <?php endif; ?>
              </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
