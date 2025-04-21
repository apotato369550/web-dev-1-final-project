<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="worker.css">
    <title>Worker - Cebu Best Value Trading</title>
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
                <h1>Welcome Worker!</h1>
            </div>

            <div class="worker-content-announcement"><?php
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

            <div class="worker-content-title">
                <h1>Your Tasks for Today</h1>
            </div>

            <div class="worker-content-tasks">
                <?php
                $userId = $_SESSION["user_id"];
                $stmt = mysqli_stmt_init($connection);
                // get jobs with tasks assigned to the user for today
                $sql = "SELECT * FROM jobs WHERE job_id IN (SELECT job_id FROM task_assignments WHERE worker_id=?) AND job_id IN (SELECT job_id FROM tasks WHERE task_date=CURDATE())";

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "Error when preparing SQL statement.";
                }

                mysqli_stmt_bind_param($stmt, "i", $userId);

                if (!mysqli_stmt_execute($stmt)) {
                    echo "Error while executing SQL statement";
                }

                $results = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($results)) {
                    $jobId = $row["job_id"];
                    $jobName = $row["job_title"];
                    $jobDescription = $row["job_description"];
                    $jobDate = $row["date_started"];

                    ?> 
                    <div class="worker-content-job">
                        <div class="worker-content-job-details">
                            <h1><?php echo $jobName ?></h1>
                            <p><?php echo $jobDescription ?></p>
                            <p>Date Created: <?php echo $jobDate ?></p>
                        </div>
                        <div>
                            <h1>Tasks found: </h1>
                        </div>
                        <?php
                        // get tasks for the job that are assigned to the user
                        $sql = "SELECT * FROM tasks WHERE job_id=? AND task_id IN (SELECT task_id FROM task_assignments WHERE worker_id=?) AND task_date=CURDATE()";
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "ii", $jobId, $userId);

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }

                        $taskResults = mysqli_stmt_get_result($stmt);
                        while ($taskRow = mysqli_fetch_assoc($taskResults)) {
                            $taskId = $taskRow["task_id"];
                            $taskName = $taskRow["task_name"];
                            $taskDescription = $taskRow["task_description"];
                            $taskDate = $taskRow["task_date"];
                            $taskStatus = $taskRow["status"];

                            ?> 
                            <div class="worker-content-task">
                                <div class="worker-content-task-info">
                                    <h2><?php echo $taskName ?></h2>
                                    <p><?php echo $taskDescription ?></p>
                                    <p>Due Date: <?php echo $taskDate ?></p>
                                </div>
                                <div class="worker-content-task-buttons">
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
    </div>
</body>
</html>