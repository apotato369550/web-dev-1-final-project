<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Tasks - Cebu Best Value Trading</title>
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
        <div class="manage-tasks-container">
            <div class="manage-tasks">
                <div class="manage-tasks-title">
                    <?php 
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true") {
                        ?>
                        <h1>Edit Task</h1>
                        <?php
                    } else {
                        ?>
                        <h1>Create Task</h1>
                        <?php
                    }
                    ?>
                </div>
                <div class="manage-tasks-form">
                    <?php 
                    // make a form to create a task
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["task-id"])) {
                        include "../includes/dbh.inc.php";
                        $taskId = $_GET["task-id"];
                        $jobId = $_GET["job-id"];
                        $sql = "SELECT * FROM tasks WHERE task_id=? AND job_id=?";
                        $stmt = mysqli_stmt_init($connection);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        } 

                        mysqli_stmt_bind_param($stmt, "ii", $taskId, $jobId);

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }
                       
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $taskName = $row["task_name"];
                        $taskDescription = $row["task_description"];
                        $taskDate = $row["task_date"];

                        ?>
                        <form action="../includes/edit-task.inc.php" method="post">
                            <input type="hidden" name="task-id" value="<?php echo $_GET["task-id"]; ?>">
                            <input type="hidden" name="job-id" value="<?php echo $_GET["job-id"]; ?>">
                            <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                            <input type="text" name="task-name" placeholder="Task Name" value="<?php echo $taskName ?>" required>
                            <textarea name="task-description" placeholder="Task Description" required> <?php echo $taskDescription; ?> </textarea>
                            <input type="date" name="task-date" placeholder="Task Deadline" value="<?php echo $taskDate ?>" required>
                            <button type="submit" name="edit-task">Edit Task</button>
                            <?php
                            $sql = "SELECT * FROM users WHERE user_type='worker'";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "Error when preparing SQL statement.";
                            } 

                            if (!mysqli_stmt_execute($stmt)) {
                                echo "Error while executing SQL statement";
                            } 

                            $allWorkersResult = mysqli_stmt_get_result($stmt);

                            $sql = "SELECT * FROM task_assignments WHERE task_id=?";
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "Error when preparing SQL statement.";
                            }
                            mysqli_stmt_bind_param($stmt, "i", $taskId);
                            mysqli_stmt_execute($stmt);
                            $assignedWorkersResult = mysqli_stmt_get_result($stmt);
                            $assignedWorkers = array();

                            while ($assignedWorkersRow = mysqli_fetch_assoc($assignedWorkersResult)) {
                                array_push($assignedWorkers, $assignedWorkersRow["worker_id"]);
                            }
                            // continue here
                            ?> 
                            <div class="manage-tasks-checkbox-container">
                            <?php
                            while ($allWorkersRow = mysqli_fetch_assoc($allWorkersResult)) {
                                ?> 
                                <div class="checkbox-container">
                                    <input type="checkbox" name="workers[]" value="<?php echo $allWorkersRow["user_id"]; ?>" <?php if (in_array($allWorkersRow["user_id"], $assignedWorkers)) echo "checked"; ?>>
                                    <label for="workers[]"><?php echo $allWorkersRow["username"] ?></label>
                                </div>
                                <?php
                            }
                            
                            ?>
                        </form>
                        <form action="manage-tasks.php" method="get">
                            <br>
                            <button type="submit">Create a task instead</button>
                        </form>
                        <?php
                    } else {
                        ?>
                        <form action="../includes/create-task.inc.php" method="post">
                        <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                        <input type="hidden" name="job-id" value="<?php echo $_GET["job-id"]; ?>">
                        <input type="text" name="task-name" placeholder="Task Name" required>
                        <textarea name="task-description" placeholder="Task Description" required>Enter task description here...</textarea>
                        <input type="date" name="task-date" placeholder="Task Deadline" required>
                        <button type="submit" name="create-task">Create Task</button>
                        <?php
                        $sql = "SELECT * FROM users WHERE user_type='worker'";
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        } 

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        } 

                        $allWorkersResult = mysqli_stmt_get_result($stmt);

                        ?> 
                        <div class="manage-tasks-checkbox-container">
                        <?php
                        while ($allWorkersRow = mysqli_fetch_assoc($allWorkersResult)) {
                            ?> 
                            <div class="checkbox-container">
                                <input type="checkbox" name="workers[]" value="<?php echo $allWorkersRow["user_id"]; ?>"?>
                                <label for="workers[]"><?php echo $allWorkersRow["username"] ?></label>
                            </div>
                            <?php
                        }
                        ?>
                        </form>
                        <?php
                    }
                    ?>
                    </div>
                </div>
                <div class="manage-tasks-title">
                    <h1>Manage Tasks</h1>
                </div>
                <div class="manage-tasks-list">
                    <?php 
                    // get all tasks from database
                    $sql = "SELECT * FROM tasks WHERE job_id=?";
                    $stmt = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $_GET["job-id"]);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $result = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $taskId = $row["task_id"];
                        $taskName = $row["task_name"];
                        $taskDescription = $row["task_description"];
                        $taskDate = $row["task_date"];
                        ?>
                        <div class="task">
                            <div class="task-info">
                                <h2><?php echo $taskName; ?></h2>
                                <p><?php echo $taskDescription; ?></p>
                                <p>Date due: <?php echo $taskDate ?></p>
                            </div>
                            <div class="task-assigned">
                                <?php 
                                $sql = "SELECT * FROM task_assignments WHERE job_id=? AND task_id=?";

                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    echo "Error when preparing SQL statement.";
                                }

                                mysqli_stmt_bind_param($stmt, "ii", $_GET["job-id"], $taskId);

                                if (!mysqli_stmt_execute($stmt)) {
                                    echo "Error while executing SQL statement";
                                }

                                $assignedWorkersResult = mysqli_stmt_get_result($stmt);

                                while ($assignedWorkersRow = mysqli_fetch_assoc($assignedWorkersResult)) {
                                    $workerId = $assignedWorkersRow["worker_id"];
                                    $sql = "SELECT * FROM users WHERE user_id=? AND application_status='approved'";
                                    $stmt = mysqli_stmt_init($connection);

                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                        echo "Error when preparing SQL statement.";
                                    } 

                                    mysqli_stmt_bind_param($stmt, "i", $workerId);

                                    if (!mysqli_stmt_execute($stmt)) {
                                        echo "Error while executing SQL statement";
                                    } 

                                    $workerResult = mysqli_stmt_get_result($stmt);
                                    $workerRow = mysqli_fetch_assoc($workerResult);

                                    // insert sample data here
                                    
                                    if ($workerRow) {
                                        $workerUsername = $workerRow["username"];
                                        ?>  
                                        <p><?php echo $workerUsername ?></p>
                                        <?php
                                    }
                                }   
                                ?>
                            </div>
                            <div class="task-buttons">
                                <form action="manage-tasks.php" method="get">
                                    <input type="hidden" name="job-id" value="<?php echo $_GET["job-id"]; ?>">
                                    <input type="hidden" name="task-id" value="<?php echo $taskId; ?>" hidden>
                                    <button type="submit" name="edit" value="true">Edit</button>
                                </form>
                                <form action="../includes/delete-task.inc.php" method="post">
                                    <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                                    <input type="hidden" name="job-id" value="<?php echo $_GET["job-id"]; ?>">
                                    <input type="hidden" name="task-id" value="<?php echo $taskId; ?>">
                                    <button type="submit" name="delete-task">Delete</button>
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