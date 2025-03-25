<?php 

$authorId = $row["author_id"];
$requestTitle = $_POST["request-title"];
$requestLocation = $_POST["request-location"];
$requestStatus = $_POST["request-status"];
$requestDescription = $_POST["request-description"];
$requestId = $_POST["request-id"];


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
    header("Location: ../client/manage-requests.php?error=usernotfound");
    exit();
}


$sql = "SELECT * FROM client_requests WHERE request_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../client/manage-requests.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $requestId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../client/manage-requests.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../client/manage-requests.php?error=requestnotfound");
    exit();
}

$sql = "UPDATE client_requests SET request_title=?, request_description=?, request_location=?, request_status=? WHERE request_id=?";


if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../client/manage-requests.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ssssi", $requestTitle, $requestDescription, $requestLocation, $requestStatus, $requestId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../client/manage-requests.php?error=sqlexecute");
    exit();
}


header("Location: ../client/manage-requests.php?edit=success");
exit();
