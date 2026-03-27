<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT c.id, c.company_name, c.industry, c.email, COUNT(j.id) as jobs_count FROM companies c LEFT JOIN jobs j ON c.id = j.company_id GROUP BY c.id ORDER BY c.created_at DESC LIMIT 12";
$result = $conn->query($sql);
$companies = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $companies[] = [
            'id' => (int)$row['id'],
            'name' => $row['company_name'],
            'industry' => $row['industry'] ?? '',
            'email' => $row['email'],
            // Always use generated avatar since logo column does not exist
            'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($row['company_name']) . '&background=0d47a1&color=fff&rounded=false',
            'jobs_count' => (int)$row['jobs_count']
        ];
    }
}
echo json_encode(['success' => true, 'companies' => $companies]);
$conn->close();
