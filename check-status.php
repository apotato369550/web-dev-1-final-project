<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Check Application Status - Cebu Best Value Trading</title>
</head>
<body>
    <div class="check-status-image-container">
        <div class="check-status-container">
            <div class="check-status-title-container">
                <h1>Cebu Best Value Trading!</h1>
            </div>
            <div class="check-status-form-container">
                <form action="includes/check-status.inc.php" method="post">
                    <input type="text" name="username" placeholder="Username">
                    <button type="submit" name="check-status">Check Status!</button>
                </form>
                <?php
                if (!empty($_GET["status"])) {
                    $status = $_GET["status"];
                    if ($status === "approved") {
                        echo "<p class='check-status-approved'>Your application has been approved!</p>";
                    }
                    if ($status === "pending") {
                        echo "<p class='check-status-pending'>Your application is still pending</p>";
                    }
                    if ($status === "rejected") {
                        echo "<p class='check-status-rejected'>Your application has been rejected!</p>";
                    }
                }
                ?>
                <form action="index.php">
                    <button type="submit">Back to main page</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>