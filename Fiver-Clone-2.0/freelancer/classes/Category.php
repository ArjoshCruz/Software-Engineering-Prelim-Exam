<?php
require_once 'Database.php';

class Category extends Database
{
    /**
     * Fetch all categories.
     */
    public function getCategories(): array
    {
        return $this->executeQuery("SELECT * FROM categories ORDER BY name ASC");
    }

    /**
     * Fetch all subcategories of a category.
     */
    public function getSubcategoriesByCategory(int $categoryId): array
    {
        return $this->executeQuery(
            "SELECT * FROM subcategories WHERE category_id = ? ORDER BY name ASC",
            [$categoryId]
        );
    }
}
