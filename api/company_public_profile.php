<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');
$company_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$company_id) {
    echo json_encode(['success' => false, 'message' => 'No company ID provided.']);
    exit;
}
// Fetch company main info
$stmt = $conn->prepare('SELECT * FROM companies WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $company_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$company) {
    echo json_encode(['success' => false, 'message' => 'Company not found.']);
    exit;
}
// Fetch benefits
$benefits = [];
$res = $conn->query("SELECT benefit FROM company_benefits WHERE company_id = $company_id");
while ($row = $res->fetch_assoc()) $benefits[] = $row['benefit'];
// Fetch photos
$photos = [];
$res = $conn->query("SELECT url FROM company_photos WHERE company_id = $company_id");
while ($row = $res->fetch_assoc()) $photos[] = $row['url'];
// Fetch jobs
$jobs = [];
$res = $conn->query("SELECT id, title, location, type, salary, tags, posted_at FROM jobs WHERE company_id = $company_id ORDER BY posted_at DESC");
while ($row = $res->fetch_assoc()) {
    $row['tags'] = $row['tags'] ? explode(',', $row['tags']) : [];
    $jobs[] = $row;
}
// Output all
$company['benefits'] = $benefits;
$company['photos'] = $photos;
$company['jobs'] = $jobs;
echo json_encode(['success' => true, 'company' => $company]);
$conn->close();
