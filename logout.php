<?php
// File: logout.php
// Simple logout script for the Hospital EMR lab.
// Intentionally minimal so students can later improve session management.

session_start();

// Unset all session variables
$_SESSION = [];

// If there's a session cookie, remove it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Optional: provide a small HTML page and redirect back to login after a brief pause
$redirectTo = 'login.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Logged out — Hospital EMR</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta http-equiv="refresh" content="2;url=<?php echo htmlspecialchars($redirectTo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
    <style>
        body { background:#f4f7fb; font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; }
        .center { height:100vh; display:flex; align-items:center; justify-content:center; }
    </style>
</head>
<body>
<div class="center">
    <div class="card text-center p-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">You have been logged out</h5>
            <p class="card-text text-muted">Redirecting to login...</p>
            <a href="<?php echo htmlspecialchars($redirectTo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" class="btn btn-primary">Go to Login</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
