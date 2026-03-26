<?php

require_once __DIR__ . '/../config/database.php';

if (!defined('AUTH_EXCEPTION_HANDLER_SET')) {
    define('AUTH_EXCEPTION_HANDLER_SET', true);

    set_exception_handler(function (Throwable $e): void {
        error_log('Auth API exception: ' . $e->getMessage());

        if (!headers_sent()) {
            auth_set_security_headers();
        }

        auth_json_response(500, [
            'success' => false,
            'message' => 'Server error while processing authentication request.',
        ]);
    });
}

function auth_set_security_headers(): void
{
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: same-origin');
}

function auth_json_response(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function auth_app_base_path(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    if ($scriptName === '') {
        return '';
    }

    // Example: /Job-Portal-DD/api/login.php -> /Job-Portal-DD
    $base = preg_replace('#/api/[^/]+$#', '', $scriptName);
    if (!is_string($base)) {
        return '';
    }

    if ($base === '/' || $base === '.') {
        return '';
    }

    return rtrim($base, '/');
}

function auth_path(string $path): string
{
    $normalized = '/' . ltrim($path, '/');
    $base = auth_app_base_path();
    return $base . $normalized;
}

function auth_set_cookie_compat(string $name, string $value, int $expires): void
{
    $isSecure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    if (PHP_VERSION_ID >= 70300) {
        setcookie($name, $value, [
            'expires' => $expires,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        return;
    }

    // PHP < 7.3 does not support array options for setcookie.
    $path = '/; samesite=Lax';
    setcookie($name, $value, $expires, $path, '', $isSecure, true);
}

function auth_require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        auth_json_response(405, ['success' => false, 'message' => 'Method not allowed.']);
    }
}

function auth_get_request_data(): array
{
    if (!empty($_POST)) {
        return $_POST;
    }

    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function auth_normalize_email(string $email): string
{
    return strtolower(trim($email));
}

function auth_validate_same_origin(): bool
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '') {
        return false;
    }

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if ($origin !== '') {
        $originHost = parse_url($origin, PHP_URL_HOST);
        return is_string($originHost) && strcasecmp($originHost, preg_replace('/:\\d+$/', '', $host)) === 0;
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if ($referer !== '') {
        $refererHost = parse_url($referer, PHP_URL_HOST);
        return is_string($refererHost) && strcasecmp($refererHost, preg_replace('/:\\d+$/', '', $host)) === 0;
    }

    
    return true;
}

function auth_password_errors(string $password): array
{
    $errors = [];

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if (strlen($password) > 128) {
        $errors[] = 'Password is too long.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must include at least one uppercase letter.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must include at least one lowercase letter.';
    }
    if (!preg_match('/\d/', $password)) {
        $errors[] = 'Password must include at least one number.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must include at least one special character.';
    }

    return $errors;
}

function auth_validate_phone(string $phone): bool
{
    if ($phone === '') {
        return true;
    }

    $normalized = preg_replace('/[^\d+]/', '', $phone);
    if ($normalized === null) {
        return false;
    }

    return (bool) preg_match('/^\+?[0-9]{10,15}$/', $normalized);
}

function auth_get_client_ip(): string
{
    $candidates = [
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
        $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        $_SERVER['REMOTE_ADDR'] ?? null,
    ];

    foreach ($candidates as $candidate) {
        if ($candidate === null || trim($candidate) === '') {
            continue;
        }

        $first = explode(',', $candidate)[0];
        $first = trim($first);
        if (filter_var($first, FILTER_VALIDATE_IP)) {
            return $first;
        }
    }

    return '0.0.0.0';
}

function auth_ensure_rate_limit_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS auth_rate_limits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        scope VARCHAR(40) NOT NULL,
        key_hash CHAR(64) NOT NULL,
        attempt_count INT NOT NULL DEFAULT 0,
        window_started_at DATETIME NOT NULL,
        blocked_until DATETIME DEFAULT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_scope_key (scope, key_hash),
        INDEX idx_blocked_until (blocked_until)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}

function auth_table_exists(mysqli $conn, string $tableName): bool
{
    $sql = 'SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('s', $tableName);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_row();
    $stmt->close();

    return $exists;
}

