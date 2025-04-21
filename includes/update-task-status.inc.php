<?php

include "dbh.inc.php";

$authorId = $_POST["author-id"];
$sql = "SELECT * FROM users WHERE user_id=?";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Error when preparing SQL statement.";
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    echo "Error while executing SQL statement";
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);
$userType = $row["user_type"];

if (!isset($_POST["update-task-status"])) {
    // get author id and check if user is admin or worker
    if ($userType == "admin") {
        $jobId = $_POST["job-id"];
        header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=nosubmit"); 
        exit();
    } else if ($userType == "worker") {
        header("Location: ../worker/view-assigned-jobs.php?error=nosubmit"); 
        exit();
    } else {
        header("Location: ../index.php?error=nosubmit"); 
        exit();
    }
}

$taskId = $_POST["task-id"];
$taskStatus = $_POST["task-status"];

if ($userType == "admin") {
    $jobId = $_POST["job-id"];
    $sql = "UPDATE tasks SET status=? WHERE task_id=?";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare"); 
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $taskStatus, $taskId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute"); 
        exit();
    }

    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&edit=success");

} else if ($userType == "worker") {
    $sql = "UPDATE tasks SET status=? WHERE task_id=?";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../worker/view-assigned-jobs.php?error=sqlprepare"); 
        exit();
    }   

    mysqli_stmt_bind_param($stmt, "si", $taskStatus, $taskId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../worker/view-assigned-jobs.php?error=sqlexecute"); 
        exit();
    }

    header("Location: ../worker/view-assigned-jobs.php?edit=success");
    exit();
} else {
    header("Location: ../index.php?error=unauthorized"); 
    exit();
}