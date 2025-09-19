<?php
/**
 * Class for handling Offer-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Offer extends Database {

    public function createOffer($user_id, $description, $proposal_id) {
        $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
    }
    
    public function getOffers($offer_id = null) {
        if ($id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added
                FROM offers JOIN fiverr_clone_users ON 
                offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getOffersByProposalID($proposal_id) {
        $sql = "SELECT offers.*, fiverr_clone_users.username, fiverr_clone_users.contact_number, 
                       offers.date_added AS offer_date_added
                FROM offers
                JOIN fiverr_clone_users ON offers.user_id = fiverr_clone_users.user_id
                WHERE offers.proposal_id = ?
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql, [$proposal_id]);
    }

    public function hasSubmittedOffer($user_id, $proposal_id) {
        $sql = "SELECT 1 FROM offers WHERE user_id = ? AND proposal_id = ? LIMIT 1";
        $result = $this->executeQuerySingle($sql, [$user_id, $proposal_id]);
        return $result ? true : false;
    }

    /**
     * Delete an offer
     */
    public function deleteOffer($offer_id) {
        $sql = "DELETE FROM offers WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$offer_id]);
    }

    public function updateOffer($offer_id, $description) {
        $sql = "UPDATE offers SET description = ? WHERE offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }
}
?>
