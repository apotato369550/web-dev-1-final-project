<?php

if (!isset($_POST["quotation-item-id"])) {
    echo "Quotation ID or Request ID not set";
    exit();
}

$itemId = $_POST["quotation-item-id"];

include "dbh.inc.php";

$sql = "DELETE FROM quotation_items WHERE item_id = ?";
$stmt = mysqli_stmt_init($connection);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "Failed to prepare SQL statement";
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $itemId);

if (!mysqli_stmt_execute($stmt)) {
    echo "Failed to execute SQL statement";
    exit();
}

echo "Quotation item removed successfully";
exit();
