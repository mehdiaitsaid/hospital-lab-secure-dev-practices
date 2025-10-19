<?php
$page_title = "Appointments";
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Appointment.php';
require_once __DIR__ . '/models/Patient.php';
require_once __DIR__ . '/models/User.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<h2 class="mb-4">Appointments</h2>
<p class="text-muted">This page shows all appointments with their patient and clinician information.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Scheduled At</th>
            <th>Patient</th>
            <th>Clinician</th>
            <th>Status</th>
            <th>Reason</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $appointments = Appointment::allWithDetails();
        if ($appointments) {
            $i = 1;
            foreach ($appointments as $a) {
                $appt = $a['appointment'];
                $patient = $a['patient'];
                $clinician = $a['clinician'];

                echo '<tr>';
                echo '<td>' . $i++ . '</td>';
                echo '<td>' . htmlspecialchars($appt->scheduled_at) . '</td>';

                if ($patient) {
                    echo '<td>' . htmlspecialchars($patient->first_name . ' ' . $patient->last_name) .
                        ' (' . htmlspecialchars($patient->medical_record_number) . ')</td>';
                } else {
                    echo '<td class="text-muted">Unknown</td>';
                }

                if ($clinician) {
                    echo '<td>' . htmlspecialchars($clinician->full_name) . '</td>';
                } else {
                    echo '<td class="text-muted">Unassigned</td>';
                }

                echo '<td>' . htmlspecialchars(ucfirst($appt->status)) . '</td>';
                echo '<td>' . htmlspecialchars($appt->reason) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6" class="text-center text-muted">No appointments found</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
