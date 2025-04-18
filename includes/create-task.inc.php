<?php

if (!isset($_POST["create-task"])) {
    header("Location: ../admin/manage-schedule.php?error=nosubmit");
    exit();
}

$jobId = $_POST["job-id"];
$authorId = $_POST["author-id"];
$taskName = $_POST["task-name"];
$taskDescription = $_POST["task-description"];
$workersAssigned = $_POST["workers-assigned"];
$taskDate = $_POST["task-date"];

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='admin'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-schedule.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-schedule.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-schedule.php?error=unauthorized");
    exit();
}

$sql = "INSERT INTO tasks (job_id, task_name, task_description, task_date) VALUES (?, ?, ?, ?)";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-schedule.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "isss", $jobId, $taskName, $taskDescription, $taskDate);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-schedule.php?error=sqlexecute");
    exit();
}

// get the inserted task id
$sql = "SELECT LAST_INSERT_ID() AS task_id";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-schedule.php?error=sqlprepare");
    exit();
}

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-schedule.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);
$taskId = $row["task_id"];

foreach($workersAssigned as $workerId) {
    $sql = "INSERT INTO task_assignments (job_id, task_id, worker_id) VALUES (?, ?, ?)";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-schedule.php?error=sqlprepare");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $jobId, $taskId, $workerId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-schedule.php?error=sqlexecute");
        exit();
    }
}

header("Location: ../admin/manage-schedule.php?success=taskcreated");
exit();

