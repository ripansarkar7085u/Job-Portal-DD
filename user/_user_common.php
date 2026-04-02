<?php



require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../config/database.php';

function user_column_exists(mysqli $conn, string $table, string $column): bool
{
    $sql = 'SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();

    return $exists;
}

function user_index_exists(mysqli $conn, string $table, string $indexName): bool
{
    $sql = 'SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('ss', $table, $indexName);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();

    return $exists;
}

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
        user_id INT NOT NULL,
        full_name VARCHAR(255) NOT NULL DEFAULT '',
        job_title VARCHAR(255) NOT NULL DEFAULT '',
        phone VARCHAR(30) NOT NULL DEFAULT '',
        email VARCHAR(255) NOT NULL DEFAULT '',
        website VARCHAR(255) NOT NULL DEFAULT '',
        location VARCHAR(255) NOT NULL DEFAULT '',
        skills TEXT,
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
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_profiles_user (user_id),
        CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);

    if (!user_column_exists($conn, 'profiles', 'user_id')) {
        $conn->query('ALTER TABLE profiles ADD COLUMN user_id INT NULL');
    }

    if (!user_index_exists($conn, 'profiles', 'uniq_profiles_user')) {
        $conn->query('ALTER TABLE profiles ADD UNIQUE KEY uniq_profiles_user (user_id)');
    }

    if (!user_column_exists($conn, 'profiles', 'skills')) {
        $conn->query('ALTER TABLE profiles ADD COLUMN skills TEXT NULL AFTER location');
    }

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
        cover_letter TEXT DEFAULT NULL,
        resume_path VARCHAR(255) DEFAULT NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'applied',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_user_job (user_id, job_id),
        INDEX idx_user_applied (user_id, applied_at),
        CONSTRAINT fk_user_job_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_user_job_applications_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);

    if (!user_column_exists($conn, 'user_job_applications', 'cover_letter')) {
        $conn->query('ALTER TABLE user_job_applications ADD COLUMN cover_letter TEXT NULL AFTER job_id');
    }

    if (!user_column_exists($conn, 'user_job_applications', 'resume_path')) {
        $conn->query('ALTER TABLE user_job_applications ADD COLUMN resume_path VARCHAR(255) NULL AFTER cover_letter');
    }

    $initialized = true;
}

/**
 * @deprecated This function is deprecated. The messaging system now uses the unified 'messages' table.
 * Run api/messages_migration_unified.php to migrate existing data.
 * This function is kept for backward compatibility but should not be used in new code.
 */
function user_ensure_messages_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    // DEPRECATED: This table schema is no longer used.
    // The unified 'messages' table is now used for all messaging.
    // This function ensures the messages table exists with the correct schema.
    
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        sender_type ENUM('user','company') NOT NULL,
        receiver_id INT NOT NULL,
        receiver_type ENUM('user','company') NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_receiver_unread (receiver_id, receiver_type, is_read),
        INDEX idx_conversation (sender_id, sender_type, receiver_id, receiver_type, created_at)
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
function user_ensure_resumes_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }
    $sql = "CREATE TABLE IF NOT EXISTS user_resumes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
        file_name VARCHAR(255) NOT NULL,
        display_name VARCHAR(255) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'Active',
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->query($sql);

    // Add user_id column if it doesn't exist (migrations)
    if (!user_column_exists($conn, 'user_resumes', 'user_id')) {
        $conn->query('ALTER TABLE user_resumes ADD COLUMN user_id INT NULL AFTER id');
        $conn->query('ALTER TABLE user_resumes ADD CONSTRAINT fk_user_resumes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    $initialized = true;
}
