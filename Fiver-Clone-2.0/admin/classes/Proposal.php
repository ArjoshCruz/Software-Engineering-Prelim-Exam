<?php  
/**
 * Class for handling Proposal-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Proposal extends Database {

    public function createProposal($user_id, $description, $image, $min_price, $max_price) {
        $sql = "INSERT INTO proposals (user_id, description, image, min_price, max_price) VALUES (?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $image, $min_price, $max_price]);
    }

    public function getProposals($id = null) {
        $sql = "SELECT 
                    p.proposal_id,
                    p.user_id AS proposal_user_id,
                    p.description,
                    p.image,
                    p.min_price,
                    p.max_price,
                    p.view_count,
                    p.date_added AS proposals_date_added,
                    u.username,
                    u.contact_number,
                    c.name AS category_name,
                    s.name AS subcategory_name
                FROM proposals p
                JOIN fiverr_clone_users u ON p.user_id = u.user_id
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id";

        if ($id) {
            $sql .= " WHERE p.proposal_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }

        $sql .= " ORDER BY p.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getProposalsByUserID($user_id) {
        $sql = "SELECT p.*, u.*, p.date_added AS proposals_date_added
                FROM proposals p
                JOIN fiverr_clone_users u ON p.user_id = u.user_id
                WHERE p.user_id = ?
                ORDER BY p.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function updateProposal($description, $min_price, $max_price, $proposal_id, $image="") {
        if (!empty($image)) {
            $sql = "UPDATE proposals SET description = ?, image = ?, min_price = ?, max_price = ? WHERE proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $proposal_id]);
        } else {
            $sql = "UPDATE proposals SET description = ?, min_price = ?, max_price = ? WHERE proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $proposal_id]);
        }
    }

    public function addViewCount($proposal_id) {
        $sql = "UPDATE proposals SET view_count = view_count + 1 WHERE proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    public function deleteProposal($id) {
        $sql = "DELETE FROM proposals WHERE proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
        
    public function getProposalsBySubcategory(int $subcategoryId): array {
        $sql = "SELECT Proposals.*, 
                    fiverr_clone_users.username, fiverr_clone_users.display_picture,
                    categories.name AS category_name,
                    subcategories.name AS subcategory_name
                FROM Proposals
                JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id
                LEFT JOIN categories ON Proposals.category_id = categories.category_id
                LEFT JOIN subcategories ON Proposals.subcategory_id = subcategories.subcategory_id
                WHERE Proposals.subcategory_id = ?
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql, [$subcategoryId]);
    }

    public function getProposalsByCategory(int $categoryId): array {
        $sql = "SELECT p.*, u.username, u.contact_number, c.name AS category_name, s.name AS subcategory_name
                FROM proposals p
                JOIN fiverr_clone_users u ON p.user_id = u.user_id
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id
                WHERE p.category_id = ?";
        return $this->executeQuery($sql, [$categoryId]);
    }
}

?>