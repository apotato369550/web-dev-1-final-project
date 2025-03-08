<?php

if (!isset($_POST["delete-announcement"])) {
    header("Location: ../admin/manage-announcements.php?error=nosubmit");
    exit();
}

$authorId = $_POST["author-id"];
$announcementId = $_POST["announcement-id"];

include "dbh.inc.php";

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
    header("Location: ../admin/manage-announcements.php?error=noauthor");
    exit();
}

$sql = "SELECT * FROM announcements WHERE announcement_id=?";

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
    header("Location: ../admin/manage-announcements.php?error=doesnotexist");
    exit();
}

$sql = "DELETE FROM announcements WHERE announcement_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-announcements.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $announcementId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-announcements.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-announcements.php?delete=success");
exit();