<?php

require_once __DIR__ . '/_auth_common.php';
require_once __DIR__ . '/../user/_user_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
user_ensure_profiles_table($conn);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'user' || !isset($_SESSION['user_id'])) {
    auth_json_response(200, [
        'success' => true,
        'skills' => [],
    ]);
}

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT skills FROM profiles WHERE user_id = ? LIMIT 1');
if (!$stmt) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to load profile skills.']);
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;
$stmt->close();

$rawSkills = (string) ($row['skills'] ?? '');
$parts = preg_split('/[\s,;|\/\\]+/', strtolower($rawSkills)) ?: [];

$skills = [];
foreach ($parts as $part) {
    $skill = trim($part);
    if ($skill === '' || strlen($skill) < 2) {
        continue;
    }
    $skills[$skill] = true;
}

auth_json_response(200, [
    'success' => true,
    'skills' => array_keys($skills),
]);
