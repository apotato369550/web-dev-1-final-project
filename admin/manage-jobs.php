<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
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

    <div class="content">
        <?php
        include "admin-sidebar.php";
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
                            if(!mysqli_stmt_execute($stmt) ) {
                                echo "Error while executing SQL statement";
    
                            }
                            $results = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($results);
                            $jobTitle = $row["job_title"];
                            $jobDescription = $row["job_description"];
                            $jobLocation = $row["job_location"];
                            $jobStatus = $row["job_status"];
                            ?> 
                            <input type="number" value="<?php echo $jobId ?>" name="job-id" style="display: none;">
                            <input type="text" name="job-title" placeholder="Job Title" value="<?php echo $jobTitle ?>" required>
                            <input type="text" name="job-location" placeholder="Job Location" value="<?php echo $jobLocation ?>" required>
                            <textarea name="job-description" placeholder="Job Description" required><?php echo $jobDescription ?></textarea>
                            <select name="job-status">
                                <option value="in progress" <?php if ($jobStatus === "in progress") { echo "selected"; } ?>>In Progress</option>
                                <option value="finished" <?php if ($jobStatus === "finished") { echo "selected"; } ?>>Finished</option>
                                <option value="cancelled" <?php if ($jobStatus === "cancelled") { echo "selected"; } ?>>Cancelled</option>
                            </select>
                            <h2>Select Workers: </h2>
                            <div class="manage-jobs-workers-select">
                                <?php
                                $sql = "SELECT * FROM users WHERE user_type='worker'";
                                $stmt = mysqli_stmt_init($connection);
    
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    echo "Error when preparing SQL statement.";
                                }
    
                                if (!mysqli_stmt_execute($stmt)) {
                                    echo "Error while executing SQL statement";
                                }
    
                                $results = mysqli_stmt_get_result($stmt);
                                while ($row = mysqli_fetch_assoc($results)) {
                                    $currentWorkerId = $row["user_id"];
    
                                    $sql = "SELECT * FROM job_assignments WHERE job_id=? AND worker_id=?";
    
                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                        echo "Error when preparing SQL statement.";
    
                                    }
    
                                    mysqli_stmt_bind_param($stmt, "ii", $jobId, $currentWorkerId);
    
                                    if (!mysqli_stmt_execute($stmt)) {
                                        echo "Error while executing SQL statement";
                                    }
    
                                    $matchingJobResults = mysqli_stmt_get_result($stmt);
                                    $matchingResultsRow = mysqli_fetch_assoc($matchingJobResults);
    
    
                                    $sql = "SELECT * FROM users WHERE user_id=?";
                                    
                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                        echo "Error when preparing SQL statement.";
                                    }
    
                                    mysqli_stmt_bind_param($stmt, "i", $currentWorkerId);
    
                                    if (!mysqli_stmt_execute($stmt)) {
                                        echo "Error while executing SQL statement";
                                    }
    
                                    $foundWorker = mysqli_stmt_get_result($stmt);
                                    $foundWorkerRow = mysqli_fetch_assoc($foundWorker);
    
                                    $workerUsername = $foundWorkerRow["username"];
    
                                    ?>
                                    <div class="checkbox-container">
                                        <input type="checkbox" name="workers[]" value="<?php echo $currentWorkerId ?>" <?php if ($matchingResultsRow) { echo "checked"; } ?>>
                                        <label for="<?php echo $currentWorkerId ?>"><?php echo $workerUsername ?></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input type="submit" name="edit-job" value="Submit">
                            <?php
                        } else {
                            ?> 
                            <input type="text" name="job-title" placeholder="Job Title" required>
                            <input type="text" name="job-location" placeholder="Job Location" required>
                            <textarea name="job-description" placeholder="Job Description" required></textarea>
                            <select name="job-status">
                                <option value="in progress" selected>In Progress</option>
                                <option value="finished">Finished</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <h2>Select Workers: </h2>
                            <div class="manage-jobs-workers-select">
                                <?php
                                $sql = "SELECT * FROM users WHERE user_type='worker'";
                                $stmt = mysqli_stmt_init($connection);
    
                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    echo "Error when preparing SQL statement.";
                                }
    
                                if (!mysqli_stmt_execute($stmt)) {
                                    echo "Error while executing SQL statement";
                                }
    
                                $results = mysqli_stmt_get_result($stmt);
                                while ($row = mysqli_fetch_assoc($results)) {
                                    $currentWorkerId = $row["user_id"];
    
                                    $sql = "SELECT * FROM users WHERE user_id=?";
                                    
                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                        echo "Error when preparing SQL statement.";
                                    }
    
                                    mysqli_stmt_bind_param($stmt, "i", $currentWorkerId);
    
                                    if (!mysqli_stmt_execute($stmt)) {
                                        echo "Error while executing SQL statement";
                                    }
    
                                    $foundWorker = mysqli_stmt_get_result($stmt);
                                    $foundWorkerRow = mysqli_fetch_assoc($foundWorker);
    
                                    $workerUsername = $foundWorkerRow["username"];
                                    
    
                                    ?>
                                    <div class="checkbox-container">
                                        <input type="checkbox" name="workers[]" value="<?php echo $currentWorkerId ?>">
                                        <label for="<?php echo $currentWorkerId ?>"><?php echo $workerUsername ?></label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input type="submit" name="create-job" value="Submit">
                            <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="manage-jobs-title">
                    <h1>Jobs</h1>
                </div>
                <div class="manage-jobs-list">
                    <?php
                    include "../includes/dbh.inc.php";
    
                    function fetchJobs($connection) {
                        $sql = "SELECT * FROM jobs ORDER BY date_started DESC";
                        $stmt = mysqli_stmt_init($connection);
    
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                            return [];
                        }
    
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                            return [];
                        }
    
                        return mysqli_stmt_get_result($stmt);
                    }
    
                    function fetchWorkersForJob($connection, $jobId) {
                        $sql = "SELECT users.username FROM job_assignments 
                                JOIN users ON job_assignments.worker_id = users.user_id 
                                WHERE job_assignments.job_id=?";
                        $stmt = mysqli_stmt_init($connection);
    
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                            return [];
                        }
    
                        mysqli_stmt_bind_param($stmt, "i", $jobId);
    
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                            return [];
                        }
    
                        return mysqli_stmt_get_result($stmt);
                    }
    
                    $jobs = fetchJobs($connection);
    
                    while ($row = mysqli_fetch_assoc($jobs)) {
                        $currentJobId = $row["job_id"];
                        $currentJobTitle = $row["job_title"];
                        $currentJobDescription = $row["job_description"];
                        $currentJobLocation = $row["job_location"];
                        $currentJobStatus = $row["job_status"];
                        $currentDateStarted = $row["date_started"];
                        $currentDateFinished = $row["date_finished"];
                        ?>
                        <div class="job">
                            <div class="job-content">
                                <h1><?php echo $currentJobTitle ?></h1>
                                <p>Date: <?php echo $currentDateStarted ?></p>
                                <p>Location: <?php echo $currentJobLocation ?></p>
                                <p><?php echo $currentJobDescription ?></p>
                                <p>Status: <?php echo $currentJobStatus ?></p>
                                
                            </div>
                            <div class="job-workers">
                                <h2>Workers Assigned: </h2>
                                <?php 
                                $workers = fetchWorkersForJob($connection, $currentJobId);
    
                                while ($worker = mysqli_fetch_assoc($workers)) {
                                    ?>
                                    <p><?php echo $worker["username"] ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="job-buttons">
                                <form action="manage-jobs.php" method="get">
                                    <input type="text" name="edit" value="true" style="display: none;">
                                    <input type="number" name="job-id" value="<?php echo $currentJobId ?>" style="display: none;">
                                    <button type="submit">Edit Job</button>
                                </form>
                                <form action="../includes/delete-job.inc.php" method="post">
                                    <input type="number" name="author-id" value="<?php echo $_SESSION["user_id"] ?>" style="display: none;">
                                    <input type="number" name="job-id" value="<?php echo $currentJobId ?>" style="display: none;">
                                    <button type="submit" name="delete-job">Delete Job</button>
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