<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - Cebu Best Value Trading</title>
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

    <div class="manage-jobs-container">
        <div class="manage-jobs">
            <div class="manage-jobs-title">
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true") {
                    ?>
                    <h1>Edit Job</h1>
                    <?php
                } else {
                    ?>
                    <h1>Create Job</h1>
                    <?php
                }
                ?>
            </div>
            <div class="manage-jobs-form">
                <?php
                if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["job-id"])) {
                    ?>
                    <form action="../includes/edit-job.inc.php" method="post">
                    <?php
                } else {
                    ?>
                    <form action="../includes/create-job.inc.php" method="post">
                    <?php
                }
                ?>
                    <input type="number" value="<?php echo $_SESSION["user_id"] ?>" name="author-id" style="display: none;">
                    <?php
                    if (isset( $_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["job-id"])) {
                        include "../includes/dbh.inc.php";
                        $jobId = $_GET["job-id"];
                        $sql = "SELECT * FROM jobs WHERE job_id=?";
                        $stmt = mysqli_stmt_init($connection);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "i", $jobId);
                    }
                    ?>
                    <input type="text" name="job-title" placeholder="Job Title" required>
                    <textarea name="job-description" placeholder="Job Description" required></textarea>
                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
        </div>
</body>
</html>