<?php
require_once '../config/database.php';
header('Content-Type: application/json');
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT id, full_name, email FROM users ";
if ($q !== '') {
    $sql .= "WHERE full_name LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%') LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= "ORDER BY created_at DESC LIMIT 10";
    $result = $conn->query($sql);
}
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => (int)$row['id'],
            'name' => $row['full_name'],
            'email' => $row['email'],
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($row['full_name']) . '&background=0d47a1&color=fff'
        ];
    }
}
echo json_encode(['success' => true, 'users' => $users]);
$conn->close();
?>
