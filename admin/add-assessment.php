<?php
/**
 * Admin Panel - Create New Assessment & Questions
 * admin/add-assessment.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../assessment/init_db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title            = trim($_POST['title'] ?? '');
    $course           = trim($_POST['course'] ?? '');
    $duration_minutes = (int)($_POST['duration_minutes'] ?? 15);
    $pass_percentage  = (float)($_POST['pass_percentage'] ?? 50.00);

    $questions = $_POST['questions'] ?? [];

    if (empty($title) || empty($course) || empty($questions)) {
        $error = "Please provide an assessment title, course, and at least one question.";
    } else {
        try {
            $pdo->beginTransaction();

            // Generate slug
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            $slug = $slug . '-' . time();

            // Insert assessment
            $aStmt = $pdo->prepare("INSERT INTO assessments (title, slug, course, duration_minutes, pass_percentage, status) VALUES (?, ?, ?, ?, ?, 'active')");
            $aStmt->execute([$title, $slug, $course, $duration_minutes, $pass_percentage]);
            $assessment_id = $pdo->lastInsertId();

            // Insert questions
            $qStmt = $pdo->prepare("INSERT INTO assessment_questions (assessment_id, question, option_a, option_b, option_c, option_d, correct_option, marks) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

            foreach ($questions as $q) {
                $qText   = trim($q['question'] ?? '');
                $optA    = trim($q['option_a'] ?? '');
                $optB    = trim($q['option_b'] ?? '');
                $optC    = trim($q['option_c'] ?? '');
                $optD    = trim($q['option_d'] ?? '');
                $correct = strtoupper(trim($q['correct_option'] ?? 'A'));

                if (!empty($qText) && !empty($optA) && !empty($optB)) {
                    $qStmt->execute([$assessment_id, $qText, $optA, $optB, $optC, $optD, $correct]);
                }
            }

            $pdo->commit();
            $message = "New Assessment Test created successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to create assessment: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assessment | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">

    <div class="flex min-h-screen">
        <!-- Sidebar Include -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="flex-1 lg:ml-72 p-4 sm:p-6 lg:p-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Create New Assessment</h1>
                    <p class="text-slate-500 text-sm">Add custom skill test quiz questions and configuration.</p>
                </div>
                <a href="assessments.php" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-sm font-semibold flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-base">arrow_back</span> Back to Assessments
                </a>
            </div>

            <?php if (!empty($message)): ?>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600">error</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <!-- Form Container -->
            <form method="POST" action="add-assessment.php" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                
                <!-- Assessment Settings -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-600">settings</span> Test Configuration
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Assessment Title *</label>
                            <input type="text" name="title" required placeholder="e.g. React & Frontend Mastery Assessment"
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Associated Course *</label>
                            <input type="text" name="course" required placeholder="e.g. Full Stack Development"
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Duration (Minutes)</label>
                            <input type="number" name="duration_minutes" value="15" min="1" max="180" required
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pass Percentage (%)</label>
                            <input type="number" name="pass_percentage" value="50" min="10" max="100" step="5" required
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>
                </div>

                <!-- Questions Container -->
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sky-600">quiz</span> Assessment Questions (MCQs)
                        </h3>
                        <button type="button" onclick="addQuestionBlock()" 
                                class="px-3.5 py-2 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200 text-xs font-bold flex items-center gap-1 transition-all">
                            <span class="material-symbols-outlined text-sm">add</span> Add Question
                        </button>
                    </div>

                    <div id="questionsContainer" class="space-y-6">
                        <!-- Question Block 1 -->
                        <div class="q-block bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">Question #1</span>
                            </div>
                            <input type="text" name="questions[0][question]" required placeholder="Enter question prompt..."
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500 font-medium">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="text" name="questions[0][option_a]" required placeholder="Option A..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[0][option_b]" required placeholder="Option B..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[0][option_c]" required placeholder="Option C..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[0][option_d]" required placeholder="Option D..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                            </div>

                            <div class="pt-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Correct Answer Option *</label>
                                <select name="questions[0][correct_option]" class="rounded-xl border border-slate-200 py-2 px-4 text-xs font-bold">
                                    <option value="A">Option A</option>
                                    <option value="B">Option B</option>
                                    <option value="C">Option C</option>
                                    <option value="D">Option D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-base shadow-lg transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl">save</span> Save & Publish Assessment Test
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        let qIndex = 1;
        function addQuestionBlock() {
            const container = document.getElementById('questionsContainer');
            const block = document.createElement('div');
            block.className = 'q-block bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4';
            block.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Question #${qIndex + 1}</span>
                    <button type="button" onclick="this.closest('.q-block').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete</span> Remove
                    </button>
                </div>
                <input type="text" name="questions[${qIndex}][question]" required placeholder="Enter question prompt..."
                       class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500 font-medium">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="questions[${qIndex}][option_a]" required placeholder="Option A..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                    <input type="text" name="questions[${qIndex}][option_b]" required placeholder="Option B..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                    <input type="text" name="questions[${qIndex}][option_c]" required placeholder="Option C..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                    <input type="text" name="questions[${qIndex}][option_d]" required placeholder="Option D..." class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                </div>

                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Correct Answer Option *</label>
                    <select name="questions[${qIndex}][correct_option]" class="rounded-xl border border-slate-200 py-2 px-4 text-xs font-bold">
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>
            `;
            container.appendChild(block);
            qIndex++;
        }
    </script>
</body>
</html>
