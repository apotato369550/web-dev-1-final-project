<?php

if (!isset($_POST["update-account-status"])) {
    header("Location: ../admin/manage-accounts.php?error=nosubmit");
    exit();
}

$adminUser = $_POST["admin-id"];
$userID = $_POST["user-id"];
$accountStatus = $_POST["account-status"];

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=?";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-accounts.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $adminUser);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-accounts.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-accounts.php?error=accountnotfound");
    exit();
}

if ($row["user_type"] !== "admin") {
    header("Location: ../admin/manage-accounts.php?error=unauthorized");
    exit();

}

$sql = "UPDATE users SET application_status=? WHERE user_id=?";

if(!mysqli_stmt_prepare($stmt, $sql)){
    header("Location: ../admin/manage-accounts.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $accountStatus, $userID);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-accounts.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-accounts.php?update=success&user-id=".$userID);
exit();