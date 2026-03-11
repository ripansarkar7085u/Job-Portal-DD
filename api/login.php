<?php


require_once '../config/database.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : false;

// Validation
$errors = [];

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
    exit;
}

// Find user by email
$sql = "SELECT id, full_name, email, password, user_type, is_active FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    exit;
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if account is active
if (!$user['is_active']) {
    echo json_encode(['success' => false, 'message' => 'Your account has been deactivated. Please contact support.']);
    exit;
}

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    exit;
}

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_type'] = $user['user_type'];
$_SESSION['logged_in'] = true;

// Set remember me cookie if requested
if ($remember) {
    $token = bin2hex(random_bytes(32));
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true); // 30 days
    // Note: In production, store this token in database for validation
}

// Determine redirect URL based on user type
$redirect_url = $user['user_type'] === 'employer' ? '/admin/index.html' : '/user/candidate-dashboard.html';

echo json_encode([
    'success' => true, 
    'message' => 'Login successful!',
    'user' => [
        'name' => $user['full_name'],
        'email' => $user['email'],
        'type' => $user['user_type']
    ],
    'redirect' => $redirect_url
]);

mysqli_close($conn);
?>
