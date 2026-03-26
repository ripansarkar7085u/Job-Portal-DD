<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$companyId = (int) $_SESSION['company_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch all photos for this company
    $sql = "SELECT id, url FROM company_photos WHERE company_id = ? ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $photos = [];
    while ($row = $result->fetch_assoc()) {
        $photos[] = [ 'id' => (int)$row['id'], 'url' => $row['url'] ];
    }
    echo json_encode(['success' => true, 'photos' => $photos]);
    $stmt->close();
    $conn->close();
    exit;
}

if ($method === 'POST') {
    // Handle photo upload (expects base64 or URL for now)
    $data = json_decode(file_get_contents('php://input'), true);
    $url = trim($data['url'] ?? '');
    if (!$url) {
        echo json_encode(['success' => false, 'message' => 'Photo URL is required.']);
        exit;
    }
    $stmt = $conn->prepare('INSERT INTO company_photos (company_id, url) VALUES (?, ?)');
    $stmt->bind_param('is', $companyId, $url);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add photo.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Photo ID required.']);
        exit;
    }
    $stmt = $conn->prepare('DELETE FROM company_photos WHERE id = ? AND company_id = ?');
    $stmt->bind_param('ii', $id, $companyId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete photo.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
