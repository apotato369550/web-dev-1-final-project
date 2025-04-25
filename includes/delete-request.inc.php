<?php

if(!isset($_POST["delete-request"])){
    header("Location: ../client/manage-requests.php?error=nosubmit");
    exit();
}

$requestId = $_POST["request-id"];
$authorId = $_POST["author-id"];

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
    header("Location: ../client/manage-requests.php?error=unauthorized");
    exit();
}

$sql = "DELETE FROM client_requests WHERE request_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../client/manage-requests.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $requestId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../client/manage-requests.php?error=sqlexecute");
    exit();
}

header("Location: ../client/manage-requests.php?delete=success");
exit();
