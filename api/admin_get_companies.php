<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$sql = "SELECT id, company_name, industry, email, status, created_at FROM companies ORDER BY created_at DESC";
$result = $conn->query($sql);
$companies = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $companies[] = [
            'id' => (int)$row['id'],
            'name' => $row['company_name'],
            'industry' => $row['industry'] ?? '',
            'email' => $row['email'],
            'jobsPosted' => 0, // Placeholder, update if jobs count is needed
            'status' => $row['status'],
            'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($row['company_name']) . '&background=0d47a1&color=fff&rounded=false'
        ];
    }
}
echo json_encode(['success' => true, 'companies' => $companies]);
$conn->close();
