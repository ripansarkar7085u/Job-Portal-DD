<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$companyId = (int) $_SESSION['company_id'];

// Delete company and all related data
$conn->begin_transaction();
try {
    $conn->query("DELETE FROM company_benefits WHERE company_id = $companyId");
    $conn->query("DELETE FROM company_photos WHERE company_id = $companyId");
    $conn->query("DELETE FROM jobs WHERE company_id = $companyId");
    $conn->query("DELETE FROM applications WHERE company_id = $companyId");
    $conn->query("DELETE FROM companies WHERE id = $companyId");
    $conn->commit();
    session_destroy();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to delete company.']);
}
$conn->close();
