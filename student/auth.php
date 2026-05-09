<?php
/**
 * Student Authentication Functions
 * student/auth.php
 */
session_start();

function loginStudent($student) {
    $_SESSION['student_logged_in'] = true;
    $_SESSION['student_id'] = $student['student_id'];
    $_SESSION['student_db_id'] = $student['id'];
    $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
    $_SESSION['student_registration_id'] = $student['registration_id'];
}

function isStudentLoggedIn() {
    return isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true;
}

function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function logoutStudent() {
    unset($_SESSION['student_logged_in']);
    unset($_SESSION['student_id']);
    unset($_SESSION['student_db_id']);
    unset($_SESSION['student_name']);
    unset($_SESSION['student_registration_id']);
}