function auth_ensure_core_tables(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            user_type ENUM('candidate','employer') DEFAULT 'candidate',
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS companies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            industry VARCHAR(100) DEFAULT NULL,
            website VARCHAR(255) DEFAULT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        "CREATE TABLE IF NOT EXISTS remember_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT DEFAULT NULL,
            company_id INT DEFAULT NULL,
            token VARCHAR(64) NOT NULL,
            expires_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_token (token)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    foreach ($queries as $sql) {
        $conn->query($sql);
    }

    $initialized = true;
}

<<<<<<< Updated upstream
function auth_ensure_jobs_table(mysqli $conn): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    $sql = "CREATE TABLE IF NOT EXISTS jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        company_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        employment_type VARCHAR(50) NOT NULL,
        experience_level VARCHAR(50) DEFAULT NULL,
        category VARCHAR(100) DEFAULT NULL,
        work_style VARCHAR(50) DEFAULT NULL,
        location VARCHAR(255) DEFAULT NULL,
        salary_min DECIMAL(12,2) DEFAULT NULL,
        salary_max DECIMAL(12,2) DEFAULT NULL,
        salary_period VARCHAR(20) DEFAULT 'year',
        currency VARCHAR(10) DEFAULT 'USD',
        salary_visible TINYINT(1) NOT NULL DEFAULT 1,
        description TEXT NOT NULL,
        requirements TEXT NOT NULL,
        status ENUM('draft','published','closed') NOT NULL DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_company_id (company_id),
        INDEX idx_status_created (status, created_at),
        CONSTRAINT fk_jobs_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->query($sql);
    $initialized = true;
}

=======
>>>>>>> Stashed changes
function auth_rate_limit_check(mysqli $conn, string $scope, string $key, int $maxAttempts = 6, int $windowSeconds = 900, int $blockSeconds = 900): bool
{
    auth_ensure_rate_limit_table($conn);

    $keyHash = hash('sha256', $key);
    $now = new DateTimeImmutable('now');

    $select = $conn->prepare('SELECT attempt_count, window_started_at, blocked_until FROM auth_rate_limits WHERE scope = ? AND key_hash = ?');
    if (!$select) {
        return true;
    }

    $select->bind_param('ss', $scope, $keyHash);
    $select->execute();
    $result = $select->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $select->close();

    if (!$row) {
        $windowStart = $now->format('Y-m-d H:i:s');
        $insert = $conn->prepare('INSERT INTO auth_rate_limits (scope, key_hash, attempt_count, window_started_at, blocked_until) VALUES (?, ?, 0, ?, NULL)');
        if ($insert) {
            $insert->bind_param('sss', $scope, $keyHash, $windowStart);
            $insert->execute();
            $insert->close();
        }

        return true;
    }

    if (!empty($row['blocked_until'])) {
        $blockedUntil = new DateTimeImmutable($row['blocked_until']);
        if ($blockedUntil > $now) {
            return false;
        }
    }

    $windowStart = new DateTimeImmutable($row['window_started_at']);
    if (($now->getTimestamp() - $windowStart->getTimestamp()) > $windowSeconds) {
        $reset = $conn->prepare('UPDATE auth_rate_limits SET attempt_count = 0, window_started_at = ?, blocked_until = NULL WHERE scope = ? AND key_hash = ?');
        if ($reset) {
            $windowStartStr = $now->format('Y-m-d H:i:s');
            $reset->bind_param('sss', $windowStartStr, $scope, $keyHash);
            $reset->execute();
            $reset->close();
        }

        return true;
    }

    return ((int) $row['attempt_count']) < $maxAttempts;
}

