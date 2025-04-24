<?php

if (!isset($_POST["edit-announcement"])) {
    header("Location: ../admin/manage-announcements.php?error=nosubmit");
    exit();
}

$authorId = $_POST["author-id"];
$announcementId = $_POST["announcement-id"];
$announcementTitle = $_POST["announcement-title"];
$announcementDescription = $_POST["announcement-description"];

if (empty($announcementTitle) || empty($announcementDescription)) {
    header("Location: ../admin/manage-announcements.php?error=emptyfields");
    exit();
}

include "dbh.inc.php";

// check if author exists

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='admin'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-announcements.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-announcements.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-announcements.php?error=unauthorized");
    exit();
}

// check if announcement exists



$sql = "SELECT * FROM announcements WHERE announcement_id=?";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-announcements.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $announcementId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-announcements.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-announcements.php?error=unauthorized");
    exit();
}

$sql = "UPDATE announcements SET title=?, description=? WHERE announcement_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-announcements.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ssi", $announcementTitle, $announcementDescription, $announcementId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-announcements.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-announcements.php?update=success");
exit();