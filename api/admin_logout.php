<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Clear admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);
unset($_SESSION['admin_logged_in']);

// Destroy the session if no user is logged in either
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    session_destroy();
}

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]);
?>
