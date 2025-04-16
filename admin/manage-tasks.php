<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        $sql = "SELECT * FROM tasks WHERE task_id=?";
                        $stmt = mysqli_stmt_init($connection);

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        } else {
                            mysqli_stmt_bind_param($stmt, "i", $taskId);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            if ($row = mysqli_fetch_assoc($result)) {
                                $taskTitle = $row["task_title"];
                                $taskDescription = $row["task_description"];
                            } else {
                                echo "Error when fetching task data.";
                            }

                        }
                        ?>
                        <form action="../edit-task.inc.php">
                            <input type="hidden" name="task-id" value="<?php echo $_GET["task-id"]; ?>">
                            <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                            <input type="text" name="task-title" placeholder="Task Title" value="<?php echo $taskTitle ?>" required>
                            <textarea name="task-description" placeholder="Task Description" required>
                                <?php 
                                echo $taskDescription;
                                ?>
                            </textarea>
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
                            // continue here

                            while ($allWorkersRow = mysqli_fetch_assoc($result)) {
                                ?> 
                                <input type="checkbox" name="workers[]" value="<?php echo $allWorkersRow["user_id"]; ?>" <?php if (in_array($allWorkersRow["user_id"], $assignedWorkers)) echo "checked"; ?>>
                                <?php
                            }
                            
                            ?>
                        </form>
                        <?php
                    } else {
                        ?>
                        <?php
                    }
                    ?>
                </div>
                <div class="manage-tasks-title">
                    <h1>Manage Tasks</h1>
                </div>
                <div class="manage-tasks-list">
                    <?php 
                    // get all tasks from database
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>