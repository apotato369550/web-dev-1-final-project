<?php

// up for testing

// if user didn't press button, redirect to original page
if (!isset($_POST["edit-profile"])) {
    header("../admin/edit-profile.php?error=nosubmit");
    exit();
}

// check if confirm password is correct before changing anything
include "dbh.inc.php";

$confirmPassword = $_POST["confirm-password"];
$userId = $_POST["user-id"];

$stmt = mysqli_stmt_init($connection);
$sql = "SELECT * from users WHERE user_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/edit-profile.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/edit-profile.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if(!$row){
    header("Location: ../admin/edit-profile.php?error=accountnotfound");
    exit();	
}

if (!password_verify($confirmPassword, $row["password"])) {
    header("Location: ../admin/edit-profile.php?error=incorrectpassword");
    exit();	
}

// old profile image path
$oldProfilePic = $row["profile_picture"];

// getting other variables
$firstName = $_POST["first-name"];
$lastName = $_POST["last-name"];
$email = $_POST["email"];
$phoneNumber = $_POST["phone-number"];
$newUsername = $_POST["new-username"];
$newPassword = $_POST["new-password"];


// update password (if any)

if (!empty($newPassword)) {
    $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);
    // update the password hash

    $sql = "UPDATE users SET password=? WHERE user_id=?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/edit-profile.php?error=sqlprepare");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "si", $newPasswordHash, $userId);
    
    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/edit-profile.php?error=sqlexecute");
        exit();
    }
}

// update all the other info
$sql = "UPDATE users SET username=?, first_name=?, last_name=?, email=?, phone_number=? WHERE user_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/edit-profile.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "sssssi", $newUsername, $firstName, $lastName, $email, $phoneNumber, $userId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/edit-profile.php?error=sqlexecute");
    exit();
}

// image processing 

if ($_FILES["profile-image"]["error"] === UPLOAD_ERR_NO_FILE) {
    header("Location: ../admin/edit-profile.php?edit=success");
    exit();
}

$allowedExtensions = array('jpg', 'jpeg', 'png', 'pdf');

$imageName = $_FILES["profile-image"]["name"];
$imageTempName = $_FILES["profile-image"]["tmp_name"];
$imageSize = $_FILES["profile-image"]["size"];
$imageError = $_FILES["profile-image"]["error"];

$fileExtension = explode('.', $imageName);
$fileExtension = end($fileExtension);
$fileExtension = strtolower($fileExtension);

if (!in_array($fileExtension, $allowedExtensions)) {
    header("Location: ../admin/edit-profile.php?error=invalidextension&imagename=".$imageName);
    exit();
}

if ($imageError !== 0) {
    header("Location: ../admin/edit-profile.php?error=uploaderror");
    exit();
}

if($imageSize > 500000){
    header("Location: ../admin/edit-profile.php?error=imagetoobig");
	exit();
}

$newFileName = uniqid("", true).".".$fileExtension;
$fileDestination = "../uploads/profile-pictures/".$newFileName;

if (!move_uploaded_file($imageTempName, $fileDestination)) {
    header("Location: ../admin/edit-profile.php?error=movefailed");
    exit();
}

if(!empty($oldProfilePic)) {
    $oldProfilePic = "../uploads/profile-pictures/".$oldProfilePic;
    unlink($oldProfilePic);
}

$sql = "UPDATE users SET profile_picture=? WHERE user_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/edit-profile.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "si", $newFileName, $userId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/edit-profile.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/edit-profile.php?edit=success");
exit();