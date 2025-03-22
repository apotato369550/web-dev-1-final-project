<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="worker.css">
    <title>View Assigned Jobs - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "worker") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "worker-navbar.php";
    ?>

    <div class="worker-content-container">
        <div class="worker-content">
            <div class="worker-content-title">
                <h1>Assigned Jobs</h1>
            </div>

            <div class="worker-content-jobs">
                <?php
                include "../includes/dbh.inc.php";

                $stmt = mysqli_stmt_init($connection);
                $sql = "SELECT * FROM job_assignments WHERE worker_id=?";

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error when preparing SQL statement.";
                }

                mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);

                if (!mysqli_stmt_execute($stmt)) {
                    echo "Error while executing SQL statement";
                }

                $results = mysqli_stmt_get_result($stmt);
                $listOfJobs = [];

                while ($row = mysqli_fetch_assoc($results)) {
                    $jobId = $row["job_id"];
                    array_push($listOfJobs, $jobId);
                }

                foreach ($listOfJobs as $jobId) {
                    $sql = "SELECT * FROM jobs WHERE job_id=?";

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $jobId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($results);

                    $jobAuthorId = $row["job_author"];
                    $jobTitle = $row["job_title"];
                    $jobDescription = $row["job_description"];
                    $jobLocation = $row["job_location"];
                    $jobStatus = $row["job_status"];
                    $dateStarted = $row["date_started"];

                    $sql = "SELECT * FROM users WHERE user_id=?";

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $jobAuthorId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $authorResults = mysqli_stmt_get_result($stmt);
                    $authorRow = mysqli_fetch_assoc($authorResults);
                    $authorProfilePicture = $authorRow["profile_picture"];
                    $authorUsername = $authorRow["username"];

                    if (empty($authorProfilePicture)) {
                        $authorProfilePicture = "../assets/default_pfp.png";
                    }
                    ?>
                    <div class="job">
                        <div class="job-profile-picture">
                            <img src="<?php echo $authorProfilePicture ?>" alt="">
                        </div>
                        <div class="job-details">
                            <h1><?php echo $jobTitle ?></h1>
                            <p>Posted by: <?php echo $authorUsername ?></p>
                            <p>Location: <?php echo $jobLocation ?></p>
                            <p>Status: <?php echo $jobStatus ?></p>
                            <p>Date Started: <?php echo $dateStarted ?></p>
                            <p><?php echo $jobDescription ?></p>
                        </div>
                    </div>
                    <?php

                }

                ?>
            </div>
        </div>
</body>
</html>