<?php
$page_title = "Dashboard";
require_once __DIR__ . '/includes/init.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<h2 class="mb-4">Welcome, <?php echo htmlspecialchars($user->full_name); ?>!</h2>
<p class="text-muted">This dashboard shows the current state of the database and basic server info.</p>

<div class="row mt-4">
    <?php
    // Fetch counts from database
    $tables = [
        'Users' => 'users',
        'Patients' => 'patients',
        'Appointments' => 'appointments',
        'Billing Records' => 'billing',
        'Medical Notes' => 'medical_notes',
        'Lab Results' => 'lab_results',
        'Files' => 'files',
        'Audit Logs' => 'audit_logs'
    ];

    foreach ($tables as $label => $table) {
        $count = 0;
        $res = $mysqli->query("SELECT COUNT(*) as cnt FROM $table");
        if ($res && $row = $res->fetch_assoc()) {
            $count = $row['cnt'];
        }
        echo '<div class="col-md-3 mb-3">';
        echo '<div class="card shadow-sm">';
        echo '<div class="card-body text-center">';
        echo "<h5 class='card-title'>$label</h5>";
        echo "<p class='display-6'>$count</p>";
        echo '</div></div></div>';
    }
    ?>
</div>

<hr>

<h4>Server Info</h4>
<ul class="list-group mb-4">
    <li class="list-group-item"><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
    <li class="list-group-item"><strong>MySQL Version:</strong> <?php echo $mysqli->server_info; ?></li>
    <li class="list-group-item"><strong>Database Name:</strong> <?php echo htmlspecialchars($mysqli->query("SELECT DATABASE()")->fetch_row()[0]); ?></li>
    <li class="list-group-item"><strong>Server OS:</strong> <?php echo php_uname(); ?></li>
</ul>

<hr>

<h4>Quick Links</h4>
<div class="list-group mb-4">
    <a href="login.php" class="list-group-item list-group-item-action">Login Page</a>
    <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
    <a href="patients.php" class="list-group-item list-group-item-action">Patients</a>
    <a href="appointments.php" class="list-group-item list-group-item-action">Appointments</a>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
