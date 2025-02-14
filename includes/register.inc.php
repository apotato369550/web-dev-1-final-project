<?php


$username = $_POST["username"];
$password = $_POST["password"];
$repeatPassword = $_POST["repeat-password"];
$accountType = $_POST["account-type"];

if (empty($username)) {
    echo "Error: Empty username";
    exit();
}

if (empty($password) || empty($repeatPassword)) {
    echo "Error: Missing password";
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    echo "Error: Username should not contain special characters";
    exit();
}

if ($password !== $repeatPassword) {
    echo "Error: Passwords do not match";
    exit();
}

include "dbh.inc.php";

$stmt = mysqli_stmt_init($connection);
$sql = "SELECT username from users WHERE username=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Error when preparing SQL statement.";
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $username);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error while executing SQL statement";
    exit();
}

mysqli_stmt_store_result($stmt);
$results = mysqli_stmt_num_rows($stmt);

if($results > 0){
    echo "Error: Username already in use";
    exit();	
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Error when preparing SQL statement. 2";
    exit();
}

mysqli_stmt_bind_param($stmt, "sss", $username, $passwordHash, $accountType);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error while executing SQL statement 2";
    exit();
}

echo "Registration Successful!";
?>