<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewAdminBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerAdmin($username, $email, $password, $contact_number)) {
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

if (isset($_POST['loginAdminBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginAdmin($email, $password)) {
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

if (isset($_GET['logoutAdminBtn'])) {
	$userObj->logout();
	header("Location: ../../index.php");
}

if (isset($_POST['updateAdminBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		header("Location: ../profile.php");
	}
}

// if (isset($_POST['insertOfferBtn'])) {
//     $user_id = $_SESSION['user_id'];
//     $proposal_id = $_POST['proposal_id'];
//     $description = htmlspecialchars($_POST['description']);

//     if ($offerObj->hasSubmittedOffer($user_id, $proposal_id)) {
//         echo "<script>
//             alert('You have already submitted an offer for this proposal.');
//             window.location.href = '../index.php';
//         </script>";
//         exit;
//     }

//     if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
//         header("Location: ../index.php?success=offer_submitted");
//         exit;
//     }
// }


// if (isset($_POST['updateOfferBtn'])) {
// 	$description = htmlspecialchars($_POST['description']);
// 	$offer_id = $_POST['offer_id'];
// 	if ($offerObj->updateOffer($description, $offer_id)) {
// 		$_SESSION['message'] = "Offer updated successfully!";
// 		$_SESSION['status'] = '200';
// 		header("Location: ../index.php");
// 	}
// }

// if (isset($_POST['deleteOfferBtn'])) {
// 	$offer_id = $_POST['offer_id'];
// 	if ($offerObj->deleteOffer($offer_id)) {
// 		$_SESSION['message'] = "Offer deleted successfully!";
// 		$_SESSION['status'] = '200';
// 		header("Location: ../index.php");
// 	}
// }


// --- ADD CATEGORY ---
if (isset($_POST['add_category'])) {
    $name = trim($_POST['cat_name']);
    $categoryObj->addCategory($name);
    header("Location: ../manage_category.php?success=Category Added");
    exit;
}

// --- ADD SUBCATEGORY ---
if (isset($_POST['add_subcategory'])) {
    $name   = trim($_POST['sub_name']);
    $cat_id = (int)$_POST['category_id'];
    $categoryObj->addSubcategory($cat_id, $name);
    header("Location: ../manage_category.php?success=Subcategory Added");
    exit;
}

// --- DELETE CATEGORY ---
if (isset($_GET['delete_cat'])) {
    $id = (int)$_GET['delete_cat'];
    $categoryObj->deleteCategory($id);
    header("Location: ../manage_category.php?success=Category Deleted");
    exit;
}

// --- DELETE SUBCATEGORY ---
if (isset($_GET['delete_sub'])) {
    $id = (int)$_GET['delete_sub'];
    $categoryObj->deleteSubcategory($id);
    header("Location: ../manage_category.php?success=Subcategory Deleted");
    exit;
}
