<?php
$page_title = "Patients";
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Patient.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<h2 class="mb-4">Patients List</h2>
<p class="text-muted">This page shows all patients stored in the system.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>MRN</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Emergency Contact</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $patients = Patient::all();
        if ($patients) {
            $i = 1;
            foreach ($patients as $p) {
                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($p->medical_record_number) . '</td>';
                echo '<td>' . htmlspecialchars($p->first_name) . '</td>';
                echo '<td>' . htmlspecialchars($p->last_name) . '</td>';
                echo '<td>' . htmlspecialchars($p->dob) . '</td>';
                echo '<td>' . htmlspecialchars(ucfirst($p->gender)) . '</td>';
                echo '<td>' . htmlspecialchars($p->phone) . '</td>';
                echo '<td>' . htmlspecialchars($p->email) . '</td>';
                echo '<td>' . htmlspecialchars($p->address) . '</td>';
                echo '<td>' . htmlspecialchars($p->emergency_contact_name) . ' (' . htmlspecialchars($p->emergency_contact_phone) . ')</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="10" class="text-center text-muted">No patients found</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
