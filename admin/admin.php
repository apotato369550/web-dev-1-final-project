<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin - Cebu Best Value Trading</title>
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
        <div class="admin-content-container">
            <div class="admin-content">
                <div class="admin-content-title">
                    <h1>Welcome Admin!</h1>
                </div>
                <div class="admin-content-announcement">
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
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>