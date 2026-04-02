<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// Fetch all users
$sql = "SELECT id, full_name, email, phone, status, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => (int)$row['id'],
            'name' => $row['full_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'status' => $row['status'],
            'joined' => $row['created_at'],
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($row['full_name']) . '&background=0d47a1&color=fff'
        ];
    }
}
echo json_encode(['success' => true, 'users' => $users]);
$conn->close();