function auth_rate_limit_record_failure(mysqli $conn, string $scope, string $key, int $maxAttempts = 6, int $windowSeconds = 900, int $blockSeconds = 900): void
{
    auth_ensure_rate_limit_table($conn);

    $keyHash = hash('sha256', $key);
    $now = new DateTimeImmutable('now');

    $select = $conn->prepare('SELECT id, attempt_count, window_started_at FROM auth_rate_limits WHERE scope = ? AND key_hash = ?');
    if (!$select) {
        return;
    }

    $select->bind_param('ss', $scope, $keyHash);
    $select->execute();
    $result = $select->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $select->close();

    if (!$row) {
        $attempts = 1;
        $windowStart = $now->format('Y-m-d H:i:s');
        $blockedUntil = null;

        if ($attempts >= $maxAttempts) {
            $blockedUntil = $now->modify('+' . $blockSeconds . ' seconds')->format('Y-m-d H:i:s');
        }

        $insert = $conn->prepare('INSERT INTO auth_rate_limits (scope, key_hash, attempt_count, window_started_at, blocked_until) VALUES (?, ?, ?, ?, ?)');
        if ($insert) {
            $insert->bind_param('ssiss', $scope, $keyHash, $attempts, $windowStart, $blockedUntil);
            $insert->execute();
            $insert->close();
        }

        return;
    }

    $windowStart = new DateTimeImmutable($row['window_started_at']);
    $attempts = (int) $row['attempt_count'];

    if (($now->getTimestamp() - $windowStart->getTimestamp()) > $windowSeconds) {
        $attempts = 1;
        $windowStart = $now;
    } else {
        $attempts++;
    }

    $blockedUntil = null;
    if ($attempts >= $maxAttempts) {
        $blockedUntil = $now->modify('+' . $blockSeconds . ' seconds')->format('Y-m-d H:i:s');
    }

    $update = $conn->prepare('UPDATE auth_rate_limits SET attempt_count = ?, window_started_at = ?, blocked_until = ? WHERE scope = ? AND key_hash = ?');
    if ($update) {
        $windowStartStr = $windowStart->format('Y-m-d H:i:s');
        $update->bind_param('issss', $attempts, $windowStartStr, $blockedUntil, $scope, $keyHash);
        $update->execute();
        $update->close();
    }
}

function auth_rate_limit_clear(mysqli $conn, string $scope, string $key): void
{
    $keyHash = hash('sha256', $key);
    $delete = $conn->prepare('DELETE FROM auth_rate_limits WHERE scope = ? AND key_hash = ?');
    if ($delete) {
        $delete->bind_param('ss', $scope, $keyHash);
        $delete->execute();
        $delete->close();
    }
}

function auth_start_user_session(array $payload): void
{
    session_regenerate_id(true);

    $_SESSION['logged_in'] = true;
    $_SESSION['account_type'] = 'user';
    $_SESSION['user_id'] = (int) $payload['id'];
    $_SESSION['user_name'] = $payload['full_name'];
    $_SESSION['user_email'] = $payload['email'];
    $_SESSION['user_type'] = $payload['user_type'] ?? 'candidate';

    unset($_SESSION['company_id'], $_SESSION['company_name'], $_SESSION['company_email']);
}

function auth_start_company_session(array $payload): void
{
    session_regenerate_id(true);

    $_SESSION['logged_in'] = true;
    $_SESSION['account_type'] = 'company';
    $_SESSION['company_id'] = (int) $payload['id'];
    $_SESSION['company_name'] = $payload['company_name'];
    $_SESSION['company_email'] = $payload['email'];

    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['user_type']);
}

function auth_issue_remember_token(mysqli $conn, string $accountType, int $accountId, int $ttlDays = 30): void
{
    auth_ensure_core_tables($conn);

    $rawToken = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $rawToken);
    $expiresAt = (new DateTimeImmutable('now'))->modify('+' . $ttlDays . ' days')->format('Y-m-d H:i:s');

    if ($accountType === 'user') {
        $clear = $conn->prepare('DELETE FROM remember_tokens WHERE user_id = ?');
        if ($clear) {
            $clear->bind_param('i', $accountId);
            $clear->execute();
            $clear->close();
        }

        $insert = $conn->prepare('INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
    } else {
        $clear = $conn->prepare('DELETE FROM remember_tokens WHERE company_id = ?');
        if ($clear) {
            $clear->bind_param('i', $accountId);
            $clear->execute();
            $clear->close();
        }

        $insert = $conn->prepare('INSERT INTO remember_tokens (company_id, token, expires_at) VALUES (?, ?, ?)');
    }

    if ($insert) {
        $insert->bind_param('iss', $accountId, $tokenHash, $expiresAt);
        $insert->execute();
        $insert->close();
    }

    auth_set_cookie_compat('remember_token', $rawToken, time() + ($ttlDays * 86400));
}

function auth_clear_remember_token(mysqli $conn): void
{
    auth_ensure_core_tables($conn);

    if (!empty($_COOKIE['remember_token'])) {
        $tokenHash = hash('sha256', (string) $_COOKIE['remember_token']);

        $delete = $conn->prepare('DELETE FROM remember_tokens WHERE token = ?');
        if ($delete) {
            $delete->bind_param('s', $tokenHash);
            $delete->execute();
            $delete->close();
        }
    }

    auth_set_cookie_compat('remember_token', '', time() - 3600);
}
     
