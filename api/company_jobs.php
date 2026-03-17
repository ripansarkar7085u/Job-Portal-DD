<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    auth_json_response(401, ['success' => false, 'message' => 'Company login required.']);
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

$validEmploymentTypes = ['full-time', 'part-time', 'contract', 'freelance', 'internship'];
$validSalaryPeriods = ['year', 'month', 'hour'];
$validStatuses = ['draft', 'published', 'closed'];

$errors = [];

if ($title === '' || strlen($title) > 255) {
    $errors[] = 'Job title is required and must be under 255 characters.';
}

if (!in_array($employmentType, $validEmploymentTypes, true)) {
    $errors[] = 'Employment type is invalid.';
}

if ($location === '' || strlen($location) > 255) {
    $errors[] = 'Location is required and must be under 255 characters.';
}

if ($description === '') {
    $errors[] = 'Job description is required.';
}

if ($requirements === '') {
    $errors[] = 'Job requirements are required.';
}

if (!in_array($salaryPeriod, $validSalaryPeriods, true)) {
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

auth_json_response(201, [
    'success' => true,
    'message' => $status === 'draft' ? 'Draft saved successfully.' : 'Job published successfully.',
    'job_id' => $jobId,
]);
