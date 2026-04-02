<?php
// Secure resume download endpoint
session_start();
$conn = new mysqli("localhost", "root", "", "job-portal");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('Invalid request.');
}
$id = (int)$_GET['id'];

// Only allow download if user is logged in (add more checks as needed)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403);
    exit('Not authorized.');
}

$stmt = $conn->prepare("SELECT file_name, display_name FROM user_resumes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    exit('File not found.');
}

$file = dirname(__DIR__) . '/user_uploads/' . $row['file_name'];
if (!file_exists($file)) {
    http_response_code(404);
    exit('File not found.');
}

$disposition = (isset($_GET['action']) && $_GET['action'] === 'view') ? 'inline' : 'inline'; // default to inline for viewing
header('Content-Type: application/pdf');
header('Content-Disposition: ' . $disposition . '; filename="' . basename($row['display_name']) . '"');
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
