<?php
// File: models/Patient.php
//
// Simple patient model using global $mysqli and basic (insecure) queries.
// Designed to be easy to read and to contain common anti-patterns students will fix.

class Patient
{
    public $id;
    public $medical_record_number;
    public $first_name;
    public $last_name;
    public $dob;
    public $gender;
    public $phone;
    public $email;
    public $address;
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $created_at;
    public $updated_at;

    public function __construct($row = null)
    {
        if ($row) {
            $this->id = $row['id'];
            $this->medical_record_number = $row['medical_record_number'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->dob = $row['dob'];
            $this->gender = $row['gender'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->emergency_contact_name = $row['emergency_contact_name'];
            $this->emergency_contact_phone = $row['emergency_contact_phone'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    public static function all()
    {
        global $mysqli;
        $res = $mysqli->query("SELECT * FROM patients ORDER BY last_name, first_name");
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = new Patient($r);
        }
        return $rows;
    }

    public static function findById($id)
    {
        global $mysqli;
        $id = (int)$id;
        $res = $mysqli->query("SELECT * FROM patients WHERE id = $id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Patient($row);
        }
        return null;
    }

    // Very basic create (no validation, no prepared statements)
    public static function create($mrn, $first, $last, $dob = null)
    {
        global $mysqli;
        $mrn_e = $mysqli->real_escape_string($mrn);
        $first_e = $mysqli->real_escape_string($first);
        $last_e = $mysqli->real_escape_string($last);
        $dob_e = $dob ? "'" . $mysqli->real_escape_string($dob) . "'" : "NULL";

        $sql = "INSERT INTO patients (medical_record_number, first_name, last_name, dob) VALUES ('$mrn_e', '$first_e', '$last_e', $dob_e)";
        if ($mysqli->query($sql)) {
            return self::findById($mysqli->insert_id);
        }
        return null;
    }

    public function save()
    {
        global $mysqli;
        $id = (int)$this->id;
        $mrn = $mysqli->real_escape_string($this->medical_record_number);
        $first = $mysqli->real_escape_string($this->first_name);
        $last = $mysqli->real_escape_string($this->last_name);
        $dob = $this->dob ? "'" . $mysqli->real_escape_string($this->dob) . "'" : "NULL";
        $addr = $mysqli->real_escape_string($this->address);

        $sql = "UPDATE patients SET medical_record_number = '$mrn', first_name = '$first', last_name = '$last', dob = $dob, address = '$addr' WHERE id = $id";
        return $mysqli->query($sql);
    }
}
