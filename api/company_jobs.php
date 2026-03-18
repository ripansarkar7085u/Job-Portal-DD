<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    auth_json_response(401, ['success' => false, 'message' => 'Company login required.']);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
    $companyId = (int) $_SESSION['company_id'];

    $jobId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($jobId > 0) {
        $detailStmt = $conn->prepare('SELECT id, title, employment_type, experience_level, category, work_style, location, salary_min, salary_max, salary_period, currency, salary_visible, description, requirements, status, created_at, updated_at FROM jobs WHERE id = ? AND company_id = ? LIMIT 1');
        if (!$detailStmt) {
            auth_json_response(500, ['success' => false, 'message' => 'Unable to load job details.']);
        }

        $detailStmt->bind_param('ii', $jobId, $companyId);
        $detailStmt->execute();
        $detailResult = $detailStmt->get_result();
        $jobRow = $detailResult ? $detailResult->fetch_assoc() : null;
        $detailStmt->close();

        if (!$jobRow) {
            auth_json_response(404, ['success' => false, 'message' => 'Job not found.']);
        }

        auth_json_response(200, [
            'success' => true,
            'job' => [
                'id' => (int) $jobRow['id'],
                'title' => (string) ($jobRow['title'] ?? ''),
                'employment_type' => (string) ($jobRow['employment_type'] ?? ''),
                'experience_level' => (string) ($jobRow['experience_level'] ?? ''),
                'category' => (string) ($jobRow['category'] ?? ''),
                'work_style' => (string) ($jobRow['work_style'] ?? ''),
                'location' => (string) ($jobRow['location'] ?? ''),
                'salary_min' => $jobRow['salary_min'] !== null ? (float) $jobRow['salary_min'] : null,
                'salary_max' => $jobRow['salary_max'] !== null ? (float) $jobRow['salary_max'] : null,
                'salary_period' => (string) ($jobRow['salary_period'] ?? 'year'),
                'currency' => (string) ($jobRow['currency'] ?? 'USD'),
                'salary_visible' => (bool) ((int) ($jobRow['salary_visible'] ?? 1)),
                'description' => (string) ($jobRow['description'] ?? ''),
                'requirements' => (string) ($jobRow['requirements'] ?? ''),
                'status' => (string) ($jobRow['status'] ?? 'draft'),
                'created_at' => (string) ($jobRow['created_at'] ?? ''),
                'updated_at' => (string) ($jobRow['updated_at'] ?? ''),
            ],
        ]);
    }

    $stmt = $conn->prepare('SELECT id, title, employment_type, location, salary_min, salary_max, salary_period, currency, salary_visible, status, created_at FROM jobs WHERE company_id = ? ORDER BY created_at DESC');
    if (!$stmt) {
        auth_json_response(500, ['success' => false, 'message' => 'Unable to load jobs.']);
    }

    $stmt->bind_param('i', $companyId);
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs = [];
    $counts = [
        'all' => 0,
        'active' => 0,
        'closed' => 0,
        'draft' => 0,
    ];

    while ($row = $result->fetch_assoc()) {
        $rawStatus = (string) $row['status'];
        $normalizedStatus = $rawStatus === 'published' ? 'active' : $rawStatus;

        $counts['all']++;
        if (isset($counts[$normalizedStatus])) {
            $counts[$normalizedStatus]++;
        }

        $jobs[] = [
            'id' => (int) $row['id'],
            'title' => (string) $row['title'],
            'employment_type' => (string) $row['employment_type'],
            'location' => (string) ($row['location'] ?? ''),
            'salary_min' => $row['salary_min'] !== null ? (float) $row['salary_min'] : null,
            'salary_max' => $row['salary_max'] !== null ? (float) $row['salary_max'] : null,
            'salary_period' => (string) ($row['salary_period'] ?? 'year'),
            'currency' => (string) ($row['currency'] ?? 'USD'),
            'salary_visible' => (bool) ((int) $row['salary_visible']),
            'status' => $normalizedStatus,
            'created_at' => (string) $row['created_at'],
        ];
    }

    $stmt->close();

    auth_json_response(200, [
        'success' => true,
        'jobs' => $jobs,
        'counts' => $counts,
    ]);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
}

