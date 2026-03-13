<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (!$isLoggedIn) {
    auth_json_response(200, ['logged_in' => false]);
}

if (($_SESSION['account_type'] ?? '') === 'company' && isset($_SESSION['company_id'])) {
    auth_json_response(200, [
        'logged_in' => true,
        'account_type' => 'company',
        'company' => [
            'id' => (int) $_SESSION['company_id'],
            'name' => (string) ($_SESSION['company_name'] ?? ''),
            'email' => (string) ($_SESSION['company_email'] ?? ''),
        ],
    ]);
}

if (isset($_SESSION['user_id'])) {
    auth_json_response(200, [
        'logged_in' => true,
        'account_type' => 'user',
        'user' => [
            'id' => (int) $_SESSION['user_id'],
            'name' => (string) ($_SESSION['user_name'] ?? ''),
            'email' => (string) ($_SESSION['user_email'] ?? ''),
            'type' => (string) ($_SESSION['user_type'] ?? 'candidate'),
        ],
    ]);
}

auth_json_response(200, ['logged_in' => false]);
