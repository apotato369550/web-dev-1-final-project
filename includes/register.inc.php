<?php
$username = $_POST["username"];
$password = $_POST["password"];
$repeatPassword = $_POST["repeat-password"];

$firstName = $_POST["first-name"];
$lastName = $_POST["last-name"];
$email = $_POST["email"];
$phoneNumber = $_POST["phone-number"];

$accountType = $_POST["account-type"];
$applicationStatus = "pending";


if (!isset($_POST["register"])) {
    header("Location: ../register.php?error=nosubmit");
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../register.php?error=specialcharacters");
    exit();
}

if ($password !== $repeatPassword) {
    header("Location: ../register.php?error=passwordsdonotmatch");
    exit();
}

include "dbh.inc.php";

$stmt = mysqli_stmt_init($connection);
$sql = "SELECT username from users WHERE username=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../register.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $username);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../register.php?error=sqlexecute");
    exit();
}

mysqli_stmt_store_result($stmt);
$results = mysqli_stmt_num_rows($stmt);

if($results > 0){
    header("Location: ../register.php?error=usernameinuse");	
    exit();
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, user_type, first_name, last_name, email, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../register.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "sssssss", $username, $passwordHash, $accountType, $firstName, $lastName, $email, $phoneNumber);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../register.php?error=sqlexecute");
    exit();
}

header("Location: ../index.php?registration=successful");
exit();