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
            <div class="action-message">
                <?php
                if (isset($_GET["error"])) {
                    $error = $_GET["error"];
                    if ($error === "sqlprepare") {
                        echo "<p class='error'>There was an error preparing the SQL statement.</p>";
                    } elseif ($error === "sqlexecute") {
                        echo "<p class='error'>There was an error executing the SQL statement.</p>";
                    } elseif ($error === "requestnotfound") {
                        echo "<p class='error'>The request you are trying to update does not exist.</p>";
                    } elseif ($error === "unauthorized") {
                        echo "<p class='error'>You are not authorized to perform this action.</p>";
                    } elseif ($error === "nosubmit") {
                        echo "<p class='error'>No form submission detected.</p>";
                    }
                } else if (isset($_GET["update"])) {
                    $update = $_GET["update"];
                    if ($update === "success") {
                        echo "<p class='success'>Task edited successfully.</p>";
                    }
                } 
                ?>
            </div>
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
                    $sql = "SELECT * FROM jobs WHERE job_id=? AND job_status='in progress'";

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

                        <div>
                            <h1>Tasks Assigned: </h1>
                        </div>

                        <?php 
                        // select task assignments where job and worker matches
                        $sql = "SELECT * FROM task_assignments WHERE job_id=? AND worker_id=?";

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "ii", $jobId, $_SESSION["user_id"]);

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }

                        $taskResults = mysqli_stmt_get_result($stmt);
                        $assignedTaskIds = [];
                        
                        while ($taskRow = mysqli_fetch_assoc($taskResults)) {
                            array_push($assignedTaskIds, $taskRow["task_id"]);
                        }

                        // select tasks where task id is in assigned task ids
                        foreach($assignedTaskIds as $taskId) {
                            $sql = "SELECT * FROM tasks WHERE task_id=?";

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "Error when preparing SQL statement.";
                            }

                            mysqli_stmt_bind_param($stmt, "i", $taskId);

                            if (!mysqli_stmt_execute($stmt)) {
                                echo "Error while executing SQL statement";
                            }

                            $taskResults = mysqli_stmt_get_result($stmt);
                            $taskRow = mysqli_fetch_assoc($taskResults);
                            
                            $taskName = $taskRow["task_name"];
                            $taskDescription = $taskRow["task_description"];
                            $taskDate = $taskRow["task_date"];
                            $taskStatus = $taskRow["status"];
                            
                            ?>
                            <div class="job-task">
                                <div class="job-task-info">
                                    <h2><?php echo $taskName ?></h2>
                                    <p><?php echo $taskDescription ?></p>
                                    <p>Date: <?php echo $taskDate ?></p>
                                    <p>Status: <?php echo $taskStatus ?></p>
                                </div>
                                <div class="job-task-buttons">
                                    <form action="../includes/update-task-status.inc.php" method="post">
                                        <input type="hidden" name="task-id" value="<?php echo $taskId; ?>">
                                        <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                                        <select name="task-status">
                                            <option value="started" <?php if ($taskStatus === "started") { echo "selected"; } ?>>Started</option>
                                            <option value="in progress" <?php if ($taskStatus === "in progress") { echo "selected"; } ?>>In Progress</option>
                                            <option value="completed" <?php if ($taskStatus === "completed") { echo "selected"; } ?>>Completed</option>
                                        </select>
                                        <button type="submit" name="update-task-status">Update Status</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }

                        ?>
                    </div>
                    <?php

                }

                ?>
            </div>
        </div>
</body>
</html>