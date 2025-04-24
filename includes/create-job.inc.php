<?php
if (!isset($_POST["create-job"])) {
    header("Location: ../admin/manage-jobs.php?error=nosubmit");
    exit();
}

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
    $sql = "INSERT INTO jobs (job_author, job_title, job_description, job_location, date_finished, job_status) VALUES (?, ?, ?, ?, NOW(), ?)";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "issss", $jobAuthor, $jobTitle, $jobDescription, $jobLocation, $jobStatus);
    
    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
} else {
    $sql = "INSERT INTO jobs (job_author, job_title, job_description, job_location, job_status) VALUES (?, ?, ?, ?, ?)";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "issss", $jobAuthor, $jobTitle, $jobDescription, $jobLocation, $jobStatus);
    
    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
}

// do something about the workers
$sql = "SELECT * FROM jobs WHERE job_author = ? AND job_title=? AND job_description=? AND job_location=? AND job_status=?";

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../admin/manage-jobs.php?error=sqlprepare");
    exit();
}

mysqli_stmt_bind_param($stmt, "issss", $jobAuthor, $jobTitle, $jobDescription, $jobLocation, $jobStatus);

if (!mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/manage-jobs.php?error=sqlexecute");
    exit();
}

$results = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($results);
$jobId = $row["job_id"];

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

foreach($workersList as $worker) {
    $sql = "INSERT INTO job_assignments (job_id, worker_id) VALUES (?, ?)";
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../admin/manage-jobs.php?error=sqlprepare6");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $jobId, $worker);
    
    if (!mysqli_stmt_execute($stmt)) {
        header("Location: ../admin/manage-jobs.php?error=sqlexecute");
        exit();
    }
}

header("Location: ../admin/manage-jobs.php?create=success");
exit();