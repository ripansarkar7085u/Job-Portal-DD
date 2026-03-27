<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$jobId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$jobId) {
    echo json_encode(['success' => false, 'message' => 'No job ID provided.']);
    exit;
}
$stmt = $conn->prepare('SELECT j.*, c.company_name, c.logo as company_logo FROM jobs j LEFT JOIN companies c ON j.company_id = c.id WHERE j.id = ? LIMIT 1');
$stmt->bind_param('i', $jobId);
$stmt->execute();
$result = $stmt->get_result();
if ($job = $result->fetch_assoc()) {
    // Optionally decode tags, requirements, etc.
    $job['tags'] = $job['tags'] ? explode(',', $job['tags']) : [];
    $job['requirements'] = $job['requirements'] ? explode("\n", $job['requirements']) : [];
    $job['nice_to_have'] = $job['nice_to_have'] ? explode("\n", $job['nice_to_have']) : [];
    $job['benefits'] = $job['benefits'] ? explode(',', $job['benefits']) : [];
    echo json_encode(['success' => true, 'job' => $job]);
} else {
    echo json_encode(['success' => false, 'message' => 'Job not found.']);
}
$stmt->close();
$conn->close();
