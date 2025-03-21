<?php

$authorId = $_POST["author-id"];
$requestId = $_POST["request-id"];
$requestStatus = $_POST["request-status"];

if (!isset($_POST["edit-request-status"])) {
    header("Location: ../admin/manage-requests.php?error=nosubmit");
    exit();
}

include "dbh.inc.php";

$sql = "SELECT * FROM users where user_id=? AND application_status='approved'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-requests.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-requests.php?error=sqlerror");
    exit();
}

$authorResults = mysqli_stmt_get_result($stmt);
$authorRow = mysqli_fetch_assoc($authorResults);

if (!$authorRow) {
    header("Location: ../admin/manage-requests.php?error=invalidauthor");
    exit();
}

$sql = "UPDATE client_requests SET request_status=? WHERE request_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-requests.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "si", $requestStatus, $requestId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-requests.php?error=sqlerror");
    exit();
}

header("Location: ../admin/manage-requests.php?success=changerequeststatus");
