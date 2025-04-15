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
                        ?>
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