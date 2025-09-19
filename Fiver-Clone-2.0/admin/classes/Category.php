<?php
require_once 'Database.php';

class Category extends Database
{

    // Adding a category
    public function addCategory(string $name): bool
    {
        $sql = "INSERT INTO categories (name) VALUES (?)";
        return $this->executeNonQuery($sql, [$name]);
    }

    // Adding a subcategory
    public function addSubcategory(int $categoryId, string $name): bool
    {
        $sql = "INSERT INTO subcategories (category_id, name) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$categoryId, $name]);
    }

    // Deleting a category
    public function deleteCategory(int $categoryId): bool
    {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$categoryId]);
    }

    // Deleting a subcategory
    public function deleteSubcategory(int $subcategoryId): bool
    {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategoryId]);
    }

    // Fetching all categories
    public function getCategories(): array
    {
        return $this->executeQuery("SELECT * FROM categories ORDER BY name ASC");
    }

    // Fetching subcategories by category ID
    public function getSubcategoriesByCategory(int $categoryId): array
    {
        return $this->executeQuery(
            "SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC",
            [$categoryId]
        );
    }

    
}
