<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
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
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true") {
                    ?>
                    <h1>Edit Announcement</h1>
                    <?php
                } else {
                    ?>
                    <h1>Create Announcement</h1>
                    <?php
                }
                ?>
            </div>
            <div class="manage-announcements-form">
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["announcement-id"])) {
                    ?>
                    <form action="../includes/edit-announcement.inc.php" method="post">
                    <?php
                } else {
                    ?>
                    <form action="../includes/create-announcement.inc.php" method="post">
                    <?php
                }
                ?>
                    <input type="number" value="<?php echo $_SESSION["user_id"] ?>" name="author-id" style="display: none;">
                    <?php
                    if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["announcement-id"])) {
                        include "../includes/dbh.inc.php";
                        $announcementId = $_GET["announcement-id"];
                        $sql = "SELECT * FROM announcements WHERE announcement_id=?";
                        $stmt = mysqli_stmt_init($connection);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "i", $announcementId);

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }

                        $results = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($results);

                        $currentTitle = $row["title"];
                        $currentDescription = $row["description"];
                        ?>
                        <input type="number" value="<?php echo $_GET["announcement-id"]; ?>" name="announcement-id" style="display: none;">
                        <div class="announcement-title-input">
                            <input type="text" placeholder="Title" name="announcement-title" value="<?php echo $currentTitle ?>">
                        </div>
                        <div class="announcement-description-input">
                            <textarea name="announcement-description"><?php echo $currentDescription ?></textarea>
                        </div>
                        <div class="announcement-submit-button">
                            <button type="submit" name="edit-announcement">Edit Announcement</button>
                        </div>
                        <?php
                    } else {
                        ?>   
                        <div class="announcement-title-input">
                            <input type="text" placeholder="Title" name="announcement-title">
                        </div>
                        <div class="announcement-description-input">
                            <textarea name="announcement-description">Enter announcement here...</textarea>
                        </div>
                        <div class="announcement-submit-button">
                            <button type="submit" name="edit-announcement">Create Announcement</button>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
            <div class="manage-announcements-title">
                <h1>Manage Announcements</h1>
            </div>
            <div class="manage-announcements-list">
                <?php
                include "../includes/dbh.inc.php";

                $stmt = mysqli_stmt_init($connection);
                $sql = "SELECT * FROM announcements ORDER BY date_created DESC";

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error when preparing SQL statement.";
                }

                if (!mysqli_stmt_execute($stmt)) {
                    echo "Error while executing SQL statement";
                }

                $results = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($results)) {
                    $announcementId = $row["announcement_id"];
                    $authorId = $row["author_id"];
                    $title = $row["title"];
                    $description = $row["description"];
                    $date = $row["date_created"];

                    $sql = "SELECT * FROM users WHERE user_id=?";

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $authorId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $authorResults = mysqli_stmt_get_result($stmt);
                    $authorRow = mysqli_fetch_assoc($authorResults);
                    $authorProfilePicture = $authorRow["profile_picture"];
                    $authorUsername = $authorRow["username"];
                    $authorFirstName = $authorRow["first_name"];
                    $authorLastName = $authorRow["last_name"];

                    if (empty($authorProfilePicture)) {
                        $authorProfilePicture = "../assets/default_pfp.png";
                    }
                    
                    ?> 
                    <div class="announcement">
                        <div class="announcement-profile-picture">
                            <img src="<?php echo $authorProfilePicture ?>" alt="">
                        </div>
                        <div class="announcement-details">
                            <h1><?php echo $title ?></h1>
                            <p>Posted by: <?php echo $authorFirstName." ".$authorLastName." (".$authorUsername.")" ?></p>
                            <p>Date Posted: <?php echo $date ?></p>
                            <p><?php echo $description ?></p>
                        </div>
                        <div class="announcement-single-interface">
                            <div class="announcement-interface-title">
                                <h3>Manage Announcement</h3>
                            </div>
                            <div class="announcement-interface-buttons">
                                <form action="manage-announcements.php" method="get"> 
                                    <input type="number" value="<?php echo $announcementId ?>" name="announcement-id" style="display: none;">
                                    <input type="text" value="true" name="edit" style="display: none;">
                                    <button type="submit">Edit</button>
                                </form>
                                <form action="../includes/delete-announcement.inc.php" method="post">
                                    <input type="number" value="<?php echo $_SESSION["user_id"] ?>" name="author-id" style="display: none;">
                                    <input type="number" value="<?php echo $announcementId ?>" name="announcement-id" style="display: none;">
                                    <button type="submit" name="delete-announcement">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>