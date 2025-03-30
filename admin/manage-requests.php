<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Client Requests - Cebu Best Value Trading</title>
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
        <div class="manage-requests-container">
            <div class="manage-requests">
                <div class="manage-requests-title">
                    <h1>Manage Client Requests</h1>
                </div>
                <div class="manage-requests-interface">
                <?php 
                    include "../includes/dbh.inc.php";
                
                    $stmt = mysqli_stmt_init($connection);
                    $sql = "SELECT * from client_requests";
                
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }
                
                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }
                
                    $results = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($results)) {
                        $authorId = $row["author_id"];
                        $requestTitle = $row["request_title"];
                        $requestDescription = $row["request_description"];
                        $requestLocation = $row["request_location"];
                        $requestStatus = $row["request_status"];
                        $dateCreated = $row["date_created"];

                        $sql = "SELECT * FROM users WHERE user_id=? AND application_status='approved'";
                        $stmt = mysqli_stmt_init($connection);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "i", $authorId);
                    
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }
                        

                        $authorResults = mysqli_stmt_get_result($stmt);
                        $authorRow = mysqli_fetch_assoc($authorResults);

                        if ($authorRow) {
                            $firstName = $authorRow["first_name"];
                            $lastName = $authorRow["last_name"];
                            $email = $authorRow["email"];
                            $phoneNumber = $authorRow["phone_number"];
                            $profilePicture = $authorRow["profile_picture"];
                        } else {
                            echo "NOPE";
                            echo $authorId;
                            echo $sql;
                        }
                        
                        if (empty($profilePicture)) {
                            $profilePicture = "../assets/default_pfp.png";
                        }

                        ?>

                        <div class="request">
                            <div class="request-profile-picture">
                                <img src="<?php echo $profilePicture ?>" alt="">
                            </div>
                            <div class="request-info">
                                <h1><?php echo $requestTitle?></h1>
                                <p><?php echo $requestDescription ?></p>
                                <p>Location: <?php echo $requestLocation ?></p>
                                <p>Date Created: <?php echo $dateCreated ?></p>
                                <p>Status: <?php echo $requestStatus ?></p>
                                <p>Full Name: <?php echo $lastName.", ".$firstName ?></p>
                                <p>Email: <?php echo $email ?> </p>
                                <p>Phone Number: <?php echo $phoneNumber ?> </p>
                            </div>
                            <div class="request-status-select">
                                <form action="../includes/change-request-status.inc.php" method="post">
                                    <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"] ?>">
                                    <input type="hidden" name="request-id" value="<?php echo $row["request_id"] ?>">
                                    <select name="request-status">
                                        <option value="in progress" <?php if ($requestStatus === "in progress") { echo "selected"; } ?>>In Progress</option>
                                        <option value="finished" <?php if ($requestStatus === "finished") { echo "selected"; } ?>>Finished</option>
                                        <option value="cancelled" <?php if ($requestStatus === "cancelled") { echo "selected"; } ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="edit-request-status">Update Progress</button>
                                </form>
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