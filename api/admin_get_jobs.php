<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$sql = "SELECT jobs.id, jobs.title, companies.company_name AS company, jobs.location, jobs.type, jobs.created_at AS posted, jobs.status FROM jobs LEFT JOIN companies ON jobs.company_id = companies.id ORDER BY jobs.created_at DESC";
$result = $conn->query($sql);
$jobs = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = [
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'company' => $row['company'] ?? '',
            'location' => $row['location'],
            'type' => $row['type'],
            'posted' => $row['posted'],
            'status' => $row['status']
        ];
    }
}
echo json_encode(['success' => true, 'jobs' => $jobs]);
$conn->close();
