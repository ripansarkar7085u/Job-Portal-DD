
<?php


require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function send_json($arr) {
    ob_clean();
    echo json_encode($arr);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(['success' => false, 'message' => 'Invalid request method']);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    send_json(['success' => false, 'message' => 'Not authorized.']);
}

if (!isset($conn) || !$conn || $conn->connect_error) {
    send_json(['success' => false, 'message' => 'Database connection failed.']);
}

$companyId = (int) $_SESSION['company_id'];
$data = json_decode(file_get_contents('php://input'), true);
if ($data === null) {
    send_json(['success' => false, 'message' => 'Invalid JSON input.']);
}

$fields = [
    'company_name', 'industry', 'company_size', 'founded', 'tagline', 'description', 'website', 'email', 'phone', 'location', 'linkedin', 'twitter', 'facebook', 'instagram', 'logo'
];
$updates = [];
$params = [];
$types = '';
foreach ($fields as $field) {
    $updates[] = "$field = ?";
    $params[] = isset($data[$field]) ? $data[$field] : '';
    $types .= 's';
}
$params[] = $companyId;
$types .= 'i';
$sql = "UPDATE companies SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log('Failed to prepare statement: ' . $conn->error);
    send_json(['success' => false, 'message' => 'Failed to prepare statement.']);
}
$stmt->bind_param($types, ...$params);
if ($stmt->execute()) {
    send_json(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    error_log('Failed to execute statement: ' . $stmt->error);
    send_json(['success' => false, 'message' => 'Failed to update profile.']);
}
$stmt->close();
$conn->close();
