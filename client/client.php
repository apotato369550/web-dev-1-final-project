<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "client") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }
    ?>
    <h1>Welcome Client!</h1>
    <a href="../includes/logout.inc.php">Logout</a>
</body>
</html>