<?php

$jobId = $_POST["job-id"];

if (!isset($_POST["delete-task"])) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=nosubmit");
    exit();
}

$taskId = $_POST["task-id"];
$authorId = $_POST["author-id"];

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

$sql = "DELETE FROM tasks WHERE task_id=? AND job_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $taskId, $jobId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
    exit();
}

$sql = "DELETE FROM task_assignments WHERE job_id=? AND task_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "ii", $jobId, $taskId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-tasks.php?job-id=".$jobId."&deletion=success");
exit();