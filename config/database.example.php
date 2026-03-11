<?php
/**
 * Database Configuration Example
 * Copy this file to database.php and update with your credentials
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'job-portal');
define('DB_PORT', 3306); // Default MySQL port, change if different

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    error_log('MySQL connection failed: ' . $conn->connect_error);
    http_response_code(503);
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Set charset
$conn->set_charset("utf8mb4");
?>
