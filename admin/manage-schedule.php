<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Schedule - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "admin-navbar.php";
    ?>
    <div class="content">
        <?php
        include "admin-sidebar.php";
        ?>
        <div class="manage-schedule-container">
            <div class="manage-schedule">
                <div class="manage-schedule-title">
                    <h1>Manage Quotations</h1>
                </div>
            </div>
        </div>
    </div>
</body>
</html>