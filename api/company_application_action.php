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

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    auth_json_response(401, ['success' => false, 'message' => 'Company login required.']);
}

$payload = auth_get_request_data();
$applicationId = isset($payload['applicationId']) ? (int) $payload['applicationId'] : 0;
$action = strtolower(trim((string) ($payload['action'] ?? '')));

if ($applicationId <= 0) {
    auth_json_response(422, ['success' => false, 'message' => 'Valid application id is required.']);
}

$validActions = [
    'accept' => 'shortlisted',
    'shortlist' => 'shortlisted',
    'reject' => 'rejected',
    'review' => 'reviewing',
    'reviewing' => 'reviewing',
];

if (!isset($validActions[$action])) {
    auth_json_response(422, ['success' => false, 'message' => 'Invalid action.']);
}

$newStatus = $validActions[$action];
$companyId = (int) $_SESSION['company_id'];

$ownership = $conn->prepare('SELECT a.id, a.user_id, a.job_id, j.title, c.company_name FROM user_job_applications a INNER JOIN jobs j ON j.id = a.job_id INNER JOIN companies c ON c.id = j.company_id WHERE a.id = ? AND j.company_id = ? LIMIT 1');
if (!$ownership) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to validate application.']);
}

$ownership->bind_param('ii', $applicationId, $companyId);
$ownership->execute();
$ownershipResult = $ownership->get_result();
$application = $ownershipResult ? $ownershipResult->fetch_assoc() : null;
$ownership->close();

if (!$application) {
    auth_json_response(404, ['success' => false, 'message' => 'Application not found.']);
}

$update = $conn->prepare('UPDATE user_job_applications SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
if (!$update) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to update application status.']);
}

$update->bind_param('si', $newStatus, $applicationId);
if (!$update->execute()) {
    $update->close();
    auth_json_response(500, ['success' => false, 'message' => 'Failed to update application status.']);
}
$update->close();

$alertTitle = 'Application Status Updated';
$statusLabel = ucfirst($newStatus);
$alertMessage = 'Your application for "' . (string) $application['title'] . '" at ' . (string) $application['company_name'] . ' is now ' . $statusLabel . '.';
$alertType = 'application_status';
$userId = (int) $application['user_id'];
$jobId = (int) $application['job_id'];

$alertInsert = $conn->prepare('INSERT INTO user_alerts (user_id, related_job_id, title, message, alert_type, is_read) VALUES (?, ?, ?, ?, ?, 0)');
if ($alertInsert) {
    $alertInsert->bind_param('iisss', $userId, $jobId, $alertTitle, $alertMessage, $alertType);
    $alertInsert->execute();
    $alertInsert->close();
}

auth_json_response(200, [
    'success' => true,
    'message' => 'Application status updated successfully.',
    'status' => $newStatus,
]);
