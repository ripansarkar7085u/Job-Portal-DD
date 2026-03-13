<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    echo json_encode([
        'success' => true,
        'logged_in' => true,
        'admin' => [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'full_name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email'],
            'role' => $_SESSION['admin_role']
        ]
    ]);
} else {
    echo json_encode([
        'success' => true,
        'logged_in' => false
    ]);
}
?>
