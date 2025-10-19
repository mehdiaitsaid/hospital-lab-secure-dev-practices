<?php
// File: index.php
// Dashboard / status page for the insecure Hospital EMR starter app.
// Shows server & DB state, key locations, and quick links to login/logout.
// This file is intentionally simple and verbose so students can read and improve it.

require_once __DIR__ . '/db_connect.php'; // loads config.php and creates $mysqli

// Quick helpers (simple and intentionally not wrapped in classes)
function safe_echo($v) {
    echo htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$php_version = phpversion();
$server_time = date('Y-m-d H:i:s');
$app_name = defined('APP_NAME') ? APP_NAME : 'Hospital EMR';
$base_url = defined('APP_BASE_URL') ? APP_BASE_URL : '';

$db_status = 'unknown';
$user_count = 'n/a';
$patient_count = 'n/a';
$session_count = 'n/a';
$tables_list = [];
$secret_key_present = false;
$secret_key_path = defined('SECRET_KEY_FILE') ? SECRET_KEY_FILE : (__DIR__ . '/keys/secret_key.txt');
$payment_key = defined('PAYMENT_API_KEY') ? PAYMENT_API_KEY : null;

// Check DB connection + simple stats (fail gracefully)
if (isset($mysqli) && $mysqli instanceof mysqli) {
    if ($mysqli->connect_errno) {
        $db_status = "Connection failed: ({$mysqli->connect_errno}) " . $mysqli->connect_error;
    } else {
        $db_status = "Connected to MySQL (host: " . DB_HOST . ", db: " . DB_NAME . ")";
        // try to get some stats (wrap in @ to avoid warnings leaking to users)
        try {
            $res = $mysqli->query("SELECT COUNT(*) AS cnt FROM users");
            if ($res && $row = $res->fetch_assoc()) $user_count = (int)$row['cnt'];
        } catch (Exception $e) { $user_count = 'error'; }

        try {
            $res = $mysqli->query("SELECT COUNT(*) AS cnt FROM patients");
            if ($res && $row = $res->fetch_assoc()) $patient_count = (int)$row['cnt'];
        } catch (Exception $e) { $patient_count = 'error'; }

        try {
            $res = $mysqli->query("SELECT COUNT(*) AS cnt FROM sessions");
            if ($res && $row = $res->fetch_assoc()) $session_count = (int)$row['cnt'];
        } catch (Exception $e) { $session_count = 'error'; }

        // list a few tables present
        try {
            $res = $mysqli->query("SHOW TABLES");
            if ($res) {
                while ($r = $res->fetch_row()) {
                    $tables_list[] = $r[0];
                }
            }
        } catch (Exception $e) { /* ignore */ }
    }
} else {
    $db_status = "MySQL client not initialized (check db_connect.php)";
}

// Secret key file presence
if (file_exists($secret_key_path) && is_readable($secret_key_path)) {
    $secret_key_present = true;
    $secret_key_preview = @file_get_contents($secret_key_path);
    $secret_key_preview = trim(substr($secret_key_preview, 0, 64)); // show small preview
} else {
    $secret_key_preview = '(not found)';
}

// Basic server environment info
$server_software = $_SERVER['SERVER_SOFTWARE'] ?? 'unknown';
$server_addr = $_SERVER['SERVER_ADDR'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php safe_echo($app_name); ?> — Status</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 20px; background:#f7f9fb; color:#222; }
        .container { max-width:1000px; margin:0 auto; background:#fff; padding:20px; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.06); }
        h1 { margin-top:0; }
        .section { margin-bottom:18px; padding:12px; border:1px solid #eef1f5; border-radius:6px; background:#fbfdff; }
        .k { color:#555; font-weight:600; display:inline-block; width:220px; }
        .value { color:#111; }
        .top-note { background:#fff9e6; border:1px solid #ffecb5; padding:12px; border-radius:4px; margin-bottom:12px; }
        a.btn { display:inline-block; padding:8px 12px; margin-right:8px; background:#2b7cff; color:#fff; text-decoration:none; border-radius:4px; }
        a.danger { background:#c0392b; }
        pre { background:#f4f6f8; padding:10px; border-radius:4px; overflow:auto; }
        ul.inline { list-style:none; padding:0; margin:0; }
        ul.inline li { display:inline-block; margin-right:8px; }
    </style>
</head>
<body>
<div class="container">
    <h1><?php safe_echo($app_name); ?></h1>

    <div class="top-note">
        <strong>Objective:</strong>
        This application is a learning / practice project intended for students to explore software development best practices (design patterns, modularity, testability) and application security. The codebase is a deliberately simple starter designed to be read, tested, refactored, and secured.
    </div>

    <div style="margin-bottom:12px;">
        <a class="btn" href="<?php safe_echo($base_url . '/login.php'); ?>">Login</a>
        <a class="btn" href="<?php safe_echo($base_url . '/logout.php'); ?>">Logout</a>
        <a class="btn" href="<?php safe_echo($base_url . '/dashboard.php'); ?>">Dashboard</a>
    </div>

    <div class="section">
        <h2>Server & PHP</h2>
        <div><span class="k">PHP Version:</span> <span class="value"><?php safe_echo($php_version); ?></span></div>
        <div><span class="k">Server Software:</span> <span class="value"><?php safe_echo($server_software); ?></span></div>
        <div><span class="k">Server Address:</span> <span class="value"><?php safe_echo($server_addr); ?></span></div>
        <div><span class="k">Current Server Time:</span> <span class="value"><?php safe_echo($server_time); ?></span></div>
    </div>

    <div class="section">
        <h2>Database Status</h2>
        <div><span class="k">Connection:</span> <span class="value"><?php safe_echo($db_status); ?></span></div>
        <div><span class="k">Users (count):</span> <span class="value"><?php safe_echo($user_count); ?></span></div>
        <div><span class="k">Patients (count):</span> <span class="value"><?php safe_echo($patient_count); ?></span></div>
        <div><span class="k">Sessions (count):</span> <span class="value"><?php safe_echo($session_count); ?></span></div>

        <div style="margin-top:8px;">
            <span class="k">Tables detected:</span>
            <span class="value">
          <?php if (!empty($tables_list)): ?>
              <ul class="inline"><?php foreach ($tables_list as $t): ?><li><?php safe_echo($t); ?></li><?php endforeach; ?></ul>
          <?php else: ?>
              (none)
          <?php endif; ?>
        </span>
        </div>
    </div>

    <div class="section">
        <h2>Application Keys & Secrets (in repo)</h2>
        <div><span class="k">Payment API Key (config):</span> <span class="value"><?php safe_echo($payment_key ?? '(not set)'); ?></span></div>
        <div><span class="k">Secret key file path:</span> <span class="value"><?php safe_echo($secret_key_path); ?></span></div>
        <div><span class="k">Secret key present:</span> <span class="value"><?php safe_echo($secret_key_present ? 'yes' : 'no'); ?></span></div>
        <div style="margin-top:8px;"><span class="k">Secret key preview:</span>
            <span class="value"><pre><?php safe_echo($secret_key_preview); ?></pre></span>
        </div>
    </div>

    <div class="section">
        <h2>Quick Links & Utilities</h2>
        <ul>
            <li><a href="<?php safe_echo($base_url . '/login.php'); ?>">/login.php</a> — Login form</li>
            <li><a href="<?php safe_echo($base_url . '/search_doctor.php'); ?>">/search_doctor.php</a> — Search Doctor</li>
            <li><a href="<?php safe_echo($base_url . '/dashboard.php'); ?>">/dashboard.php</a> — App dashboard</li>
            <li><a href="<?php safe_echo($base_url . '/patients.php'); ?>">/patients.php</a> — Patients list</li>
            <li><a href="<?php safe_echo($base_url . '/appointments.php'); ?>">/appointments.php</a> — Appointments</li>
            <li><a href="<?php safe_echo($base_url . '/billing.php'); ?>">/billing.php</a> — Billing</li>
        </ul>
    </div>

    <div class="section">
        <h2>Notes for instructors</h2>
        <ul>
            <li>This starter page intentionally exposes configuration and secret information to make it easy for learners to find and fix insecure patterns.</li>
            <li>Do not deploy this page outside of a controlled lab environment.</li>
            <li>Suggested exercise: have students harden the index page (hide secrets, require auth, validate DB output).</li>
        </ul>
    </div>

    <footer style="text-align:center; margin-top:12px; color:#666;">
        &copy; <?php safe_echo(date('Y')); ?> <?php safe_echo($app_name); ?> — Pr. AIT SAID Mehdi
    </footer>
</div>
</body>
</html>
