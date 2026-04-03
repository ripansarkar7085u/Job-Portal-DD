<?php

require_once __DIR__ . '/_auth_common.php';
require_once __DIR__ . '/../user/_user_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);
user_ensure_applications_table($conn);
user_ensure_alerts_table($conn);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
}

if (!auth_validate_same_origin()) {
    auth_json_response(403, ['success' => false, 'message' => 'Request origin is not allowed.']);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'user' || !isset($_SESSION['user_id'])) {
    auth_json_response(401, ['success' => false, 'message' => 'User login required.']);
}

$payload = auth_get_request_data();
$userId = (int) $_SESSION['user_id'];
$jobId = isset($payload['job_id']) ? (int) $payload['job_id'] : 0;
$coverLetter = trim((string) ($payload['cover_letter'] ?? ''));

$resumePath = null;
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $size = $_FILES['resume']['size'];
    $maxSize = 5 * 1024 * 1024; // 5MB limit
    
    if ($size <= $maxSize && in_array($ext, ['pdf', 'doc', 'docx'])) {
        $uploadDir = dirname(__DIR__) . '/user_uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0700, true);
        }
        $safeName = 'resume_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        if (move_uploaded_file($tmp, $uploadDir . $safeName)) {
            $resumePath = $safeName;
        }
    }
}

if (!$resumePath) {
    // Try to get active resume from database
    $stmtCv = $conn->prepare("SELECT file_name FROM user_resumes WHERE user_id = ? AND status = 'Active' ORDER BY id DESC LIMIT 1");
    if ($stmtCv) {
        $stmtCv->bind_param("i", $userId);
        $stmtCv->execute();
        $resCv = $stmtCv->get_result();
        if ($resCv && $rowCv = $resCv->fetch_assoc()) {
            $resumePath = $rowCv['file_name'];
        }
        $stmtCv->close();
    }
}

if (!$resumePath) {
    auth_json_response(422, ['success' => false, 'message' => 'Please upload a CV to apply.']);
}

if ($jobId <= 0) {
    auth_json_response(422, ['success' => false, 'message' => 'Valid job id is required.']);
}

$jobStmt = $conn->prepare("SELECT j.id, j.title, j.company_id, c.company_name
    FROM jobs j
    INNER JOIN companies c ON c.id = j.company_id
    WHERE j.id = ? AND j.status = 'published'
    LIMIT 1");

if (!$jobStmt) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to validate job.']);
}

$jobStmt->bind_param('i', $jobId);
$jobStmt->execute();
$jobResult = $jobStmt->get_result();
$job = $jobResult ? $jobResult->fetch_assoc() : null;
$jobStmt->close();

if (!$job) {
    auth_json_response(404, ['success' => false, 'message' => 'Job not found or not open for applications.']);
}

$insert = $conn->prepare('INSERT INTO user_job_applications (user_id, job_id, cover_letter, resume_path, status) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE cover_letter = VALUES(cover_letter), resume_path = COALESCE(VALUES(resume_path), resume_path), status = VALUES(status), updated_at = CURRENT_TIMESTAMP');
if (!$insert) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to save application.']);
}

$status = 'applied';
$insert->bind_param('iisss', $userId, $jobId, $coverLetter, $resumePath, $status);
if (!$insert->execute()) {
    $insert->close();
    auth_json_response(500, ['success' => false, 'message' => 'Failed to save application.']);
}
$insert->close();

$alertTitle = 'Application Submitted';
$alertMessage = 'Your application for "' . (string) $job['title'] . '" at ' . (string) $job['company_name'] . ' has been submitted.';
$alertType = 'application';
$alertInsert = $conn->prepare('INSERT INTO user_alerts (user_id, related_job_id, title, message, alert_type, is_read) VALUES (?, ?, ?, ?, ?, 0)');
if ($alertInsert) {
    $alertInsert->bind_param('iisss', $userId, $jobId, $alertTitle, $alertMessage, $alertType);
    $alertInsert->execute();
    $alertInsert->close();
}

auth_json_response(200, [
    'success' => true,
    'message' => 'Application submitted successfully.',
]);
