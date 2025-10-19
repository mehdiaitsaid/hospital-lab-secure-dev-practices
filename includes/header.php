<?php
// File: includes/header.php
// Header + topbar template
if (!isset($_SESSION)) session_start();

$user_name = $_SESSION['username'] ?? 'Guest';
$user_role = $_SESSION['role_name'] ?? 'Unknown';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($page_title ?? 'Hospital EMR'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page content wrapper -->
    <div id="page-content-wrapper" class="flex-grow-1">
        <!-- Top navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle">☰</button>
                <span class="ms-3 fw-bold"><?php echo htmlspecialchars($page_title ?? 'Dashboard'); ?></span>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3"><?php echo htmlspecialchars($user_name); ?> (<?php echo htmlspecialchars($user_role); ?>)</span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                </div>
            </div>
        </nav>

        <div class="container-fluid mt-3">
