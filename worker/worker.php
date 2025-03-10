<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="worker.css">
    <title>Worker - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "worker") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "worker-navbar.php";
    ?>
    <h1>Welcome Worker!</h1>
    <a href="../includes/logout.inc.php">Logout</a>
</body>
</html>