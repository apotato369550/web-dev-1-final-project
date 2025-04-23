<?php

header('Content-Type: application/json'); 

// do backend stuff
//send tasks within the date range in the form of json
$startDate = $_GET['start-date'];
$endDate = $_GET['end-date'];

$tasks = [];

include "dbh.inc.php";
$sql = "SELECT * FROM tasks WHERE task_date >= ? AND task_date <= ?";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo json_encode(["error" => "SQL statement preparation failed"]);
    exit();
}
mysqli_stmt_bind_param($stmt, "ss", $startDate, $endDate);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "SQL execution failed"]);
    exit();
}

$results = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($results)) {
    $tasks[] = $row;
}

echo json_encode($tasks);
exit();
