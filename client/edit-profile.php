<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="client.css">
    <title>Edit Profile - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "client") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "client-navbar.php";

    include "../includes/dbh.inc.php";

    $userId = $_SESSION["user_id"];
    $username = $_SESSION["username"];

    $stmt = mysqli_stmt_init($connection);
    $sql = "SELECT * from users WHERE user_id=? AND username=?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Error when preparing SQL statement.";
    }

    mysqli_stmt_bind_param($stmt, "ss", $userId, $username);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Error while executing SQL statement";
    }

    $results = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($results);

    if(!$row){
        echo "account not found";
    }

    $firstName = $row["first_name"];
    $lastName = $row["last_name"];
    $email = $row["email"];
    $phoneNumber = $row["phone_number"];
    ?>
    <div class="edit-profile-container">
        <div class="edit-profile-content-container">
            <div class="edit-profile-title">
                <h1>Edit Profile</h1>
            </div>
            <div class="edit-profile-form-container">
                <form action="../includes/edit-profile.inc.php" method="post" enctype="multipart/form-data">
                    <input type="number" style="display: none;" value="<?php echo $_SESSION["user_id"] ?>" name="user-id">
                    <div class="edit-profile-info">
                        <div class="edit-profile-main-info">
                            <h3>Name</h3>
                            <div class="edit-profile-form-duo">
                                <input type="text" name="first-name" placeholder="Edit First Name" value="<?php echo $firstName ?>" required>
                                <input type="text" name="last-name" placeholder="Edit Last Name" value="<?php echo $lastName ?>" required>
                            </div>
                            <h3>Contact Info</h3>
                            <div class="edit-profile-form-duo">
                                <input type="email" name="email" placeholder="New Email" value="<?php echo $email ?>" required>
                                <input type="text" name="phone-number" placeholder="New Phone Number" value="<?php echo $phoneNumber ?>" required>
                            </div>
                            <h3>Username and Password</h3>                    
                            <input type="text" name="new-username" placeholder="New Username" value="<?php echo $username ?>" required>
                            <div class="edit-profile-form-duo">
                                <input type="password" name="new-password" placeholder="New Password">
                            </div>
                        </div>
                        <div class="edit-profile-pfp">
                            <img src="<?php echo $profilePicPath ?>" alt="Profile Pic">
                            <br>
                            <h3>Upload Profile Pic</h3>                    
                            <input type="file" name="profile-image">
                        </div>
                    </div>
                    <div class="edit-profile-confirm-submit">
                        <label for="confirm-password">Enter Password to Save Changes</label>
                        <input type="password" name="confirm-password" placeholder="Password">
                        <button type="submit" name="edit-profile">Save Changes</button>
                    </div>
                </form>
                <?php 
                if (!empty($_GET["error"])) {
                    $errorMessage = $_GET["error"];
                    if ($errorMessage === "sqlprepare" || $errorMessage === "sqlexecute") {
                        echo "<p class='error-message'>Error occured executing SQL query</p>";
                    }
                    if ($errorMessage === "accountnotfound") {
                        echo "<p class='error-message'>Could not find your account.</p>";
                    }
                    if ($errorMessage === "incorrectpassword") {
                        echo "<p class='error-message'>Password incorrect.</p>";
                    }
                    if ($errorMessage === "uploaderror" || $errorMessage === "movefailed") {
                        echo "<p class='error-message'>There was an error uploading your image.</p>";
                    }
                    if ($errorMessage === "imagetoobig") {
                        echo "<p class='error-message'>File size too large. Please select a smaller image</p>";
                    }
                }

                if (!empty($_GET["edit"]) && $_GET["edit"] === "success") {
                    echo "<p class='success-message'>Profile edited successfully!</p>";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>