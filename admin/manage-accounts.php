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
    ?>
    <div class="manage-accounts-container">
        <div class="manage-accounts">
            <div class="manage-accounts-title">
                <h1>Manage Accounts</h1>
            </div>
            <div class="manage-accounts-interface">
                <?php 
                include "../includes/dbh.inc.php"

                $sessionUserId = $_SESSION["user_id"];
                $sessionUsername = $_SESSION["username"];
            
                $stmt = mysqli_stmt_init($connection);
                $sql = "SELECT * from users";
            
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error when preparing SQL statement.";
                }
            
                mysqli_stmt_bind_param($stmt);
            
                if (!mysqli_stmt_execute($stmt)) {
                    echo "Error while executing SQL statement";
                }
            
                $results = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row["user_id"];
                    $username = $row["username"];
                    if ($sessionUserId === $userId && $sessionUsername === $username) {
                        continue;
                    }
                    $userType = $row["user_type"];
                    $firstName = $row["first_name"];
                    $lastName = $row["last_name"];
                    $email = $row["email"];
                    $phoneNumber = $row["phone_number"];
                    $profilePicture = $row["profile_picture"];
                    $applicationStatus = $row["application_status"];

                    ?>
                    <div>Username: <?php echo $username ?></div>
                    <?php
                }

                ?>
            </div>
        </div>
    </div>
</body>
</html>