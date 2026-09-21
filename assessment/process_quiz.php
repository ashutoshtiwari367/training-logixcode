<?php
/**
 * Student Assessment Portal - Quiz Evaluation & Saving
 * assessment/process_quiz.php
 */
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['quiz_student']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$student = $_SESSION['quiz_student'];
$assessment_id = (int)($student['assessment_id'] ?? 0);
$userAnswers = $_POST['answers'] ?? [];

try {
    // Fetch assessment details
    $aStmt = $pdo->prepare("SELECT * FROM assessments WHERE id = ?");
    $aStmt->execute([$assessment_id]);
    $assessment = $aStmt->fetch();

    if (!$assessment) {
        throw new Exception("Assessment test not found.");
    }

    // Fetch questions & correct answers
    $qStmt = $pdo->prepare("SELECT id, question, option_a, option_b, option_c, option_d, correct_option, marks FROM assessment_questions WHERE assessment_id = ?");
    $qStmt->execute([$assessment_id]);
    $questions = $qStmt->fetchAll();

    $score = 0;
    $totalQuestions = count($questions);
    $reviewData = [];

    foreach ($questions as $q) {
        $qId = $q['id'];
        $givenAnswer = $userAnswers[$qId] ?? 'NONE';
        $isCorrect = ($givenAnswer === $q['correct_option']);

        if ($isCorrect) {
            $score += (int)$q['marks'];
        }

        $reviewData[] = [
            'question_id'    => $qId,
            'question'       => $q['question'],
            'given_answer'   => $givenAnswer,
            'correct_answer' => $q['correct_option'],
            'is_correct'     => $isCorrect,
            'options'        => [
                'A' => $q['option_a'],
                'B' => $q['option_b'],
                'C' => $q['option_c'],
                'D' => $q['option_d'],
            ]
        ];
    }

    $percentage = ($totalQuestions > 0) ? round(($score / $totalQuestions) * 100, 2) : 0;
    $passPercentage = (float)$assessment['pass_percentage'];
    $status = ($percentage >= $passPercentage) ? 'PASS' : 'FAIL';

    // Save attempt record to DB
    $stmt = $pdo->prepare("
        INSERT INTO assessment_attempts (
            assessment_id, student_name, mobile, email, course, year, college_name,
            score, total_questions, percentage, status, answers_json, completed_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )
    ");

    $stmt->execute([
        $assessment_id,
        $student['student_name'],
        $student['mobile'],
        $student['email'],
        $student['course'],
        $student['year'],
        $student['college_name'],
        $score,
        $totalQuestions,
        $percentage,
        $status,
        json_encode($reviewData)
    ]);

    $attempt_id = $pdo->lastInsertId();

    // Clear quiz session
    unset($_SESSION['quiz_student']);
    $_SESSION['quiz_result_id'] = $attempt_id;

    header("Location: result.php?id=" . $attempt_id);
    exit;

} catch (Exception $e) {
    die("Error processing assessment quiz: " . htmlspecialchars($e->getMessage()));
}
