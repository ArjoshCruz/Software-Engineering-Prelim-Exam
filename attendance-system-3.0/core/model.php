<?php
require_once 'database.php';

class Model extends Database {
    protected $table;

    public function __construct() {
        parent::__construct(); // This is used for connecting to database
    }

    // CREATE
    public function create($table, $data) {
        $keys = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO $table ($keys) VALUES ($placeholders)");
        return $stmt->execute($data);
    }

    // READ (either all records or a single record based on ID)
    public function read($table, $id = null) {
        if ($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM $table");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // UPDATE
    public function update($table, $data, $id) {
        $fields = "";
        foreach($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $stmt = $this->pdo->prepare("UPDATE $table SET $fields WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // DELETE
    public function delete($table, $id) {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

?>