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
    <div class="content">
        <?php
        include "admin-sidebar.php";
        ?>
        
        <div class="manage-accounts-container">
            <div class="manage-accounts">
                <div class="manage-accounts-title">
                    <h1>Manage Accounts</h1>
                </div>
                <div class="manage-accounts-interface">
                    <?php 
                    include "../includes/dbh.inc.php";

                    $sessionUserId = $_SESSION["user_id"];
                    $sessionUsername = $_SESSION["username"];
                
                    $stmt = mysqli_stmt_init($connection);
                    $sql = "SELECT * from users";
                
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }
                
                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }
                
                    $results = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($results)) {
                        $userId = $row["user_id"];
                        $username = $row["username"];
                        if ($sessionUserId === $userId && $sessionUsername === $username) {
                            continue;
                        }
                        $userId = $row["user_id"];
                        $userType = $row["user_type"];
                        $firstName = $row["first_name"];
                        $lastName = $row["last_name"];
                        $email = $row["email"];
                        $phoneNumber = $row["phone_number"];
                        $profilePicture = $row["profile_picture"];
                        $applicationStatus = $row["application_status"];

                        if (empty($profilePicture)) {
                            $profilePicture = "../assets/default_pfp.png";
                        }

                        ?>
                        <div class="account">
                            <div class="account-profile-picture">
                                <img src="<?php echo $profilePicture ?>" alt="">
                            </div>
                            <div class="account-info">
                                <h1>User #<?php echo $userId ?>: <?php echo $username ?></h1>
                                <h3>Account Type: <?php echo $userType ?> </h3>
                                <p>Full Name: <?php echo $lastName.", ".$firstName ?></p>
                                <p>Email: <?php echo $email ?> </p>
                                <p>Phone Number: <?php echo $phoneNumber ?> </p>
                            </div>
                            <div class="account-options">
                                <form action="../includes/update-account-status.inc.php" method="post">
                                    <input type="number" value="<?php echo $sessionUserId; ?>" style="display: none;" name="admin-id">
                                    <input type="number" value="<?php echo $userId; ?>" style="display: none;" name="user-id">
                                    
                                    <h3>Status: </h3>
                                    <select name="account-status">
                                        <option value="approved" <?php if ($applicationStatus === "accepted") { echo "selected"; } ?>>Active</option>
                                        <option value="rejected" <?php if ($applicationStatus === "rejected") { echo "selected"; } ?>>Disabled</option>
                                        <option value="pending" <?php if ($applicationStatus === "pending") { echo "selected"; } ?>>Inactive</option>
                                    </select>
                                    <button type="submit" name="update-account-status">Update</button>
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