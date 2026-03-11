<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
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

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
    exit;
}

// Find admin by username
$sql = "SELECT id, username, password, full_name, email, role, is_active FROM admins WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit;
}

$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if account is active
if (!$admin['is_active']) {
    echo json_encode(['success' => false, 'message' => 'Your account has been deactivated. Please contact super admin.']);
    exit;
}

// Verify password
if (!password_verify($password, $admin['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    exit;
}

// Update last login time
$updateSql = "UPDATE admins SET last_login = NOW() WHERE id = ?";
$updateStmt = mysqli_prepare($conn, $updateSql);
mysqli_stmt_bind_param($updateStmt, "i", $admin['id']);
mysqli_stmt_execute($updateStmt);
mysqli_stmt_close($updateStmt);

// Set session variables for admin
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['admin_name'] = $admin['full_name'];
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
        'role' => $admin['role']
    ]
]);

mysqli_close($conn);
?>
