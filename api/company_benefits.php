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
    // Fetch all benefits for this company
    $sql = "SELECT id, benefit FROM company_benefits WHERE company_id = ? ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $benefits = [];
    while ($row = $result->fetch_assoc()) {
        $benefits[] = [ 'id' => (int)$row['id'], 'benefit' => $row['benefit'] ];
    }
    echo json_encode(['success' => true, 'benefits' => $benefits]);
    $stmt->close();
    $conn->close();
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $benefit = trim($data['benefit'] ?? '');
    if (!$benefit) {
        echo json_encode(['success' => false, 'message' => 'Benefit is required.']);
        exit;
    }
    $stmt = $conn->prepare('INSERT INTO company_benefits (company_id, benefit) VALUES (?, ?)');
    $stmt->bind_param('is', $companyId, $benefit);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add benefit.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Benefit ID required.']);
        exit;
    }
    $stmt = $conn->prepare('DELETE FROM company_benefits WHERE id = ? AND company_id = ?');
    $stmt->bind_param('ii', $id, $companyId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete benefit.']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
