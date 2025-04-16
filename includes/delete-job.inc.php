<?php

if (!isset($_POST["delete-job"])) {
    header("Location: ../admin/manage-jobs.php?error=nosubmit");
    exit();
}

$jobId = $_POST["job-id"];
$jobAuthor = $_POST["author-id"];

include "dbh.inc.php";

$sql = "SELECT * FROM users WHERE user_id=? AND user_type='admin'";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $jobAuthor);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);

if (!$row) {
    header("Location: ../admin/manage-jobs.php?error=accountnotfound");
    exit();
}

$sql = "DELETE FROM jobs WHERE job_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $jobId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}

$sql = "DELETE FROM job_assignments WHERE job_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $jobId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}

$sql = "DELETE FROM tasks WHERE job_id=?";


if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $jobId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}
$sql = "DELETE FROM task_assignments WHERE job_id=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $jobId);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}

header("Location: ../admin/manage-jobs.php?success=jobdeleted");
exit();

