<?php
/**
 * student-enquiry.php (Legacy)
 * Redirects to the complete and beautiful admission-form.php.
 */

$queryString = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
header("HTTP/1.1 301 Moved Permanently");
header("Location: admission-form.php" . $queryString);
exit;
?>
