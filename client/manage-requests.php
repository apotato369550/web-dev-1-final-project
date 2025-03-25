<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="client.css">
    <title>Manage Requests - Cebu Best Value Trading</title>
</head>
<body>
    
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "client") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "client-navbar.php";
    ?>

    <div class="manage-requests-container">
        <div class="manage-requests">
            <div class="manage-requests-title">
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true") {
                    ?>
                    <h1>Edit Request</h1>
                    <?php
                } else {
                    ?>
                    <h1>Create Request</h1>
                    <?php
                }
                ?>
            </div>
            <div class="manage-requests-form">
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["request-id"])) {
                    ?>
                    <form action="../includes/edit-request.inc.php" method="post">
                    <?php
                } else {
                    ?>
                    <form action="../includes/create-request.inc.php" method="post">
                    <?php
                }
                ?>
                <input type="hidden" value="<?php echo $_SESSION["user_id"] ?>" name="author-id">
                <?php 
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["request-id"])) {
                    include "../includes/dbh.inc.php";
                    $requestId = $_GET["request-id"];
                    $sql = "SELECT * FROM client_requests WHERE request_id=?";
                    $stmt = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $requestId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($results);

                    if(!$row){
                        echo "request not found";
                    }

                    $requestTitle = $row["request_title"];
                    $requestDescription = $row["request_description"];
                    $requestLocation = $row["request_location"];
                    $requestStatus = $row["request_status"];
                    ?>
                    <input type="hidden" name="request-id" value="<?php echo $requestId ?>">
                    <input type="text" name="request-title" value="<?php echo $requestTitle ?>" placeholder="Title">
                    <input type="text" name="request-location" value="<?php echo $requestLocation ?>" placeholder="Location">
                    <select name="request-status">
                            <option value="in progress" <?php if ($requestStatus === "in progress") { echo "selected"; } ?>>In Progress</option>
                            <option value="finished" <?php if ($requestStatus === "finished") { echo "selected"; } ?>>Finished</option>
                            <option value="cancelled" <?php if ($requestStatus === "cancelled") { echo "selected"; } ?>>Cancelled</option>
                    </select>
                    <textarea name="request-description"><?php echo $requestDescription ?></textarea>
                    <button type="submit" name="edit-request">Edit</button>
                    <?php
                } else {
                    // replace below with empty fields
                    $requestTitle = "";
                    $requestDescription = "";
                    $requestLocation = "";
                    $requestStatus = "";
                    $dateCreated = "";
                    ?>
                    <input type="text" name="request-title"  placeholder="Title">
                    <input type="text" name="request-location"  placeholder="Location">
                    <select name="request-status">
                            <option value="in progress">In Progress</option>
                            <option value="finished">Finished</option>
                            <option value="cancelled">Cancelled</option>
                    </select>
                    <textarea name="request-description" id=""></textarea>
                    <button type="submit" name="create-request">Create</button>
                    <?php
                }
                ?>
                </form>
            </div>
            <div class="manage-requests-title">
                <h1>Your Requests</h1>
            </div>
            <div class="manage-requests-interface">
            <?php 
                include "../includes/dbh.inc.php";
            
                $stmt = mysqli_stmt_init($connection);
                $sql = "SELECT * from client_requests WHERE author_id=? ORDER BY date_created DESC";
            
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error when preparing SQL statement.";
                }
                
                mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);

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
                            <form action="manage-requests.php" method="get">
                                <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"] ?>">
                                <input type="hidden" name="request-id" value="<?php echo $row["request_id"] ?>">
                                <input type="text" value="true" name="edit" style="display: none;">
                                <button type="submit" name="edit-request-status">Edit</button>
                            </form>
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