if (!auth_validate_same_origin()) {
    auth_json_response(403, ['success' => false, 'message' => 'Request origin is not allowed.']);
}

$payload = auth_get_request_data();

$title = trim((string) ($payload['title'] ?? ''));
$employmentType = trim((string) ($payload['employment_type'] ?? ''));
$experienceLevel = trim((string) ($payload['experience_level'] ?? ''));
$category = trim((string) ($payload['category'] ?? ''));
$workStyle = trim((string) ($payload['work_style'] ?? ''));
$location = trim((string) ($payload['location'] ?? ''));
$salaryMinRaw = (string) ($payload['salary_min'] ?? '');
$salaryMaxRaw = (string) ($payload['salary_max'] ?? '');
$salaryPeriod = trim((string) ($payload['salary_period'] ?? 'year'));
$currency = strtoupper(trim((string) ($payload['currency'] ?? 'USD')));
$salaryVisible = !empty($payload['salary_visible']) ? 1 : 0;
$description = trim((string) ($payload['description'] ?? ''));
$requirements = trim((string) ($payload['requirements'] ?? ''));
$status = trim((string) ($payload['status'] ?? 'published'));
$jobId = isset($payload['id']) ? (int) $payload['id'] : 0;
$isUpdate = $jobId > 0;

$isPublished = $status === 'published';

$validEmploymentTypes = ['full-time', 'part-time', 'contract', 'freelance', 'internship'];
$validSalaryPeriods = ['year', 'month', 'hour'];
$validStatuses = ['draft', 'published', 'closed'];

$errors = [];

if (strlen($title) > 255) {
    $errors[] = 'Job title must be under 255 characters.';
}

if ($isPublished && $title === '') {
    $errors[] = 'Job title is required.';
}

if ($employmentType !== '' && !in_array($employmentType, $validEmploymentTypes, true)) {
    $errors[] = 'Employment type is invalid.';
}

if (strlen($location) > 255) {
    $errors[] = 'Location must be under 255 characters.';
}

if ($isPublished && $location === '') {
    $errors[] = 'Location is required.';
}

if ($isPublished && $description === '') {
    $errors[] = 'Job description is required.';
}

if ($isPublished && $requirements === '') {
    $errors[] = 'Job requirements are required.';
}

if ($salaryPeriod !== '' && !in_array($salaryPeriod, $validSalaryPeriods, true)) {
    $errors[] = 'Salary period is invalid.';
}

if (!in_array($status, $validStatuses, true)) {
    $errors[] = 'Job status is invalid.';
}

$salaryMin = null;
$salaryMax = null;

if ($salaryMinRaw !== '') {
    if (!is_numeric($salaryMinRaw)) {
        $errors[] = 'Minimum salary must be a number.';
    } else {
        $salaryMin = (float) $salaryMinRaw;
    }
}

if ($salaryMaxRaw !== '') {
    if (!is_numeric($salaryMaxRaw)) {
        $errors[] = 'Maximum salary must be a number.';
    } else {
        $salaryMax = (float) $salaryMaxRaw;
    }
}

if ($salaryMin !== null && $salaryMax !== null && $salaryMin > $salaryMax) {
    $errors[] = 'Minimum salary cannot be greater than maximum salary.';
}

if (!empty($errors)) {
    auth_json_response(422, ['success' => false, 'message' => $errors[0], 'errors' => $errors]);
}

$companyId = (int) $_SESSION['company_id'];

if ($isPublished && $employmentType === '') {
    auth_json_response(422, ['success' => false, 'message' => 'Employment type is required.']);
}

if (!$isPublished && $title === '') {
    $title = 'Untitled Draft';
}

if ($employmentType === '') {
    $employmentType = 'full-time';
}

if ($salaryPeriod === '') {
    $salaryPeriod = 'year';
}

