<?php
// File: models/LabResult.php
//
// Simple lab result model.

class LabResult
{
    public $id;
    public $patient_id;
    public $ordered_by_id;
    public $performed_by_id;
    public $appointment_id;
    public $test_code;
    public $result_text;
    public $result_value;
    public $result_unit;
    public $status;
    public $recorded_at;
    public $created_at;
    public $updated_at;

    public function __construct($row = null)
    {
        if ($row) {
            $this->id = $row['id'];
            $this->patient_id = $row['patient_id'];
            $this->ordered_by_id = $row['ordered_by_id'];
            $this->performed_by_id = $row['performed_by_id'];
            $this->appointment_id = $row['appointment_id'];
            $this->test_code = $row['test_code'];
            $this->result_text = $row['result_text'];
            $this->result_value = $row['result_value'];
            $this->result_unit = $row['result_unit'];
            $this->status = $row['status'];
            $this->recorded_at = $row['recorded_at'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    public static function forPatient($patient_id)
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $res = $mysqli->query("SELECT * FROM lab_results WHERE patient_id = $pid ORDER BY created_at DESC");
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = new LabResult($r);
        }
        return $rows;
    }

    public static function findById($id)
    {
        global $mysqli;
        $id = (int)$id;
        $res = $mysqli->query("SELECT * FROM lab_results WHERE id = $id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new LabResult($row);
        }
        return null;
    }

    public static function create($patient_id, $test_code, $result_text = '', $result_value = null, $result_unit = '')
    {
        global $mysqli;
        $pid = (int)$patient_id;
        $tc = $mysqli->real_escape_string($test_code);
        $rt = $mysqli->real_escape_string($result_text);
        $rv = $result_value ? "'" . $mysqli->real_escape_string($result_value) . "'" : "NULL";
        $ru = $mysqli->real_escape_string($result_unit);

        $sql = "INSERT INTO lab_results (patient_id, test_code, result_text, result_value, result_unit) VALUES ($pid, '$tc', '$rt', $rv, '$ru')";
        if ($mysqli->query($sql)) {
            return self::findById($mysqli->insert_id);
        }
        return null;
    }

    public function save()
    {
        global $mysqli;
        $id = (int)$this->id;
        $rt = $mysqli->real_escape_string($this->result_text);
        $rv = $this->result_value ? "'" . $mysqli->real_escape_string($this->result_value) . "'" : "NULL";
        $ru = $mysqli->real_escape_string($this->result_unit);
        $status = $mysqli->real_escape_string($this->status);

        $sql = "UPDATE lab_results SET result_text = '$rt', result_value = $rv, result_unit = '$ru', status = '$status' WHERE id = $id";
        return $mysqli->query($sql);
    }
}
