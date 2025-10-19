<?php
// File: login.php
// Insecure lab login form using User::authenticate($email, $password)
// Uses Bootstrap CDN as requested.

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/models/User.php';

session_start();

// If already logged in, send to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Please enter email and password.';
    } else {
        // Use the User model's authenticate method (intentionally insecure in this lab)
        $user = User::authenticate($email, $password);

        if ($user !== null) {
            // Basic session setup
            $_SESSION['user_id'] = $user->id;
            // Prefer storing a friendly name for display
            $_SESSION['username'] = $user->full_name ?: $user->email;

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Invalid email or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login — Hospital EMR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN (you provided) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* small local tweaks for demo */
        body { background: linear-gradient(135deg,#eaf3ff 0%,#f8fbff 100%); font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
        .card { border-radius: 12px; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg" style="width: 420px;">
    <div class="card-header text-center bg-primary text-white">
        <h4 class="mb-0">Hospital EMR — Login</h4>
    </div>

    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email"  name="email" id="email" class="form-control" placeholder="user@example.local" required value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : 'admin@lab.local'; ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" value="123456" name="password" id="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
        </form>
    </div>

    <div class="card-footer text-center">
        <small class="text-muted">Demo starter — do not use in production</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
