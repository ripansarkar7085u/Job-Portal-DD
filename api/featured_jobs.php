<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
}

$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 6;
if ($limit < 1) {
    $limit = 6;
}
if ($limit > 200) {
    $limit = 200;
}

$sql = "SELECT j.id, j.title, j.category, j.employment_type, j.experience_level, j.location, j.salary_min, j.salary_max, j.salary_period, j.currency, j.salary_visible, j.description, j.created_at, c.company_name
        FROM jobs j
        INNER JOIN companies c ON c.id = j.company_id
        WHERE j.status = 'published'
        ORDER BY j.created_at DESC
        LIMIT ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to load featured jobs.']);
}

$stmt->bind_param('i', $limit);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'id' => (int) $row['id'],
        'title' => (string) $row['title'],
        'category' => (string) ($row['category'] ?? ''),
        'company_name' => (string) $row['company_name'],
        'employment_type' => (string) $row['employment_type'],
        'experience_level' => (string) ($row['experience_level'] ?? ''),
        'location' => (string) ($row['location'] ?? ''),
        'salary_min' => $row['salary_min'] !== null ? (float) $row['salary_min'] : null,
        'salary_max' => $row['salary_max'] !== null ? (float) $row['salary_max'] : null,
        'salary_period' => (string) ($row['salary_period'] ?? 'year'),
        'currency' => (string) ($row['currency'] ?? 'USD'),
        'salary_visible' => (bool) ((int) $row['salary_visible']),
        'description' => (string) ($row['description'] ?? ''),
        'created_at' => (string) $row['created_at'],
    ];
}

$stmt->close();

auth_json_response(200, [
    'success' => true,
    'jobs' => $jobs,
]);
