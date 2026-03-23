<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
}

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 8;
if ($limit < 1) {
    $limit = 8;
}
if ($limit > 100) {
    $limit = 100;
}

$sql = "SELECT j.category, COUNT(*) AS jobs_count
        FROM jobs j
        WHERE j.status = 'published' AND j.category IS NOT NULL AND TRIM(j.category) <> ''
        GROUP BY j.category
        ORDER BY jobs_count DESC, j.category ASC
        LIMIT ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to load categories.']);
}

$stmt->bind_param('i', $limit);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$totalJobs = 0;

while ($row = $result->fetch_assoc()) {
    $rawCategory = trim((string) ($row['category'] ?? ''));
    if ($rawCategory === '') {
        continue;
    }

    $count = (int) ($row['jobs_count'] ?? 0);
    $totalJobs += $count;

    $display = ucwords(str_replace(['-', '_'], ' ', strtolower($rawCategory)));
    $id = sprintf('%u', crc32(strtolower($rawCategory)));

    $categories[] = [
        'id' => $id,
        'category' => $rawCategory,
        'display_name' => $display,
        'jobs_count' => $count,
    ];
}

$stmt->close();

auth_json_response(200, [
    'success' => true,
    'categories' => $categories,
    'total_jobs' => $totalJobs,
]);
