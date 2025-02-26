<?php

$username = $_POST["username"];
$password = $_POST["password"];
$approvedStatus = "approved";

if (!isset($_POST["login"])) {
    header("Location: ../index.php?error=nosubmit");
    exit();
}

include "dbh.inc.php";

$stmt = mysqli_stmt_init($connection);
$sql = "SELECT * from users WHERE username=? AND application_status=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Error when preparing SQL statement.";
    header("Location: ../index.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $username, $approvedStatus);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error while executing SQL statement";
    header("Location: ../index.php?error=login");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if(!$row){
    echo "account not found";
    header("Location: ../index.php?error=login");
    exit();	
}

if (!password_verify($password, $row["password"])) {
    echo "invalid password";
    header("Location: ../index.php?error=login");
    exit();	
}


if ($row["user_type"] === "admin") {
    session_start();
    $_SESSION["user_type"] = $row["user_type"];
    $_SESSION["username"] = $username;
    header("Location: ../admin/admin.php");
} 

if ($row["user_type"] === "worker") {
    session_start();
    $_SESSION["user_type"] = $row["user_type"];
    $_SESSION["username"] = $username;
    header("Location: ../worker/worker.php");

} 

if ($row["user_type"] === "client") {
    session_start();
    $_SESSION["user_type"] = $row["user_type"];
    $_SESSION["username"] = $username;
    header("Location: ../cashier/client.php");
} 