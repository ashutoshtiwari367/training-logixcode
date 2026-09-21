<?php
/**
 * Admin Panel - Edit Assessment & Questions
 * admin/edit-assessment.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../assessment/init_db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: assessments.php');
    exit;
}

$message = '';
$error = '';

// Fetch existing assessment
$stmt = $pdo->prepare("SELECT * FROM assessments WHERE id = ?");
$stmt->execute([$id]);
$assessment = $stmt->fetch();

if (!$assessment) {
    header('Location: assessments.php');
    exit;
}

// Fetch existing questions
$qStmt = $pdo->prepare("SELECT * FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC");
$qStmt->execute([$id]);
$existingQuestions = $qStmt->fetchAll();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title            = trim($_POST['title'] ?? '');
    $course           = trim($_POST['course'] ?? '');
    $duration_minutes = (int)($_POST['duration_minutes'] ?? 15);
    $pass_percentage  = (float)($_POST['pass_percentage'] ?? 50.00);
    $status           = $_POST['status'] ?? 'active';

    $questions = $_POST['questions'] ?? [];

    if (empty($title) || empty($course) || empty($questions)) {
        $error = "Please provide an assessment title, course, and at least one question.";
    } else {
        try {
            $pdo->beginTransaction();

            // Update assessment record
            $uStmt = $pdo->prepare("UPDATE assessments SET title = ?, course = ?, duration_minutes = ?, pass_percentage = ?, status = ? WHERE id = ?");
            $uStmt->execute([$title, $course, $duration_minutes, $pass_percentage, $status, $id]);

            // Clear old questions and insert new set
            $delStmt = $pdo->prepare("DELETE FROM assessment_questions WHERE assessment_id = ?");
            $delStmt->execute([$id]);

            $insStmt = $pdo->prepare("INSERT INTO assessment_questions (assessment_id, question, option_a, option_b, option_c, option_d, correct_option, marks) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

            foreach ($questions as $q) {
                $qText   = trim($q['question'] ?? '');
                $optA    = trim($q['option_a'] ?? '');
                $optB    = trim($q['option_b'] ?? '');
                $optC    = trim($q['option_c'] ?? '');
                $optD    = trim($q['option_d'] ?? '');
                $correct = strtoupper(trim($q['correct_option'] ?? 'A'));

                if (!empty($qText) && !empty($optA) && !empty($optB)) {
                    $insStmt->execute([$id, $qText, $optA, $optB, $optC, $optD, $correct]);
                }
            }

            $pdo->commit();
            $message = "Assessment updated successfully!";

            // Refresh data
            $stmt->execute([$id]);
            $assessment = $stmt->fetch();
            $qStmt->execute([$id]);
            $existingQuestions = $qStmt->fetchAll();

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to update assessment: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assessment | Admin Panel</title>
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
                    <h1 class="text-2xl font-bold text-slate-900">Edit Assessment Test</h1>
                    <p class="text-slate-500 text-sm">Modify test settings, status, and questions for #<?php echo $assessment['id']; ?>.</p>
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
            <form method="POST" action="edit-assessment.php?id=<?php echo $id; ?>" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
                
                <!-- Assessment Settings -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-600">settings</span> Test Settings
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Assessment Title *</label>
                            <input type="text" name="title" required value="<?php echo htmlspecialchars($assessment['title']); ?>"
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Associated Course *</label>
                            <input type="text" name="course" required value="<?php echo htmlspecialchars($assessment['course']); ?>"
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Duration (Minutes)</label>
                            <input type="number" name="duration_minutes" value="<?php echo $assessment['duration_minutes']; ?>" min="1" max="180" required
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pass Percentage (%)</label>
                            <input type="number" name="pass_percentage" value="<?php echo (int)$assessment['pass_percentage']; ?>" min="10" max="100" step="5" required
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                            <select name="status" class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm font-semibold">
                                <option value="active" <?php echo ($assessment['status'] === 'active') ? 'selected' : ''; ?>>Active (Visible to Students)</option>
                                <option value="inactive" <?php echo ($assessment['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Questions Container -->
                <div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sky-600">quiz</span> Edit Assessment Questions
                        </h3>
                        <button type="button" onclick="addQuestionBlock()" 
                                class="px-3.5 py-2 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200 text-xs font-bold flex items-center gap-1 transition-all">
                            <span class="material-symbols-outlined text-sm">add</span> Add Question
                        </button>
                    </div>

                    <div id="questionsContainer" class="space-y-6">
                        <?php foreach ($existingQuestions as $idx => $q): ?>
                        <div class="q-block bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 text-sm">Question #<?php echo ($idx + 1); ?></span>
                                <button type="button" onclick="this.closest('.q-block').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">delete</span> Delete
                                </button>
                            </div>
                            <input type="text" name="questions[<?php echo $idx; ?>][question]" required 
                                   value="<?php echo htmlspecialchars($q['question']); ?>"
                                   class="w-full rounded-xl border border-slate-200 py-3 px-4 text-sm focus:ring-sky-500 focus:border-sky-500 font-medium">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="text" name="questions[<?php echo $idx; ?>][option_a]" required value="<?php echo htmlspecialchars($q['option_a']); ?>" class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[<?php echo $idx; ?>][option_b]" required value="<?php echo htmlspecialchars($q['option_b']); ?>" class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[<?php echo $idx; ?>][option_c]" required value="<?php echo htmlspecialchars($q['option_c']); ?>" class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                                <input type="text" name="questions[<?php echo $idx; ?>][option_d]" required value="<?php echo htmlspecialchars($q['option_d']); ?>" class="rounded-xl border border-slate-200 py-2.5 px-4 text-xs">
                            </div>

                            <div class="pt-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Correct Answer Option *</label>
                                <select name="questions[<?php echo $idx; ?>][correct_option]" class="rounded-xl border border-slate-200 py-2 px-4 text-xs font-bold">
                                    <option value="A" <?php echo ($q['correct_option'] === 'A') ? 'selected' : ''; ?>>Option A</option>
                                    <option value="B" <?php echo ($q['correct_option'] === 'B') ? 'selected' : ''; ?>>Option B</option>
                                    <option value="C" <?php echo ($q['correct_option'] === 'C') ? 'selected' : ''; ?>>Option C</option>
                                    <option value="D" <?php echo ($q['correct_option'] === 'D') ? 'selected' : ''; ?>>Option D</option>
                                </select>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex gap-4">
                    <button type="submit" 
                            class="flex-1 py-4 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-base shadow-lg transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-xl">save</span> Update & Save Changes
                    </button>
                    <a href="assessments.php" 
                       class="py-4 px-6 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-base transition-all text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </main>
    </div>

    <script>
        let qIndex = <?php echo count($existingQuestions); ?>;
        function addQuestionBlock() {
            const container = document.getElementById('questionsContainer');
            const block = document.createElement('div');
            block.className = 'q-block bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-4';
            block.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-sm">Question #${qIndex + 1}</span>
                    <button type="button" onclick="this.closest('.q-block').remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">delete</span> Delete
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
