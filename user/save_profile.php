<?php
require_once __DIR__ . '/_user_common.php';

user_ensure_profiles_table($conn);

// 2. Process Form Submission
if (isset($_POST['save_all'])) {
    
    // Handle Image Upload
    $photo_name = "_"; // Default placeholder
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $photo_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_dir . $photo_name);
    }

    // Capture POST data
    $full_name   = trim((string) ($_POST['full_name'] ?? ''));
    $job_title   = trim((string) ($_POST['job_title'] ?? ''));
    $phone       = trim((string) ($_POST['phone'] ?? ''));
    $email       = trim((string) ($_POST['email'] ?? ''));
    $website     = trim((string) ($_POST['website'] ?? ''));
    $location    = trim((string) ($_POST['location'] ?? ''));
    $salary      = trim((string) ($_POST['salary'] ?? ''));
    $experience  = trim((string) ($_POST['experience'] ?? ''));
    $age         = trim((string) ($_POST['age'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $linkedin    = trim((string) ($_POST['linkedin'] ?? ''));
    $github      = trim((string) ($_POST['github'] ?? ''));
    $twitter     = trim((string) ($_POST['twitter'] ?? ''));
    $facebook    = trim((string) ($_POST['facebook'] ?? ''));

    // 3. SQL Prepared Statement
    $sql = "INSERT INTO profiles (full_name, job_title, phone, email, website, location, salary, experience, age, description, linkedin, github, twitter, facebook, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssss", 
        $full_name, $job_title, $phone, $email, $website, $location, 
        $salary, $experience, $age, $description, $linkedin, $github, $twitter, $facebook, $photo_name
    );

    if ($stmt->execute()) {
        header("Location: profile.php?status=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: profile.php");
exit();
?>