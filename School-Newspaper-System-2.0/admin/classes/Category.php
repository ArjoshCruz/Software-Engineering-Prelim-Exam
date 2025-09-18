<?php
require_once 'Database.php';

class Category extends Database {

    public function addCategory($name) {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        return $this->executeNonQuery($sql, [$name]);
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_id";
        return $this->executeQuery($sql);
    }

    public function updateCategory($id, $name) {
        $sql = "UPDATE categories SET category_name = ? WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$name, $id]);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}

?>