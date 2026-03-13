<?php
/**
 * Registration Handler
 */

require_once '../config/database.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$user_type = isset($_POST['user_type']) ? trim($_POST['user_type']) : 'candidate';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

// Validation
$errors = [];

// Validate full name
if (empty($full_name)) {
    $errors[] = 'Full name is required';
} elseif (strlen($full_name) < 2 || strlen($full_name) > 100) {
    $errors[] = 'Full name must be between 2 and 100 characters';
}

// Validate email
if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

// Validate password
if (empty($password)) {
    $errors[] = 'Password is required';
} elseif (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters';
} elseif (!preg_match('/[A-Z]/', $password)) {
    $errors[] = 'Password must contain at least one uppercase letter';
} elseif (!preg_match('/[a-z]/', $password)) {
    $errors[] = 'Password must contain at least one lowercase letter';
} elseif (!preg_match('/[0-9]/', $password)) {
    $errors[] = 'Password must contain at least one number';
} elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    $errors[] = 'Password must contain at least one special character';
}

// Validate confirm password
if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
}

// Validate user type
if (!in_array($user_type, ['candidate', 'employer'])) {
    $errors[] = 'Invalid user type';
}

// Validate phone (optional but if provided, must be valid)
if (!empty($phone) && !preg_match('/^[0-9+\-\s()]{10,20}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number';
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors), 'errors' => $errors]);
    exit;
}

// Check if email already exists
$check_sql = "SELECT id FROM users WHERE email = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered. Please login or use a different email.']);
    exit;
}
mysqli_stmt_close($check_stmt);

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert user into database
$insert_sql = "INSERT INTO users (full_name, email, password, user_type, phone) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = mysqli_prepare($conn, $insert_sql);
mysqli_stmt_bind_param($insert_stmt, "sssss", $full_name, $email, $hashed_password, $user_type, $phone);

if (mysqli_stmt_execute($insert_stmt)) {
    $user_id = mysqli_insert_id($conn);
    
    // Start session and store user data
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $full_name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_type'] = $user_type;
    $_SESSION['logged_in'] = true;
    
    echo json_encode([
        'success' => true, 
        'message' => 'Registration successful!',
        'redirect' => $user_type === 'employer' ? '/admin/index.html' : '/user/candidate-dashboard.html'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
}

mysqli_stmt_close($insert_stmt);
mysqli_close($conn);
?>
