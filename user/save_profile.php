<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
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
    $full_name = trim((string) ($_POST['full_name'] ?? ''));
    $job_title = trim((string) ($_POST['job_title'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $website = trim((string) ($_POST['website'] ?? ''));
    $location = trim((string) ($_POST['location'] ?? ''));
    $skills = trim((string) ($_POST['skills'] ?? ''));
    $salary = trim((string) ($_POST['salary'] ?? ''));
    $experience = trim((string) ($_POST['experience'] ?? ''));
    $age = trim((string) ($_POST['age'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $linkedin = trim((string) ($_POST['linkedin'] ?? ''));
    $github = trim((string) ($_POST['github'] ?? ''));
    $twitter = trim((string) ($_POST['twitter'] ?? ''));
    $facebook = trim((string) ($_POST['facebook'] ?? ''));

    // Keep the current profile image if no new image was uploaded.
    if ($photo_name === '_') {
        $imageStmt = $conn->prepare('SELECT profile_image FROM profiles WHERE user_id = ? LIMIT 1');
        if ($imageStmt) {
            $imageStmt->bind_param('i', $userId);
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();
            $existing = $imageResult ? $imageResult->fetch_assoc() : null;
            if (!empty($existing['profile_image'])) {
                $photo_name = (string) $existing['profile_image'];
            } else {
                $photo_name = '';
            }
            $imageStmt->close();
        } else {
            $photo_name = '';
        }
    }

    // 3. SQL Prepared Statement
    $sql = "INSERT INTO profiles (user_id, full_name, job_title, phone, email, website, location, skills, salary, experience, age, description, linkedin, github, twitter, facebook, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                full_name = VALUES(full_name),
                job_title = VALUES(job_title),
                phone = VALUES(phone),
                email = VALUES(email),
                website = VALUES(website),
                location = VALUES(location),
                skills = VALUES(skills),
                salary = VALUES(salary),
                experience = VALUES(experience),
                age = VALUES(age),
                description = VALUES(description),
                linkedin = VALUES(linkedin),
                github = VALUES(github),
                twitter = VALUES(twitter),
                facebook = VALUES(facebook),
                profile_image = VALUES(profile_image)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssssssssssss",
        $userId,
        $full_name,
        $job_title,
        $phone,
        $email,
        $website,
        $location,
        $skills,
        $salary,
        $experience,
        $age,
        $description,
        $linkedin,
        $github,
        $twitter,
        $facebook,
        $photo_name
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