if (!$isUpdate) {
    $rateKey = $companyId . '|' . auth_get_client_ip();
    if (!auth_rate_limit_check($conn, 'company_job_post', $rateKey, 8, 600, 600)) {
        auth_json_response(429, ['success' => false, 'message' => 'Too many post attempts. Please wait a few minutes before posting again.']);
    }

    $duplicateStmt = $conn->prepare('SELECT id FROM jobs WHERE company_id = ? AND title = ? AND employment_type = ? AND location = ? AND description = ? AND requirements = ? AND created_at >= (NOW() - INTERVAL 10 MINUTE) LIMIT 1');
    if ($duplicateStmt) {
        $duplicateStmt->bind_param('isssss', $companyId, $title, $employmentType, $location, $description, $requirements);
        $duplicateStmt->execute();
        $duplicateResult = $duplicateStmt->get_result();
        $duplicateRow = $duplicateResult ? $duplicateResult->fetch_assoc() : null;
        $duplicateStmt->close();

        if ($duplicateRow) {
            auth_rate_limit_record_failure($conn, 'company_job_post', $rateKey, 8, 600, 600);
            auth_json_response(409, ['success' => false, 'message' => 'Duplicate job detected. Please edit the existing post instead of creating a copy.']);
        }
    }
}

if ($isUpdate) {
    $ownershipStmt = $conn->prepare('SELECT id FROM jobs WHERE id = ? AND company_id = ? LIMIT 1');
    if (!$ownershipStmt) {
        auth_json_response(500, ['success' => false, 'message' => 'Unable to validate job ownership.']);
    }

    $ownershipStmt->bind_param('ii', $jobId, $companyId);
    $ownershipStmt->execute();
    $ownershipResult = $ownershipStmt->get_result();
    $owned = $ownershipResult ? $ownershipResult->fetch_assoc() : null;
    $ownershipStmt->close();

    if (!$owned) {
        auth_json_response(404, ['success' => false, 'message' => 'Job not found or access denied.']);
    }

    $updateStmt = $conn->prepare('UPDATE jobs SET title = ?, employment_type = ?, experience_level = ?, category = ?, work_style = ?, location = ?, salary_min = ?, salary_max = ?, salary_period = ?, currency = ?, salary_visible = ?, description = ?, requirements = ?, status = ? WHERE id = ? AND company_id = ?');
    if (!$updateStmt) {
        auth_json_response(500, ['success' => false, 'message' => 'Unable to prepare job update statement.']);
    }

    $updateStmt->bind_param(
        'ssssssddssisssii',
        $title,
        $employmentType,
        $experienceLevel,
        $category,
        $workStyle,
        $location,
        $salaryMin,
        $salaryMax,
        $salaryPeriod,
        $currency,
        $salaryVisible,
        $description,
        $requirements,
        $status,
        $jobId,
        $companyId
    );

    if (!$updateStmt->execute()) {
        $updateStmt->close();
        auth_json_response(500, ['success' => false, 'message' => 'Failed to update job.']);
    }

    $updateStmt->close();

    auth_json_response(200, [
        'success' => true,
        'message' => $status === 'draft' ? 'Draft updated successfully.' : 'Job updated successfully.',
        'job_id' => $jobId,
    ]);
}

$stmt = $conn->prepare('INSERT INTO jobs (company_id, title, employment_type, experience_level, category, work_style, location, salary_min, salary_max, salary_period, currency, salary_visible, description, requirements, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
if (!$stmt) {
    auth_json_response(500, ['success' => false, 'message' => 'Unable to prepare job save statement.']);
}

$stmt->bind_param(
    'issssssddssisss',
    $companyId,
    $title,
    $employmentType,
    $experienceLevel,
    $category,
    $workStyle,
    $location,
    $salaryMin,
    $salaryMax,
    $salaryPeriod,
    $currency,
    $salaryVisible,
    $description,
    $requirements,
    $status
);

if (!$stmt->execute()) {
    $stmt->close();
    auth_json_response(500, ['success' => false, 'message' => 'Failed to save job.']);
}

$jobId = (int) $conn->insert_id;
$stmt->close();

auth_rate_limit_record_failure($conn, 'company_job_post', $companyId . '|' . auth_get_client_ip(), 8, 600, 600);

auth_json_response(201, [
    'success' => true,
    'message' => $status === 'draft' ? 'Draft saved successfully.' : 'Job published successfully.',
    'job_id' => $jobId,
]);
