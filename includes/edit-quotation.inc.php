<?php

if (!isset($_POST['edit-quotation'])) {
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

$quotationId = $_POST['quotation-id'];

$sql = "SELECT * FROM quotations WHERE quotation_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare2");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $quotationId);   

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

$quotationTitle = $_POST['quotation-name'];
$quotationLocation = $_POST['quotation-location'];
$quotationDescription = $_POST['quotation-description'];

$sql = "UPDATE quotations SET quotation_name=?, quotation_location=?, quotation_description=?, last_updated=NOW() WHERE quotation_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-quotation.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "sssi", $quotationTitle, $quotationLocation, $quotationDescription, $quotationId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-quotation.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-quotation.php?quotation-id=".$quotationId."&edit=true&update=success");
exit();