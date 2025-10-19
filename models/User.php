<?php
// File: models/User.php
//
// NOTE (intentional for the lab): This model contains insecure patterns on purpose:
// - vulnerable SQL string interpolation (SQLi)
// - plaintext / weak hashing examples (md5) depending on lab phase
// - direct use of global $mysqli without prepared statements
//
// Students will be asked to identify and fix these issues.

class User
{
    public $id;
    public $role_id;
    public $email;
    public $password_hash; // currently stores plaintext
    public $full_name;
    public $phone;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($row = null)
    {
        if ($row) {
            $this->id = $row['id'];
            $this->role_id = $row['role_id'];
            $this->email = $row['email'];
            $this->password_hash = $row['password_hash'];
            $this->full_name = $row['full_name'];
            $this->phone = $row['phone'];
            $this->is_active = $row['is_active'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
        }
    }

    // Vulnerable authentication: interpolates user input directly into SQL.
    // Phase 1 uses plaintext password comparison, Phase 2 may use md5($password).
    public static function authenticate($email, $password)
    {
        global $mysqli; // provided by db_connect.php

        // Insecure: direct interpolation -> SQL injection possible
        // Insecure: comparing password_hash directly (could be plaintext or md5)
        $email_escaped = $mysqli->real_escape_string($email);

        // For Phase 1: password stored in plaintext
        // $sql = "SELECT * FROM users WHERE email = '$email_escaped' AND password_hash = '$password'";

        // For Phase 2 (weak hash): md5 used (insecure)
        // $pw_md5 = md5($password);
        // $sql = "SELECT * FROM users WHERE email = '$email_escaped' AND password_hash = '$pw_md5'";

        // Default lab: plaintext
        $sql = "SELECT * FROM users WHERE email = '$email_escaped' AND password_hash = '$password' LIMIT 1";

        $res = $mysqli->query($sql);
        if ($res && $row = $res->fetch_assoc()) {
            return new User($row);
        }
        return null;
    }

    // Find user by id (insecure: no prepared statements)
    public static function findById($id)
    {
        global $mysqli;
        $id = (int)$id;
        $res = $mysqli->query("SELECT * FROM users WHERE id = $id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new User($row);
        }
        return null;
    }

    // Find by email (insecure - uses interpolation)
    public static function findByEmail($email)
    {
        global $mysqli;
        $email_escaped = $mysqli->real_escape_string($email);
        $res = $mysqli->query("SELECT * FROM users WHERE email = '$email_escaped' LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new User($row);
        }
        return null;
    }

    // Create user (insecure: stores plaintext or md5 based on lab config)
    public static function create($email, $password, $full_name = '', $role_id = null)
    {
        global $mysqli;

        $email_e = $mysqli->real_escape_string($email);
        $name_e = $mysqli->real_escape_string($full_name);
        $role = is_null($role_id) ? "NULL" : (int)$role_id;

        // Default lab stores plaintext (insecure)
        $pw_stored = $password;

        // Example alternate: weak MD5
        // $pw_stored = md5($password);

        $pw_e = $mysqli->real_escape_string($pw_stored);

        $sql = "INSERT INTO users (role_id, email, password_hash, full_name) VALUES ($role, '$email_e', '$pw_e', '$name_e')";
        if ($mysqli->query($sql)) {
            return self::findById($mysqli->insert_id);
        }
        return null;
    }

    // Update user fields (insecure: no validation)
    public function save()
    {
        global $mysqli;
        $id = (int)$this->id;
        $role = is_null($this->role_id) ? "NULL" : (int)$this->role_id;
        $email = $mysqli->real_escape_string($this->email);
        $pw = $mysqli->real_escape_string($this->password_hash);
        $name = $mysqli->real_escape_string($this->full_name);
        $phone = $mysqli->real_escape_string($this->phone);

        $sql = "UPDATE users SET role_id = $role, email = '$email', password_hash = '$pw', full_name = '$name', phone = '$phone' WHERE id = $id";
        return $mysqli->query($sql);
    }

    // Insecure helper to set password (lab versions: plaintext or md5)
    public function setPassword($password)
    {

            $this->password_hash = $password; // plaintext for Phase 1
    }


    // Get the role name of this user
    public function getRoleName()
    {
        global $mysqli;

        if (!$this->role_id) {
            return 'Unknown';
        }

        $role_id = (int)$this->role_id;
        $res = $mysqli->query("SELECT name FROM roles WHERE id = $role_id LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return $row['name'];
        }
        return 'Unknown';
    }

}
