<?php
require_once __DIR__ . '/auth.php';
logoutStudent();
header('Location: login.php');
exit;
