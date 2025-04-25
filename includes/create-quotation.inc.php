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
    header("Location: ../admin/manage-quotation.php?error=sqlprepare");
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

$sql = "INSERT INTO quotations (request_id, client_id, quotation_name, quotation_location, quotation_description) VALUES (?, ?, ?, ?, ?)";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "iisss", $requestId, $clientId, $quotationTitle, $quotationLocation, $quotationDescription);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

$sql = "SELECT * FROM quotations WHERE request_id=? AND client_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $requestId, $clientId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-quotation.php?error=quotationnotfound");
    exit();
}

$quotationId = $row['quotation_id'];

$sql = "UPDATE quotation_items SET quotation_id=? WHERE request_id=? AND client_id=? AND quotation_id IS NULL";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "iii", $quotationId, $requestId, $clientId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-quotation.php?create=success&edit=true&quotation-id=$quotationId");
exit();