<?php
/**
 * Student Assessment Portal - Entry & Registration
 * assessment/index.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/init_db.php';

$error = '';

// Fetch active assessments from database
try {
    $stmt = $pdo->prepare("
        SELECT a.*, COUNT(q.id) as question_count 
        FROM assessments a 
        LEFT JOIN assessment_questions q ON a.id = q.assessment_id 
        WHERE a.status = 'active' 
        GROUP BY a.id 
        ORDER BY a.id ASC
    ");
    $stmt->execute();
    $assessments = $stmt->fetchAll();
} catch (Exception $e) {
    $assessments = [];
    $error = "Error loading assessments: " . $e->getMessage();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name'] ?? '');
    $mobile       = trim($_POST['mobile'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $course       = trim($_POST['course'] ?? '');
    $year         = trim($_POST['year'] ?? '');
    $college_name = trim($_POST['college_name'] ?? '');
    $assessment_id= (int)($_POST['assessment_id'] ?? 0);

    // Clean phone
    $rawMobile = preg_replace('/\D/', '', $mobile);

    if (empty($student_name) || empty($email) || empty($rawMobile) || empty($course) || empty($year) || empty($college_name) || empty($assessment_id)) {
        $error = "Please fill out all required fields to start the assessment.";
    } elseif (strlen($rawMobile) !== 10 || !preg_match('/^[6-9][0-9]{9}$/', $rawMobile)) {
        $error = "Please enter a valid 10-digit mobile number.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Verify selected assessment exists
        $aStmt = $pdo->prepare("SELECT * FROM assessments WHERE id = ? AND status = 'active'");
        $aStmt->execute([$assessment_id]);
        $targetAssessment = $aStmt->fetch();

        if (!$targetAssessment) {
            $error = "Selected assessment is not available.";
        } else {
            // Save student info in session
            $_SESSION['quiz_student'] = [
                'student_name'  => $student_name,
                'mobile'        => '+91' . $rawMobile,
                'email'         => $email,
                'course'        => $course,
                'year'          => $year,
                'college_name'  => $college_name,
                'assessment_id' => $assessment_id,
                'start_time'    => time()
            ];

            header("Location: quiz.php?id=" . $assessment_id);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Portal | <?php echo INSTITUTE_NAME; ?></title>
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

    <!-- Main Content -->
    <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Page Header Card -->
            <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl border border-slate-100 text-center relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-[#03c4ce]/10 rounded-full blur-2xl"></div>
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#03c4ce]/10 text-[#03c4ce] mb-4">
                    <span class="material-symbols-outlined text-3xl">quiz</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 font-heading mb-3">
                    Student Assessment Portal
                </h1>
                <p class="text-slate-600 max-w-2xl mx-auto text-base">
                    Test your technical knowledge and skills. Register your candidate details below to begin your online skill assessment.
                </p>
            </div>

            <?php if (!empty($error)): ?>
            <!-- Error Alert -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center gap-3">
                <span class="material-symbols-outlined text-rose-500">error</span>
                <span class="font-medium text-sm"><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <!-- Available Quizzes / Assessments Selection Grid -->
            <div>
                <h2 class="text-xl font-bold text-slate-900 font-heading mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#03c4ce]">verified</span> Available Assessments
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($assessments as $item): ?>
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-md hover:border-[#03c4ce] transition-all flex flex-col justify-between cursor-pointer assessment-card"
                         onclick="selectAssessment(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars(addslashes($item['title'])); ?>')">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-[#03c4ce]/10 text-[#03c4ce] text-xs font-bold uppercase">
                                    <?php echo htmlspecialchars($item['course']); ?>
                                </span>
                                <span class="text-xs text-slate-500 flex items-center gap-1 font-semibold">
                                    <span class="material-symbols-outlined text-sm">schedule</span> <?php echo $item['duration_minutes']; ?> Mins
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading"><?php echo htmlspecialchars($item['title']); ?></h3>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 mt-3">
                            <span>Questions: <strong><?php echo $item['question_count']; ?> MCQs</strong></span>
                            <span>Passing: <strong><?php echo (int)$item['pass_percentage']; ?>%</strong></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Student Registration & Entry Form -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 shadow-xl border border-slate-100">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#03c4ce]/10 text-[#03c4ce]">
                        <span class="material-symbols-outlined text-lg">how_to_reg</span>
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 font-heading">Candidate Registration Before Quiz</h3>
                </div>

                <form method="POST" action="index.php" class="space-y-6">
                    
                    <!-- Selected Assessment Header -->
                    <div>
                        <label for="assessment_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Assessment Test <span class="text-rose-500">*</span></label>
                        <select id="assessment_id" name="assessment_id" required
                                class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all font-semibold">
                            <option value="">-- Choose Assessment Test --</option>
                            <?php foreach ($assessments as $item): ?>
                            <option value="<?php echo $item['id']; ?>" <?php echo (isset($_POST['assessment_id']) && $_POST['assessment_id'] == $item['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($item['title']); ?> (<?php echo $item['question_count']; ?> Questions)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Personal & Academic Input Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="student_name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="student_name" name="student_name" required
                                   value="<?php echo htmlspecialchars($_POST['student_name'] ?? ''); ?>"
                                   class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                   placeholder="Enter your full name">
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold text-sm">+91</span>
                                <input type="tel" id="mobile" name="mobile" inputmode="numeric" maxlength="10" required
                                       value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>"
                                       class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 pl-14 pr-4 shadow-sm transition-all"
                                       placeholder="10-digit mobile number">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                   placeholder="name@example.com">
                        </div>

                        <div>
                            <label for="course" class="block text-sm font-semibold text-slate-700 mb-2">Course / Technology <span class="text-rose-500">*</span></label>
                            <select id="course" name="course" required
                                    class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all">
                                <option value="">-- Select Course --</option>
                                <option value="Full Stack Development" <?php echo (($_POST['course'] ?? '') == 'Full Stack Development') ? 'selected' : ''; ?>>Full Stack Development</option>
                                <option value="Python Development" <?php echo (($_POST['course'] ?? '') == 'Python Development') ? 'selected' : ''; ?>>Python Development</option>
                                <option value="Java Programming" <?php echo (($_POST['course'] ?? '') == 'Java Programming') ? 'selected' : ''; ?>>Java Programming</option>
                                <option value="MERN Stack Development" <?php echo (($_POST['course'] ?? '') == 'MERN Stack Development') ? 'selected' : ''; ?>>MERN Stack Development</option>
                                <option value="Data Science & Analytics" <?php echo (($_POST['course'] ?? '') == 'Data Science & Analytics') ? 'selected' : ''; ?>>Data Science & Analytics</option>
                                <option value="Android Development" <?php echo (($_POST['course'] ?? '') == 'Android Development') ? 'selected' : ''; ?>>Android Development</option>
                                <option value="Web Design / Frontend" <?php echo (($_POST['course'] ?? '') == 'Web Design / Frontend') ? 'selected' : ''; ?>>Web Design / Frontend</option>
                                <option value="Other" <?php echo (($_POST['course'] ?? '') == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700 mb-2">Year of Study / Passing <span class="text-rose-500">*</span></label>
                            <select id="year" name="year" required
                                    class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 py-3 px-4 shadow-sm transition-all">
                                <option value="">-- Select Year --</option>
                                <option value="1st Year" <?php echo (($_POST['year'] ?? '') == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                                <option value="2nd Year" <?php echo (($_POST['year'] ?? '') == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                                <option value="3rd Year" <?php echo (($_POST['year'] ?? '') == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                                <option value="Final Year" <?php echo (($_POST['year'] ?? '') == 'Final Year') ? 'selected' : ''; ?>>Final Year</option>
                                <option value="Passed Out 2024" <?php echo (($_POST['year'] ?? '') == 'Passed Out 2024') ? 'selected' : ''; ?>>Passed Out 2024</option>
                                <option value="Passed Out 2025" <?php echo (($_POST['year'] ?? '') == 'Passed Out 2025') ? 'selected' : ''; ?>>Passed Out 2025</option>
                                <option value="Other" <?php echo (($_POST['year'] ?? '') == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="college_name" class="block text-sm font-semibold text-slate-700 mb-2">College / University Name <span class="text-rose-500">*</span></label>
                            <input type="text" id="college_name" name="college_name" required
                                   value="<?php echo htmlspecialchars($_POST['college_name'] ?? ''); ?>"
                                   class="w-full rounded-xl border-slate-200 focus:border-[#03c4ce] focus:ring-[#03c4ce] text-slate-900 placeholder:text-slate-400 py-3 px-4 shadow-sm transition-all"
                                   placeholder="Enter your college name">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full py-4 px-8 rounded-2xl bg-[#03c4ce] hover:bg-[#02aab3] text-white font-extrabold text-lg shadow-xl shadow-[#03c4ce]/25 transition-all duration-300 flex items-center justify-center gap-3">
                            <span>Start Quiz Assessment Now</span>
                            <span class="material-symbols-outlined text-2xl">play_circle</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script>
        function selectAssessment(id, title) {
            const selectEl = document.getElementById('assessment_id');
            selectEl.value = id;
            selectEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            selectEl.classList.add('ring-2', 'ring-[#03c4ce]');
            setTimeout(() => selectEl.classList.remove('ring-2', 'ring-[#03c4ce]'), 1500);
        }

        const mobileInput = document.getElementById('mobile');
        if (mobileInput) {
            mobileInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 10);
            });
        }
    </script>
</body>
</html>
