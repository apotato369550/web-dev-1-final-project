<?php

if (!isset($_POST["edit-job"])) {
    header("Location: ../admin/manage-jobs.php?error=nosubmit");
    exit();
}

$jobId = $_POST["job-id"];
$jobAuthor = $_POST["author-id"];
$jobTitle = $_POST["job-title"];
$jobLocation = $_POST["job-location"];
$jobDescription = $_POST["job-description"];
$jobStatus = $_POST["job-status"];
$workersList = $_POST["workers"];

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

if ($jobStatus === "finished") {
    $sql = "UPDATE jobs SET job_author=?, job_title=?, job_description=?, job_location=?, date_finished=NOW(), job_status=? WHERE job_id=?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "issssi", $jobAuthor, $jobTitle, $jobDescription, $jobLocation, $jobStatus, $jobId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
} else {
    $sql = "UPDATE jobs SET job_author=?, job_title=?, job_description=?, job_location=?, job_status=? WHERE job_id=?";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "issssi", $jobAuthor, $jobTitle, $jobDescription, $jobLocation, $jobStatus, $jobId);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
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

foreach ($workersList as $worker) {
    $sql = "INSERT INTO job_assignments (job_id, worker_id) VALUES (?, ?)";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $jobId, $worker);

    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
}

header("Location: ../admin/manage-jobs.php?success=jobedited");
exit();