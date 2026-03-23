<?php

require_once __DIR__ . '/../config/database.php';

function user_require_login(): int
{
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'user' || !isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }

    return (int) $_SESSION['user_id'];
}

function user_esc(?string $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function user_ensure_profiles_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL DEFAULT '',
        job_title VARCHAR(255) NOT NULL DEFAULT '',
        phone VARCHAR(30) NOT NULL DEFAULT '',
        email VARCHAR(255) NOT NULL DEFAULT '',
        website VARCHAR(255) NOT NULL DEFAULT '',
        location VARCHAR(255) NOT NULL DEFAULT '',
        salary VARCHAR(50) NOT NULL DEFAULT '',
        experience VARCHAR(50) NOT NULL DEFAULT '',
        age VARCHAR(10) NOT NULL DEFAULT '',
        description TEXT,
        linkedin VARCHAR(255) NOT NULL DEFAULT '',
        github VARCHAR(255) NOT NULL DEFAULT '',
        twitter VARCHAR(255) NOT NULL DEFAULT '',
        facebook VARCHAR(255) NOT NULL DEFAULT '',
        profile_image VARCHAR(255) NOT NULL DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}

function user_ensure_applications_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS user_job_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_id INT NOT NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'applied',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_job (user_id, job_id),
        INDEX idx_user_applied (user_id, applied_at),
        CONSTRAINT fk_user_job_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_user_job_applications_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}

function user_ensure_messages_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS user_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        company_id INT NOT NULL,
        sender_type ENUM('user', 'company') NOT NULL,
        message_text TEXT NOT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_company_time (user_id, company_id, created_at),
        CONSTRAINT fk_user_messages_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_user_messages_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}

function user_ensure_alerts_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS user_alerts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        related_job_id INT DEFAULT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        alert_type VARCHAR(50) NOT NULL DEFAULT 'general',
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_alerts_time (user_id, created_at),
        CONSTRAINT fk_user_alerts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_user_alerts_job FOREIGN KEY (related_job_id) REFERENCES jobs(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}
