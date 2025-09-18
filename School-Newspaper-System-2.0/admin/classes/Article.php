<?php  

require_once 'Database.php';
require_once 'User.php';
require_once 'Notification.php';
/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database {
    private $notificationObj;

    public function __construct() {
        parent::__construct();
        $this->notificationObj = new Notification();
    }
    /**
     * Creates a new article.
     * @param string $title The article title.
     * @param string $content The article content.
     * @param int $author_id The ID of the author.
     * @return int The ID of the newly created article.
     */
    public function createArticle($title, $content, $author_id, $category_id, $imagePath = null) {
        $sql = "INSERT INTO articles (title, content, author_id, category_id, is_active, image_path)
                VALUES (?, ?, ?, ?, 0, ?)";
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $category_id, $imagePath]);
    }

    /**
     * Retrieves articles from the database.
     * @param int|null $id The article ID to retrieve, or null for all articles.
     * @return array
     */
    public function getArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                ORDER BY articles.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null) {
        if ($id) {
            $sql = "SELECT a.*, c.category_name, u.username 
                    FROM articles a
                    JOIN school_publication_users u 
                        ON a.author_id = u.user_id
                    JOIN categories c 
                        ON a.category_id = c.category_id
                    WHERE a.article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }

        $sql = "SELECT a.*, c.category_name, u.username, u.is_admin 
                FROM articles a
                JOIN school_publication_users u ON a.author_id = u.user_id
                JOIN categories c ON a.category_id = c.category_id
                WHERE a.is_active = 1
                ORDER BY a.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getArticlesByCategory($category_id) {
        $sql = "SELECT a.*, c.category_name, u.username 
                FROM articles a
                JOIN categories c ON a.category_id = c.category_id
                JOIN school_publication_users u ON a.author_id = u.user_id
                WHERE a.is_active = 1 AND a.category_id = ?
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql, [$category_id]);
    }



    public function getArticlesByUserID($user_id) {
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an article.
     * @param int $id The article ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @return int The number of affected rows.
     */
    public function updateArticle($id, $title, $content, $category_id, $imagePath = null) {
        if ($imagePath !== null) {
            $sql = "UPDATE articles SET title = ?, content = ?, category_id = ?, image_path = ? 
                    WHERE article_id = ?";
            return $this->executeNonQuery($sql, [$title, $content, $category_id, $imagePath, $id]);
        }

        $sql = "UPDATE articles SET title = ?, content = ?, category_id = ?
                WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $category_id, $id]);
    }
    
    /**
     * Toggles the visibility (is_active status) of an article.
     * This operation is restricted to admin users only.
     * @param int $id The article ID to update.
     * @param bool $is_active The new visibility status.
     * @return int The number of affected rows.
     */
    public function updateArticleVisibility($id, $is_active) {
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$is_active, $id]);
    }


    /**
     * Deletes an article.
     * @param int $id The article ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    /* -------------------------------
     *   EDIT REQUEST METHODS
     * ------------------------------- */

    // Insert a new edit request and notify the author
    public function requestEdit(int $article_id, int $requester_id, ?string $message = null): bool {
        $article = $this->executeQuerySingle(
            "SELECT title, author_id FROM articles WHERE article_id = ?", 
            [$article_id]
        );
        if (!$article) return false;

        $author_id = (int)$article['author_id'];
        $title = $article['title'];

        if ($author_id === $requester_id) return false; // can’t request own article

        $existing = $this->executeQuerySingle(
            "SELECT request_id FROM edit_requests 
             WHERE article_id = ? AND requester_id = ? AND status = 'pending'",
            [$article_id, $requester_id]
        );
        if ($existing) return false;

        $sql = "INSERT INTO edit_requests (article_id, requester_id, author_id, status, message) 
                VALUES (?, ?, ?, 'pending', ?)";
        $ok = $this->executeNonQuery($sql, [$article_id, $requester_id, $author_id, $message]);

        if ($ok) {
            $notifMsg = "Writer {$this->getUsername($requester_id)} requested to edit your article: \"{$title}\".";
            $this->notificationObj->sendNotification($author_id, $notifMsg);
        }

        return (bool)$ok;
    }

    // Author responds to request
    public function respondToRequest(int $request_id, int $author_id, string $response): bool {
        if (!in_array($response, ['accepted', 'rejected'])) return false;

        $row = $this->executeQuerySingle(
            "SELECT r.requester_id, r.article_id, a.title 
             FROM edit_requests r 
             JOIN articles a ON r.article_id = a.article_id
             WHERE r.request_id = ? AND r.author_id = ?",
            [$request_id, $author_id]
        );
        if (!$row) return false;

        $requester_id = (int)$row['requester_id'];
        $title = $row['title'];

        $this->executeNonQuery(
            "UPDATE edit_requests SET status = ? WHERE request_id = ?", 
            [$response, $request_id]
        );

        $msg = ($response === 'accepted')
            ? "Your edit request for \"{$title}\" was accepted."
            : "Your edit request for \"{$title}\" was rejected.";

        $this->notificationObj->sendNotification($requester_id, $msg);

        return true;
    }

    // Get all pending requests for this author
    public function getRequestsForAuthor(int $author_id): array {
        $sql = "SELECT r.request_id, r.article_id, r.requester_id, r.status, r.message, r.created_at,
                       u.username AS requester_name, a.title
                FROM edit_requests r
                JOIN school_publication_users u ON r.requester_id = u.user_id
                JOIN articles a ON r.article_id = a.article_id
                WHERE r.author_id = ? AND r.status = 'pending'
                ORDER BY r.created_at DESC";
        return $this->executeQuery($sql, [$author_id]);
    }

    // Check if user has accepted permission
    public function hasAcceptedEdit(int $article_id, int $user_id): bool {
        $row = $this->executeQuerySingle(
            "SELECT request_id FROM edit_requests 
             WHERE article_id = ? AND requester_id = ? AND status = 'accepted'",
            [$article_id, $user_id]
        );
        return (bool)$row;
    }

    // helper to get username
    private function getUsername(int $user_id): string {
        $row = $this->executeQuerySingle(
            "SELECT username FROM school_publication_users WHERE user_id = ?", 
            [$user_id]
        );
        return $row ? $row['username'] : 'Unknown';
    }

    // Get all articles shared with a specific user (accepted edit requests)
    public function getSharedArticlesForUser(int $user_id): array {
        $sql = "SELECT a.article_id, a.title, a.content, a.image_path, a.created_at,
                       a.author_id, u.username AS author_name, r.request_id, r.created_at AS shared_at
                FROM edit_requests r
                JOIN articles a ON r.article_id = a.article_id
                JOIN school_publication_users u ON a.author_id = u.user_id
                WHERE r.requester_id = ? AND r.status = 'accepted'
                ORDER BY r.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }
}
?>