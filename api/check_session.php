<?php
/**
 * Check Session
 */

require_once '../config/database.php';

header('Content-Type: application/json');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo json_encode([
        'logged_in' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'type' => $_SESSION['user_type']
        ]
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>
