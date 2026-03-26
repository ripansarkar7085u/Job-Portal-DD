<?php
require_once '../config/database.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validation
$errors = [];

if (empty($username)) {
    $errors[] = 'Username is required';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}


if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
    exit;
}

if ($username !== 'admin' || $password !== '123') {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit;
}


$_SESSION['admin_id'] = 1;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_name'] = 'Administrator';
$_SESSION['admin_email'] = $admin['email'];
$_SESSION['admin_role'] = $admin['role'];
$_SESSION['admin_logged_in'] = true;

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'admin' => [
        'id' => $admin['id'],
        'username' => $admin['username'],
        'full_name' => $admin['full_name'],
        'email' => $admin['email'],
        'role' => $admin['role']    ]
]);

mysqli_close($conn);
?>
