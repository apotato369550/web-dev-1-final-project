<?php

if (!isset($_POST['create-quotation'])) {
    header("Location: ../admin/manage-quotation.php?error=nosubmit");
    exit();
}

$authorId = $_POST['author-id'];

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='admin'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare1");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-quotation.php?error=unauthorized");
    exit();
}

$requestId = $_POST['request-id'];
$clientId = $_POST['client-id'];
$quotationTitle = $_POST['quotation-name'];
$quotationLocation = $_POST['quotation-location'];
$quotationDescription = $_POST['quotation-description'];

$sql = "INSERT INTO quotations (quotation_title, quotation_description, quotation_location, client_id, request_id) VALUES (?, ?, ?, ?, ?)";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare2");
    exit();
}

mysqli_stmt_bind_param($stmt, "iisss", $clientId, $requestId, $quotationTitle, $quotationDescription, $quotationLocation);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-quotation.php?creation=success");
exit();