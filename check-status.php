<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Check Application Status - Cebu Best Value Trading</title>
</head>
<body>
    <div class="check-status-container">
        <h1>Cebu Best Value Trading!</h1>
        <form action="includes/check-status.inc.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username">
            <button type="submit" name="check-status">Check Status!</button>
        </form>
        <?php
        if (!empty($_POST["status"])) {
            $status = $_POST["status"];
            if ($status === "approved") {
                echo "<p class='application-status-approved'>Your application has been approved!</p>";
            }
            if ($status === "pending") {
                echo "<p class='application-status-pending'>Your application is still pending</p>";
            }
            if ($status === "rejected") {
                echo "<p class='application-status-rejected'>Your application has been rejected!</p>";
            }
        }
        ?>
    </div>
</body>
</html>