<?php
require_once '../config/database.php';
header('Content-Type: application/json');
// Always return logged_in true for admin (no session check)
echo json_encode([
    'success' => true,
    'logged_in' => true,
    'admin' => [
        'id' => 1,
        'username' => 'admin',
        'full_name' => 'Admin',
        'email' => 'admin@example.com',
        'role' => 'admin'
    ]
]);
?>
