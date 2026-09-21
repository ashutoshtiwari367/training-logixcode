<?php
/**
 * Student Assessment Portal - Interactive Quiz Runner
 * assessment/quiz.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';

// Check if student session exists
if (!isset($_SESSION['quiz_student'])) {
    header('Location: index.php');
    exit;
}

$student = $_SESSION['quiz_student'];
$assessment_id = (int)($student['assessment_id'] ?? 0);

// Fetch assessment info
try {
    $stmt = $pdo->prepare("SELECT * FROM assessments WHERE id = ? AND status = 'active'");
    $stmt->execute([$assessment_id]);
    $assessment = $stmt->fetch();

    if (!$assessment) {
        throw new Exception("Assessment test not found.");
    }

    // Fetch questions
    $qStmt = $pdo->prepare("SELECT id, question, option_a, option_b, option_c, option_d FROM assessment_questions WHERE assessment_id = ? ORDER BY id ASC");
    $qStmt->execute([$assessment_id]);
    $questions = $qStmt->fetchAll();

    if (empty($questions)) {
        throw new Exception("No questions available for this assessment.");
    }
} catch (Exception $e) {
    die("Assessment Error: " . htmlspecialchars($e->getMessage()));
}

$durationMinutes = $assessment['duration_minutes'] ?? 15;
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($assessment['title']); ?> | Quiz</title>
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
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col pt-20">

    <!-- Header Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Sticky Timer & Candidate Bar -->
    <div class="sticky top-20 z-40 bg-slate-900 text-white shadow-lg border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between text-sm">
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-0.5 rounded-full bg-[#03c4ce]/20 text-[#03c4ce] font-bold text-xs">
                    Candidate: <?php echo htmlspecialchars($student['student_name']); ?>
                </span>
                <span class="hidden sm:inline text-slate-400">| <?php echo htmlspecialchars($student['college_name']); ?></span>
            </div>
            <div class="flex items-center gap-2 bg-rose-500/20 text-rose-300 px-3 py-1 rounded-full font-bold font-mono">
                <span class="material-symbols-outlined text-base">timer</span>
                <span id="timerDisplay">15:00</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Quiz Header -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading mb-2">
                    <?php echo htmlspecialchars($assessment['title']); ?>
                </h1>
                <p class="text-slate-600 text-sm">
                    Answer all <?php echo count($questions); ?> questions carefully. Do not refresh or close the page while taking the quiz.
                </p>
            </div>

            <!-- Quiz Form -->
            <form id="quizForm" method="POST" action="process_quiz.php" class="space-y-6">
                
                <?php foreach ($questions as $index => $q): ?>
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-[#03c4ce] text-white font-bold text-sm shrink-0">
                            Q<?php echo ($index + 1); ?>
                        </span>
                        <h3 class="text-lg font-bold text-slate-900 pt-0.5 font-heading">
                            <?php echo htmlspecialchars($q['question']); ?>
                        </h3>
                    </div>

                    <!-- Options List -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        
                        <!-- Option A -->
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-[#03c4ce] hover:bg-slate-50/80 cursor-pointer transition-all option-label">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="A" required
                                   class="text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <span class="text-sm font-semibold text-slate-800">A) <?php echo htmlspecialchars($q['option_a']); ?></span>
                        </label>

                        <!-- Option B -->
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-[#03c4ce] hover:bg-slate-50/80 cursor-pointer transition-all option-label">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="B"
                                   class="text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <span class="text-sm font-semibold text-slate-800">B) <?php echo htmlspecialchars($q['option_b']); ?></span>
                        </label>

                        <!-- Option C -->
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-[#03c4ce] hover:bg-slate-50/80 cursor-pointer transition-all option-label">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="C"
                                   class="text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <span class="text-sm font-semibold text-slate-800">C) <?php echo htmlspecialchars($q['option_c']); ?></span>
                        </label>

                        <!-- Option D -->
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-200 hover:border-[#03c4ce] hover:bg-slate-50/80 cursor-pointer transition-all option-label">
                            <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="D"
                                   class="text-[#03c4ce] focus:ring-[#03c4ce] w-5 h-5 border-slate-300">
                            <span class="text-sm font-semibold text-slate-800">D) <?php echo htmlspecialchars($q['option_d']); ?></span>
                        </label>

                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" id="submitQuizBtn"
                            class="w-full py-4 px-8 rounded-2xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-extrabold text-lg shadow-xl shadow-[#03c4ce]/25 transition-all duration-300 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                        <span>Submit Final Quiz & View Results</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
        // Timer Logic
        let totalSeconds = <?php echo $durationMinutes * 60; ?>;
        const timerDisplay = document.getElementById('timerDisplay');
        const quizForm = document.getElementById('quizForm');

        function updateTimer() {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            
            timerDisplay.textContent = 
                (minutes < 10 ? '0' : '') + minutes + ':' + 
                (seconds < 10 ? '0' : '') + seconds;

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                alert("Time's up! Submitting your assessment now.");
                quizForm.submit();
            } else {
                totalSeconds--;
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>
