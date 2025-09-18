<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../../index.php");
}

if (isset($_POST['insertArticleBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $author_id = $_SESSION['user_id'];
    $imagePath = null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['article_image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            // Centralized uploads directory (root/uploads)
            $uploadDir = realpath(__DIR__ . "/../../uploads/"); 

            // If the folder doesn’t exist, create it
            if ($uploadDir === false) {
                $uploadDir = __DIR__ . "/../../uploads/";
                mkdir($uploadDir, 0777, true);
            }

            // Make sure the path ends with /
            $uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $fileName = time() . '_' . basename($_FILES['article_image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['article_image']['tmp_name'], $targetFile)) {
                // Store relative path for DB (frontend can use this)
                $imagePath = "uploads/" . $fileName;
            }


        }
    }

    if ($articleObj->createArticle($title, $description, $author_id, $category_id, $imagePath)) {
        header("Location: ../index.php");
        exit;
    }
}


if (isset($_POST['editArticleBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $article_id = $_POST['article_id'];
    $category_id = $_POST['category_id'];

    $imagePath = null; // default: no update to image

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['article_image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            // Centralized uploads directory (root/uploads)
            $uploadDir = realpath(__DIR__ . "/../../uploads/");
            if ($uploadDir === false) {
                $uploadDir = __DIR__ . "/../../uploads/";
                mkdir($uploadDir, 0777, true);
            }
            $uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $fileName = time() . '_' . basename($_FILES['article_image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['article_image']['tmp_name'], $targetFile)) {
                $imagePath = "uploads/" . $fileName;
            }
        }
    }

    if ($articleObj->updateArticle($article_id, $title, $description, $category_id, $imagePath)) {
        header("Location: ../articles_submitted.php");
        exit;
    }
}



if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}

if (isset($_POST['deleteArticleBtn'])) {
    $article_id = $_POST['article_id'];

    // 1. Get article info (author + title)
    $sql = "SELECT author_id, title FROM articles WHERE article_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if ($article) {
        $author_id = $article['author_id'];
        $title = $article['title'];

        // 2. Delete article
        $deleteSql = "DELETE FROM articles WHERE article_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute([$article_id]);

        // 3. Notify the writer
        $message = "Your article titled '{$title}' was deleted by an admin.";
        $notificationObj->sendNotification($author_id, $message);
    }

    // 4. Redirect back to admin dashboard
    header("Location: ../index.php?deleted=1");
    exit;
}


if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id,$status);
}

// Accept request
if (isset($_POST['acceptRequestBtn'])) {
    $request_id = (int)$_POST['request_id'];
    $author_id = $_SESSION['user_id'];

    if ($articleObj->respondToRequest($request_id, $author_id, 'accepted')) {
        $_SESSION['flash'] = "Request accepted successfully!";
    } else {
        $_SESSION['flash'] = "Failed to accept request.";
    }

    header("Location: ../edit_request.php");
    exit();
}

// Reject request
if (isset($_POST['rejectRequestBtn'])) {
    $request_id = (int)$_POST['request_id'];
    $author_id = $_SESSION['user_id'];

    if ($articleObj->respondToRequest($request_id, $author_id, 'rejected')) {
        $_SESSION['flash'] = "Request rejected.";
    } else {
        $_SESSION['flash'] = "Failed to reject request.";
    }

    header("Location: ../edit_requests.php");
    exit();
}


if (isset($_POST['requestEditBtn'])) {
    $article_id = (int)$_POST['article_id'];
    $requester_id = $_SESSION['user_id'];

    if ($articleObj->requestEdit($article_id, $requester_id)) {
        $_SESSION['flash'] = "Edit request sent to the author!";
    } else {
        $_SESSION['flash'] = "Could not send request (maybe already pending or your own article).";
    }

    header("Location: ../index.php"); 
    exit();
}

if (isset($_POST['acceptEditBtn']) || isset($_POST['rejectEditBtn'])) {
    $request_id = (int)$_POST['request_id'];
    $author_id = $_SESSION['user_id'];
    $response = isset($_POST['acceptEditBtn']) ? 'accepted' : 'rejected';

    if ($editRequestObj->respondToRequest($request_id, $author_id, $response)) {
        $_SESSION['message'] = "Request {$response}.";
    } else {
        $_SESSION['message'] = "Action failed.";
    }
    header("Location: ../author_requests.php");
    exit;
}
