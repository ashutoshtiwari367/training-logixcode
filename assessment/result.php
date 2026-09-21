<?php
/**
 * Student Assessment Portal - Instant Quiz Result & Report
 * assessment/result.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';

$attempt_id = (int)($_GET['id'] ?? ($_SESSION['quiz_result_id'] ?? 0));

if (!$attempt_id) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT r.*, a.title as assessment_title, a.pass_percentage 
        FROM assessment_attempts r 
        JOIN assessments a ON r.assessment_id = a.id 
        WHERE r.id = ?
    ");
    $stmt->execute([$attempt_id]);
    $result = $stmt->fetch();

    if (!$result) {
        throw new Exception("Assessment result record not found.");
    }

    $reviewData = json_decode($result['answers_json'], true) ?: [];
} catch (Exception $e) {
    die("Result Error: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result | <?php echo INSTITUTE_NAME; ?></title>
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
                "brand-cyan": "#03c4ce",
                "brand-dark": "#004f54",
                "primary-container": "#03bfd3",
              },
              fontFamily: {
                sans: ['Inter', 'Manrope', 'sans-serif'],
                heading: ['Manrope', 'sans-serif'],
              }
            }
          }
        }
    </script>
    <style>
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            body {
                padding-top: 0 !important;
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Result Header Banner -->
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center relative overflow-hidden">
                <?php if ($result['status'] === 'PASS'): ?>
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mb-4 animate-bounce">
                    <span class="material-symbols-outlined text-4xl">workspace_premium</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-600 font-heading mb-2">
                    Congratulations! Assessment Passed
                </h1>
                <p class="text-slate-600 text-base max-w-lg mx-auto">
                    Great job, <strong class="text-slate-900"><?php echo htmlspecialchars($result['student_name']); ?></strong>! You have successfully passed the test.
                </p>
                <?php else: ?>
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-100 text-rose-600 mb-4">
                    <span class="material-symbols-outlined text-4xl">error</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-rose-600 font-heading mb-2">
                    Assessment Result: Needs Improvement
                </h1>
                <p class="text-slate-600 text-base max-w-lg mx-auto">
                    Keep practicing, <strong class="text-slate-900"><?php echo htmlspecialchars($result['student_name']); ?></strong>! Review your answers below and try again.
                </p>
                <?php endif; ?>
            </div>

            <!-- Score Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Score Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 text-center">
                    <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Total Score</span>
                    <span class="text-4xl font-extrabold text-slate-900">
                        <?php echo $result['score']; ?> <span class="text-xl text-slate-400">/ <?php echo $result['total_questions']; ?></span>
                    </span>
                </div>

                <!-- Percentage Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 text-center">
                    <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Percentage</span>
                    <span class="text-4xl font-extrabold text-[#03c4ce]">
                        <?php echo number_format($result['percentage'], 1); ?>%
                    </span>
                </div>

                <!-- Status Card -->
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 text-center">
                    <span class="text-xs font-semibold uppercase text-slate-500 block mb-1">Final Status</span>
                    <?php if ($result['status'] === 'PASS'): ?>
                    <span class="inline-block px-4 py-1 rounded-full bg-emerald-100 text-emerald-700 font-extrabold text-xl mt-1">
                        PASS
                    </span>
                    <?php else: ?>
                    <span class="inline-block px-4 py-1 rounded-full bg-rose-100 text-rose-700 font-extrabold text-xl mt-1">
                        FAIL
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Candidate Details Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="material-symbols-outlined text-[#03c4ce]">badge</span>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Candidate Information</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">Assessment Test</span>
                        <span class="font-bold text-[#004f54]"><?php echo htmlspecialchars($result['assessment_title']); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">Student Name</span>
                        <span class="font-bold text-slate-900"><?php echo htmlspecialchars($result['student_name']); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">Mobile Number</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($result['mobile']); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">Email Address</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($result['email']); ?></span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">Course & Year</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($result['course']); ?> (<?php echo htmlspecialchars($result['year']); ?>)</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-500 font-semibold uppercase">College Name</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($result['college_name']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Detailed Question-by-Question Review -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 space-y-6">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="material-symbols-outlined text-[#03c4ce]">fact_check</span>
                    <h3 class="text-lg font-bold text-slate-900 font-heading">Question-by-Question Review</h3>
                </div>

                <div class="space-y-6">
                    <?php foreach ($reviewData as $idx => $item): ?>
                    <div class="p-5 rounded-2xl border <?php echo $item['is_correct'] ? 'border-emerald-200 bg-emerald-50/40' : 'border-rose-200 bg-rose-50/40'; ?> space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="font-bold text-slate-900 text-base">
                                Q<?php echo ($idx + 1); ?>. <?php echo htmlspecialchars($item['question']); ?>
                            </h4>
                            <?php if ($item['is_correct']): ?>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold shrink-0 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check</span> Correct (+1)
                            </span>
                            <?php else: ?>
                            <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-bold shrink-0 flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">close</span> Incorrect
                            </span>
                            <?php endif; ?>
                        </div>

                        <!-- Options Review Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                            <?php foreach ($item['options'] as $key => $optVal): 
                                $isGiven = ($item['given_answer'] === $key);
                                $isCorrectOpt = ($item['correct_answer'] === $key);
                                
                                $optClass = "bg-white text-slate-700 border-slate-200";
                                if ($isCorrectOpt) {
                                    $optClass = "bg-emerald-100 text-emerald-900 font-bold border-emerald-300";
                                } elseif ($isGiven && !$isCorrectOpt) {
                                    $optClass = "bg-rose-100 text-rose-900 font-bold border-rose-300";
                                }
                            ?>
                            <div class="p-3 rounded-xl border <?php echo $optClass; ?> flex items-center justify-between">
                                <span><?php echo $key; ?>) <?php echo htmlspecialchars($optVal); ?></span>
                                <?php if ($isCorrectOpt): ?>
                                <span class="text-[10px] uppercase font-bold text-emerald-700">✓ Correct</span>
                                <?php elseif ($isGiven): ?>
                                <span class="text-[10px] uppercase font-bold text-rose-700">Your Choice</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center no-print">
                <button onclick="window.print()" 
                        class="py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-lg">print</span>
                    <span>Print Result Report</span>
                </button>
                
                <a href="index.php" 
                   class="py-3.5 px-6 rounded-xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-bold text-sm shadow-lg shadow-[#03c4ce]/20 flex items-center justify-center gap-2 transition-all">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    <span>Take Another Assessment</span>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
