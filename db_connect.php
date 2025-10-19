<?php
// File: db_connect.php
// Simple mysqli connection using the (insecure) hardcoded config from config.php
// Intentionally minimal; meant to be refactored by students.

require_once __DIR__ . '/config.php';

// turn on errors for development (insecure to show errors in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Create a mysqli connection and assign to global $mysqli
 * On failure the script will exit (simple behaviour for lab).
 */
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($mysqli->connect_errno) {
    // For lab visibility we show the error (insecure practice)
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Ensure UTF-8 (utf8mb4) for the connection
if (! $mysqli->set_charset('utf8mb4')) {
    // not fatal for the lab, but log
    error_log("Warning: could not set mysql charset to utf8mb4: " . $mysqli->error);
}

// Simple helper to run a query and die on error (convenience for the lab)
function db_query_or_die($sql)
{
    global $mysqli;
    $res = $mysqli->query($sql);
    if ($res === false) {
        die("Database query error: " . $mysqli->error . "\nSQL: " . $sql);
    }
    return $res;
}

// Example: optional auto-import of schema if DB is empty (commented out — instructor can enable)
// $res = $mysqli->query("SHOW TABLES LIKE 'users'");
// if ($res && $res->num_rows === 0) {
//     // The instructor may provide an SQL import step instead of doing it here.
//     // die("Database appears empty. Please import sql/hospital_emr.sql before running the app.");
// }
