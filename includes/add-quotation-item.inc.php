<?php

$quotationItemName = $_POST["quotation-item-name"];
$quotationItemDescription = $_POST["quotation-item-description"];
$quotationItemQuantity = $_POST["quotation-item-quantity"];
$quotationItemPrice = $_POST["quotation-item-price"];

include "../includes/dbh.inc.php";

if (isset($_POST["quotation-id"])) {
    $quotationId = $_POST["quotation-id"];

    // double check stmt
    $sql = "INSERT INTO quotation_items (quotation_id, item_name, item_description, item_quantity, item_cost) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Failed to insert quotation item due to failed SQL statement preparation";
        exit();
    } 
    mysqli_stmt_bind_param($stmt, "issss", $quotationId, $quotationItemName, $quotationItemDescription, $quotationItemQuantity, $quotationItemPrice);
    
    if (!mysqli_stmt_execute($stmt)) {
        echo "Failed to insert quotation item due to failed SQL statement execution";
        exit();
    }
    
    echo "Quotation item added successfully";
    exit();
} else {
    $requestId = $_POST["request-id"];
    $clientId = $_POST["client-id"];

    // double check stmt
    $sql = "INSERT INTO quotation_items (request_id, client_id, item_name, item_description, item_quantity, item_cost) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($connection);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Failed to insert quotation item due to failed SQL statement preparation";
        exit();
    } 
    mysqli_stmt_bind_param($stmt, "isssss", $requestId, $clientId, $quotationItemName, $quotationItemDescription, $quotationItemQuantity, $quotationItemPrice);
    
    if (!mysqli_stmt_execute($stmt)) {
        echo "Failed to insert quotation item due to failed SQL statement execution";
        exit();
    }
    
    echo "Quotation item added successfully";
    exit();
}






/*
echo $quotationItemName;
echo "<br>";
echo $quotationItemDescription;
echo "<br>";
echo $quotationItemQuantity;
echo "<br>";
echo $quotationItemPrice;
echo "<br>";
echo $quotationId;
echo "<br>";
*/