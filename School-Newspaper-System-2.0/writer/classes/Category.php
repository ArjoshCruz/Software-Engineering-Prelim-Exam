<?php
require_once __DIR__ . '/Database.php';

class Category extends Database {

    // Writers can only view categories
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        return $this->executeQuery($sql);
    }

}

?>