<?php

$authorId = $_POST["author-id"];
$requestTitle = $_POST["request-title"];
$requestLocation = $_POST["request-location"];
$requestStatus = $_POST["request-status"];
$requestDescription = $_POST["request-description"];

if (!isset($_POST["edit-request"])) {
    header("Location: ../client/manage-requests.php?error=nosubmit");
    exit();
}

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='client'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../client/manage-requests.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../client/manage-requests.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../client/manage-requests.php?error=accountnotfound");
    exit();
}

$sql = "INSERT INTO client_requests (author_id, request_title, request_description, request_location, request_status, date_created) VALUES (?, ?, ?, ?, ?, NOW())";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../client/manage-requests.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "issss", $authorId, $requestTitle, $requestDescription, $requestLocation, $requestStatus);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../client/manage-requests.php?error=sqlexecute");
    exit();
}

header("Location: ../client/manage-requests.php?creation=success");
exit();