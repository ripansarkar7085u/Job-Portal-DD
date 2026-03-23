<?php
// 1. Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "job-portal";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $full_name   = $_POST['full_name'];
    $job_title   = $_POST['job_title'];
    $phone       = $_POST['phone'];
    $email       = $_POST['email'];
    $website     = $_POST['website'];
    $location    = $_POST['location'];
    $salary      = $_POST['salary'];
    $experience  = $_POST['experience'];
    $age         = $_POST['age'];
    $description = $_POST['description'];
    $linkedin    = $_POST['linkedin'];
    $github      = $_POST['github'];
    $twitter     = $_POST['twitter'];
    $facebook    = $_POST['facebook'];

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
?>