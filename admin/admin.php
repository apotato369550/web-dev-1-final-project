<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
        // header("Location: ../index.php?error=unauthorized");
        exit();
    }
    ?>
    <h1>Welcome Admin!</h1>
    <a href="../includes/logout.inc.php">Logout</a>
</body>
</html>