<?php

if (!isset($_POST["edit-task"])) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=unauthorized");
    exit();
}

$taskId = $_POST["task-id"];
$jobId = $_POST["job-id"];
$authorId = $_POST["author-id"];
$taskName = $_POST["task-name"];
$taskDescription = $_POST["task-description"];
$workersAssigned = $_POST["workers"];
$taskDate = $_POST["task-date"];

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='admin'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $authorId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=unauthorized");
    exit();
}

$sql = "UPDATE tasks SET task_name=?, task_description=?, task_date=? WHERE task_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "sssi", $taskName, $taskDescription, $taskDate, $taskId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
    exit();
}

$sql = "DELETE FROM task_assignments WHERE task_id=?";  

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $taskId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
    exit();
}

foreach ($workersAssigned as $workerId) {
    $sql = "INSERT INTO task_assignments (job_id, task_id, worker_id) VALUES (?, ?, ?)";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-tasks.php?error=sqlprepare");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $jobId, $taskId, $workerId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
        exit();
    }
}

header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&update=success");
exit();