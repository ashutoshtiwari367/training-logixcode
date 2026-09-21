<?php
/**
 * Admin Panel - Assessment Portal Dashboard & Results
 * admin/assessments.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../assessment/init_db.php';

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $filterTestId = (int)($_GET['test_id'] ?? 0);
    
    $whereClause = "";
    $params = [];
    if ($filterTestId > 0) {
        $whereClause = " WHERE r.assessment_id = ? ";
        $params[] = $filterTestId;
    }

    $stmt = $pdo->prepare("
        SELECT r.id, a.title as test_title, r.student_name, r.mobile, r.email, r.course, r.year, r.college_name,
               r.score, r.total_questions, r.percentage, r.status, r.completed_at
        FROM assessment_attempts r
        JOIN assessments a ON r.assessment_id = a.id
        {$whereClause}
        ORDER BY r.id DESC
    ");
    $stmt->execute($params);
    $attempts = $stmt->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=assessment_results_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Attempt ID', 'Assessment Test', 'Candidate Name', 'Mobile', 'Email', 'Course', 'Year', 'College Name', 'Score', 'Total Questions', 'Percentage (%)', 'Status', 'Date Completed']);
    
    foreach ($attempts as $row) {
        fputcsv($output, [
            $row['id'],
            $row['test_title'],
            $row['student_name'],
            $row['mobile'],
            $row['email'],
            $row['course'],
            $row['year'],
            $row['college_name'],
            $row['score'],
            $row['total_questions'],
            $row['percentage'],
            $row['status'],
            $row['completed_at']
        ]);
    }
    fclose($output);
    exit;
}

// Stats & Data Processing
$filterTest = (int)($_GET['test_id'] ?? 0);
$search = trim($_GET['search'] ?? '');

// Overall Stats
$totalAssessments = $pdo->query("SELECT COUNT(*) FROM assessments")->fetchColumn();
$totalAttempts = $pdo->query("SELECT COUNT(*) FROM assessment_attempts")->fetchColumn();
$totalPassed = $pdo->query("SELECT COUNT(*) FROM assessment_attempts WHERE status = 'PASS'")->fetchColumn();
$overallPassRate = ($totalAttempts > 0) ? round(($totalPassed / $totalAttempts) * 100, 1) : 0;

// Fetch Assessment Tests with attempt statistics
$testsStmt = $pdo->query("
    SELECT a.*, 
           COUNT(q.id) as total_q, 
           COUNT(r.id) as attempt_count,
           SUM(CASE WHEN r.status = 'PASS' THEN 1 ELSE 0 END) as pass_count
    FROM assessments a
    LEFT JOIN assessment_questions q ON a.id = q.assessment_id
    LEFT JOIN assessment_attempts r ON a.id = r.assessment_id
    GROUP BY a.id
    ORDER BY a.id ASC
");
$assessmentsList = $testsStmt->fetchAll();

// Build Candidate Attempts Query
$attemptWhere = [];
$attemptParams = [];

if ($filterTest > 0) {
    $attemptWhere[] = "r.assessment_id = ?";
    $attemptParams[] = $filterTest;
}

if (!empty($search)) {
    $attemptWhere[] = "(r.student_name LIKE ? OR r.mobile LIKE ? OR r.email LIKE ? OR r.college_name LIKE ?)";
    $term = "%{$search}%";
    $attemptParams = array_merge($attemptParams, [$term, $term, $term, $term]);
}

$whereSql = !empty($attemptWhere) ? " WHERE " . implode(" AND ", $attemptWhere) : "";

$attemptsQuery = "
    SELECT r.*, a.title as test_title 
    FROM assessment_attempts r 
    JOIN assessments a ON r.assessment_id = a.id 
    {$whereSql} 
    ORDER BY r.id DESC
";
$aStmt = $pdo->prepare($attemptsQuery);
$aStmt->execute($attemptParams);
$attemptsList = $aStmt->fetchAll();

$currentTestTitle = "";
if ($filterTest > 0) {
    foreach ($assessmentsList as $t) {
        if ($t['id'] == $filterTest) {
            $currentTestTitle = $t['title'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Dashboard | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">

    <!-- Mobile Navigation Bar -->
    <div class="lg:hidden bg-slate-900 text-white p-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[#03c4ce]">quiz</span>
            <span class="font-bold">Assessment Dashboard</span>
        </div>
        <button id="mobileMenuBtn" class="text-white focus:outline-none">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar Include -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="flex-1 lg:ml-72 p-4 sm:p-6 lg:p-8 space-y-8">
            
            <!-- Dashboard Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Assessment Portal Dashboard</h1>
                    <p class="text-slate-500 text-sm">View and manage online assessments, candidate results, and quiz scores.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="add-assessment.php" class="px-4 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        <span>Create New Assessment</span>
                    </a>
                </div>
            </div>

            <!-- Stats Overview Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Assessments Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-400">Total Assessments</span>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1"><?php echo $totalAssessments; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">quiz</span>
                    </div>
                </div>

                <!-- Total Quiz Attempts -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-400">Total Attempts</span>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1"><?php echo $totalAttempts; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">groups</span>
                    </div>
                </div>

                <!-- Passed Candidates -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-400">Passed Candidates</span>
                        <h3 class="text-3xl font-extrabold text-emerald-600 mt-1"><?php echo $totalPassed; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">verified</span>
                    </div>
                </div>

                <!-- Overall Pass Rate -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-400">Overall Pass Rate</span>
                        <h3 class="text-3xl font-extrabold text-slate-900 mt-1"><?php echo $overallPassRate; ?>%</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">insights</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: Assessment Tests Breakdown Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-4 p-6">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sky-600">assignment</span>
                            Assessments Overview & Test Wise Data
                        </h2>
                        <p class="text-xs text-slate-500">Each assessment data is separated and filtered per quiz test.</p>
                    </div>
                    <?php if ($filterTest > 0): ?>
                    <a href="assessments.php" class="text-xs font-semibold text-sky-600 hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">filter_alt_off</span> Clear Test Filter
                    </a>
                    <?php endif; ?>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-3">Assessment Title</th>
                                <th class="p-3">Course</th>
                                <th class="p-3">Questions</th>
                                <th class="p-3">Duration</th>
                                <th class="p-3">Pass %</th>
                                <th class="p-3">Attempts</th>
                                <th class="p-3">Pass Rate</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php foreach ($assessmentsList as $testItem): 
                                $tAttempts = $testItem['attempt_count'];
                                $tPasses = $testItem['pass_count'];
                                $tRate = ($tAttempts > 0) ? round(($tPasses / $tAttempts) * 100, 1) : 0;
                                $isSelected = ($filterTest == $testItem['id']);
                            ?>
                            <tr class="<?php echo $isSelected ? 'bg-sky-50/70 border-l-4 border-l-sky-500' : 'hover:bg-slate-50/50'; ?>">
                                <td class="p-3 font-bold text-slate-900">
                                    <?php echo htmlspecialchars($testItem['title']); ?>
                                </td>
                                <td class="p-3 text-xs">
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold">
                                        <?php echo htmlspecialchars($testItem['course']); ?>
                                    </span>
                                </td>
                                <td class="p-3"><?php echo $testItem['total_q']; ?> MCQs</td>
                                <td class="p-3"><?php echo $testItem['duration_minutes']; ?> mins</td>
                                <td class="p-3"><?php echo (int)$testItem['pass_percentage']; ?>%</td>
                                <td class="p-3 font-bold text-slate-800"><?php echo $tAttempts; ?></td>
                                <td class="p-3">
                                    <span class="text-xs font-bold text-emerald-600"><?php echo $tRate; ?>%</span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="assessments.php?test_id=<?php echo $testItem['id']; ?>" 
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold <?php echo $isSelected ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-sky-100 hover:text-sky-700'; ?> transition-all inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">visibility</span> View Submissions
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 2: Candidate Attempts Results Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600">group</span>
                            Candidate Quiz Submissions
                            <?php if (!empty($currentTestTitle)): ?>
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-700 font-semibold">Filter: <?php echo htmlspecialchars($currentTestTitle); ?></span>
                            <?php endif; ?>
                        </h2>
                        <p class="text-xs text-slate-500">Student registration data and MCQ scores for assessment attempts.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Search Form -->
                        <form method="GET" action="assessments.php" class="flex items-center gap-2">
                            <?php if ($filterTest > 0): ?>
                            <input type="hidden" name="test_id" value="<?php echo $filterTest; ?>">
                            <?php endif; ?>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   placeholder="Search student, mobile, college..." 
                                   class="px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-sky-500 focus:border-sky-500 w-48 sm:w-64">
                            <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700">
                                <span class="material-symbols-outlined text-base">search</span>
                            </button>
                        </form>

                        <!-- CSV Export Button -->
                        <a href="assessments.php?export=csv<?php echo $filterTest > 0 ? '&test_id=' . $filterTest : ''; ?>" 
                           class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-sm transition-all">
                            <span class="material-symbols-outlined text-sm">download</span> Export CSV
                        </a>
                    </div>
                </div>

                <!-- Attempts Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-3">ID</th>
                                <th class="p-3">Candidate Name</th>
                                <th class="p-3">Mobile & Email</th>
                                <th class="p-3">Course & Year</th>
                                <th class="p-3">College Name</th>
                                <th class="p-3">Assessment Test</th>
                                <th class="p-3">Score</th>
                                <th class="p-3">Percentage</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Completed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-xs">
                            <?php if (empty($attemptsList)): ?>
                            <tr>
                                <td colspan="10" class="p-6 text-center text-slate-400">
                                    No assessment attempts found for the selected criteria.
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($attemptsList as $item): ?>
                            <tr class="hover:bg-slate-50/70">
                                <td class="p-3 text-slate-400 font-mono">#<?php echo $item['id']; ?></td>
                                <td class="p-3 font-bold text-slate-900"><?php echo htmlspecialchars($item['student_name']); ?></td>
                                <td class="p-3 space-y-0.5">
                                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($item['mobile']); ?></div>
                                    <div class="text-[11px] text-slate-400"><?php echo htmlspecialchars($item['email']); ?></div>
                                </td>
                                <td class="p-3">
                                    <div class="font-semibold text-slate-800"><?php echo htmlspecialchars($item['course']); ?></div>
                                    <div class="text-[11px] text-slate-400"><?php echo htmlspecialchars($item['year']); ?></div>
                                </td>
                                <td class="p-3 font-semibold text-slate-700"><?php echo htmlspecialchars($item['college_name']); ?></td>
                                <td class="p-3 font-bold text-sky-700"><?php echo htmlspecialchars($item['test_title']); ?></td>
                                <td class="p-3 font-extrabold text-slate-900"><?php echo $item['score']; ?> / <?php echo $item['total_questions']; ?></td>
                                <td class="p-3 font-bold text-slate-800"><?php echo number_format($item['percentage'], 1); ?>%</td>
                                <td class="p-3">
                                    <?php if ($item['status'] === 'PASS'): ?>
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px]">PASS</span>
                                    <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 font-bold text-[10px]">FAIL</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-slate-500 whitespace-nowrap"><?php echo date('d M Y, h:i A', strtotime($item['completed_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>

    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
</body>
</html>
