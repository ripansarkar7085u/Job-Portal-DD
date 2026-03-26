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
if (empty($username)) $errors[] = 'Username is required';
if (empty($password)) $errors[] = 'Password is required';
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
    exit;
}

// Ensure admin table exists
$conn->query("CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT 'Administrator',
    role VARCHAR(50) DEFAULT 'super_admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Ensure at least one admin exists with strict credentials
$admin_email = 'admin@admin.com';
$admin_username = 'admin';
$admin_password = password_hash('123', PASSWORD_DEFAULT);
$check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param('s', $admin_username);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO admins (username, email, password, full_name, role) VALUES (?, ?, ?, 'Administrator', 'super_admin')");
    $insert->bind_param('sss', $admin_username, $admin_email, $admin_password);
    $insert->execute();
    $insert->close();
}
$check->close();

// Authenticate
$stmt = $conn->prepare("SELECT id, username, email, password, full_name, role FROM admins WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
if ($admin = $result->fetch_assoc()) {
    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role'];
        $_SESSION['admin_logged_in'] = true;
        header('Location: ../admin/index.php');
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'admin' => [
                'id' => $admin['id'],
                'username' => $admin['username'],
                'full_name' => $admin['full_name'],
                'email' => $admin['email'],
                'role' => $admin['role']
            ]
        ]);
        $stmt->close();
        $conn->close();
        exit;
    }
}
$stmt->close();
$conn->close();
echo json_encode(['success' => false, 'message' => 'Invalid username or password']);

