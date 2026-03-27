<?php


require_once __DIR__ . '/../config/database.php';

// Set a default password for all users (for emergency bulk reset only)
$defaultPassword = 'ChangeMe123!'; // Change this to a secure value and notify users
$newHash = password_hash($defaultPassword, PASSWORD_BCRYPT, ['cost' => 6]);

$sql = "UPDATE users SET password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $newHash);
if ($stmt->execute()) {
    echo "All user passwords have been reset to the default password.\n";
    echo "Default password: $defaultPassword\n";
} else {
    echo "Failed to update user passwords: " . $conn->error . "\n";
}
$stmt->close();
$conn->close();
?>
