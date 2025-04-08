<?php
include "dbh.inc.php";

header('Content-Type: application/json'); // make sure response is marked as JSON

$rows = [];

if (isset($_POST["quotation-id"])) {
    $quotationId = $_POST["quotation-id"];
    $sql = "SELECT * FROM quotation_items WHERE quotation_id=?";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(["error" => "SQL statement preparation failed"]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $quotationId);
} else {
    $requestId = $_POST["request-id"];
    $clientId = $_POST["client-id"];
    $sql = "SELECT * FROM quotation_items WHERE request_id=? AND client_id=?";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo json_encode(["error" => "SQL statement preparation failed"]);
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $requestId, $clientId);
}

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => "SQL execution failed"]);
    exit();
}

$results = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($results)) {
    $rows[] = $row;
}

echo json_encode($rows);
exit();
