<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Cebu Best Value Trading</title>
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

    <div class="manage-announcements-container">
        <div class="manage-announcements">
            <div class="manage-announcements-title">
                <h1>Manage and Edit Announcements</h1>
            </div>
            <div class="manage-announcements-form">
                <form action="../includes/manage-announcement.inc.php" method="post">
                    <?php
                    if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["announcement-id"]) && is_int($_GET["announcement-id"])) {
                        ?>
                        <input type="number" value="<?php echo $_GET["announcement-id"]; ?>" name="announcement-id" style="display: none;">
                        <?php
                    }
                    ?>
                    <input type="number" value="<?php echo $_SESSION["user_id"] ?>" name="author-id">
                    <input type="text" placeholder="Title" name="announcement-title">
                    <textarea name="announcement-description">Add announcement text here...</textarea>
                    <button type="submit" name="manage-announcement">Create/Edit Announcement</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>