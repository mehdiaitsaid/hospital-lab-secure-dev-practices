<?php
// File: config.php
// Insecure lab config: hardcoded credentials and secrets on purpose.

# Database configuration (hardcoded for lab)
define('DB_HOST', 'mysql_db');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', 'root_pwd');      // intentionally hardcoded
define('DB_NAME', 'hospital_emr');

# Application settings
define('APP_NAME', 'Hospital EMR - Lab');
define('APP_BASE_URL', 'http://localhost:8080'); // served over HTTP for Phase 3

# Hardcoded API / storage key (intentionally insecure for Phase 4)
define('PAYMENT_API_KEY', 'PAY_labs_key_1234567890');

# Path to a secret key file (also intentionally included in repo under keys/)
define('SECRET_KEY_FILE', __DIR__ . '/secret_key.txt');

# Simple helper to read the secret key file (returns string or null)
function get_secret_key()
{
    $path = SECRET_KEY_FILE;
    if (file_exists($path) && is_readable($path)) {
        $k = trim(file_get_contents($path));
        return $k === '' ? null : $k;
    }
    return null;
}

# Expose a quick config array (optional)
$config = [
    'db' => [
        'host' => DB_HOST,
        'port' => DB_PORT,
        'user' => DB_USER,
        'pass' => DB_PASS,
        'name' => DB_NAME,
    ],
    'app' => [
        'name' => APP_NAME,
        'base_url' => APP_BASE_URL,
    ],
    'payment_key' => PAYMENT_API_KEY,
    'secret_key' => get_secret_key(),
];
