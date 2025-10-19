<?php
// File: models/Appointment.php
//
// Appointment model with basic query functions.

class Appointment
{
    public $id;
    public $patient_id;
    public $clinician_id;
    public $scheduled_at;
    public $status;
    public $reason;
    public $created_at;
    public $updated_at;

    public function __construct($row = null)
    {
        if ($row) {
            $this->id = $row['id'];
            $this->patient_id = $row['patient_id'];
            $this->clinician_id = $row['clinician_id'];
            $this->scheduled_at = $row['scheduled_at'];
            $this->status = $row['status'];
            $this->reason = $row['reason'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    public static function forPatient($patient_id)
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $res = $mysqli->query("SELECT * FROM appointments WHERE patient_id = $pid ORDER BY scheduled_at DESC");
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = new Appointment($r);
        }
        return $rows;
    }

    /**
     * Get all appointments with patient and clinician info
     * Returns an array of associative arrays with keys:
     * - appointment: Appointment object
     * - patient: Patient object
     * - clinician: User object (can be null if no clinician assigned)
     */
    public static function allWithDetails()
    {
        global $mysqli;

        $sql = "SELECT a.*, 
                   p.id as p_id, p.medical_record_number, p.first_name as p_first, p.last_name as p_last, p.dob as p_dob, p.gender as p_gender,
                   u.id as u_id, u.full_name as u_name, u.email as u_email
            FROM appointments a
            LEFT JOIN patients p ON a.patient_id = p.id
            LEFT JOIN users u ON a.clinician_id = u.id
            ORDER BY a.scheduled_at DESC";

        $res = $mysqli->query($sql);
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $appointment = new Appointment($r);
            $patient = null;
            if ($r['p_id']) {
                $patient = new Patient([
                    'id' => $r['p_id'],
                    'medical_record_number' => $r['medical_record_number'],
                    'first_name' => $r['p_first'],
                    'last_name' => $r['p_last'],
                    'dob' => $r['p_dob'],
                    'gender' => $r['p_gender'],
                    'phone' => null,
                    'email' => $r['u_email'] ?? null,
                    'address' => null,
                    'emergency_contact_name' => null,
                    'emergency_contact_phone' => null,
                    'created_at' => null,
                    'updated_at' => null
                ]);
            }

            $clinician = null;
            if ($r['u_id']) {
                $clinician = User::findById($r['u_id']);
            }

            $rows[] = [
                'appointment' => $appointment,
                'patient' => $patient,
                'clinician' => $clinician
            ];
        }

        return $rows;
    }


    public static function findById($id)
    {
        global $mysqli;
        $id = (int)$id;
        $res = $mysqli->query("SELECT * FROM appointments WHERE id = $id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Appointment($row);
        }
        return null;
    }

    public static function create($patient_id, $clinician_id, $scheduled_at, $reason = '')
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $cid = is_null($clinician_id) ? "NULL" : (int)$clinician_id;
        $sched = $mysqli->real_escape_string($scheduled_at);
        $reason_e = $mysqli->real_escape_string($reason);

        $sql = "INSERT INTO appointments (patient_id, clinician_id, scheduled_at, reason) VALUES ($pid, $cid, '$sched', '$reason_e')";
        if ($mysqli->query($sql)) {
            return self::findById($mysqli->insert_id);
        }
        return null;
    }

    public function save()
    {
        global $mysqli;
        $id = (int)$this->id;
        $status = $mysqli->real_escape_string($this->status);
        $reason = $mysqli->real_escape_string($this->reason);
        $sched = $mysqli->real_escape_string($this->scheduled_at);

        $sql = "UPDATE appointments SET scheduled_at = '$sched', status = '$status', reason = '$reason' WHERE id = $id";
        return $mysqli->query($sql);
    }
}
