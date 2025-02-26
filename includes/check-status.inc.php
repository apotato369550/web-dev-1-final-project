<?php

if (!isset($_POST["check-status"])) {
    header("Location: ../check-status.php?error=nosubmit");
    exit();
}

$username = $_POST["username"];

include "dbh.inc.php";


$stmt = mysqli_stmt_init($connection);
$sql = "SELECT * from users WHERE username=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Error when preparing SQL statement.";
    header("Location: ../index.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $username);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error while executing SQL statement";
    header("Location: ../index.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if(!$row){
    header("Location: ../check-status.php?error=accountnotfound");
    exit();	
}

$status = $row["application_status"];
header("Location: ../check-status.php?status=".$status);
exit();