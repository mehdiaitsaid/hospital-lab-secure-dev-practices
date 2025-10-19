<?php
// File: models/Billing.php
//
// Billing model: list and manipulate billing records.
// Intentionally simple and insecure for the lab.

class Billing
{
    public $id;
    public $patient_id;
    public $appointment_id;
    public $amount;
    public $currency;
    public $status;
    public $description;
    public $created_at;
    public $updated_at;

    public function __construct($row = null)
    {
        if ($row) {
            $this->id = $row['id'];
            $this->patient_id = $row['patient_id'];
            $this->appointment_id = $row['appointment_id'];
            $this->amount = $row['amount'];
            $this->currency = $row['currency'];
            $this->status = $row['status'];
            $this->description = $row['description'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    public static function listForPatient($patient_id)
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $res = $mysqli->query("SELECT * FROM billing WHERE patient_id = $pid ORDER BY created_at DESC");
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = new Billing($r);
        }
        return $rows;
    }

    public static function findById($id)
    {
        global $mysqli;
        $id = (int)$id;
        $res = $mysqli->query("SELECT * FROM billing WHERE id = $id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Billing($row);
        }
        return null;
    }

    public static function create($patient_id, $appointment_id, $amount, $currency = 'USD', $description = '')
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $aid = is_null($appointment_id) ? "NULL" : (int)$appointment_id;
        $amt = (float)$amount;
        $cur = $mysqli->real_escape_string($currency);
        $desc = $mysqli->real_escape_string($description);

        $sql = "INSERT INTO billing (patient_id, appointment_id, amount, currency, description) VALUES ($pid, $aid, $amt, '$cur', '$desc')";
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
        $desc = $mysqli->real_escape_string($this->description);
        $amt = (float)$this->amount;
        $cur = $mysqli->real_escape_string($this->currency);

        $sql = "UPDATE billing SET amount = $amt, currency = '$cur', status = '$status', description = '$desc' WHERE id = $id";
        return $mysqli->query($sql);
    }
}
