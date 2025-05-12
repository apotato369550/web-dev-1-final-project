<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="client.css">
    <title>View Quotation - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "client") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "client-navbar.php";
    ?>
    <div class="content">
        <div class="view-quotation-container">
            <div class="view-quotation">
                <div class="view-quotation-title">
                    <h1>View Quotation</h1>
                </div>
                <div class="view-quotation-info">
                    <?php
                    $quotationId = $_GET["quotation-id"];
                    if (empty($quotationId)) {
                        echo "Quotation not found.";
                    }
                    include "../includes/dbh.inc.php";
                    $sql = "SELECT * FROM quotations WHERE quotation_id=?";
                    $stmt = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $quotationId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($results);

                    if (!$row) {
                        echo "Quotation not found";
                    } else {
                        $quotationName = $row["quotation_name"];
                        $quotationLocation = $row["quotation_location"];
                        $quotationDescription = $row["quotation_description"];
                        $dateCreated = $row["date_created"];
                        $lastUpdated = $row["last_updated"];
                        
                        ?>
                        <h1><?php echo $quotationName ?></h1>
                        <h2>Location: <?php echo $quotationLocation ?></h2>
                        <p>Description: <?php echo $quotationDescription ?></p>
                        <p>Date Created: <?php echo $dateCreated ?></p>
                        <p>Last Updated: <?php echo $lastUpdated ?></p>
                        <a href="manage-requests.php">Go back to "Manage Requests" Page</a>
                        <?php
                    }
                    ?>
                </div>
                <div class="view-quotation-title">
                    <h1>Quotation Items</h1>
                </div>
                <div class="view-quotation-items">
                    <?php 
                    $totalQuotationCost = 0;
                    $sql = "SELECT * FROM quotation_items WHERE quotation_id=?";

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    mysqli_stmt_bind_param($stmt, "i", $quotationId);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($results)) {
                        $itemName = $row["item_name"];
                        $itemDescription = $row["item_description"];
                        $itemQuantity = $row["item_quantity"];
                        $itemCost = $row["item_cost"];
                        $itemTotalCost = $itemQuantity * $itemCost;
                        $totalQuotationCost += $itemTotalCost;

                        ?>
                        <div class="view-quotation-item">
                            <h2><?php echo $itemName ?></h2>
                            <p><?php echo $itemDescription ?></p>
                            <p>Quantity: <?php echo $itemQuantity ?></p>
                            <p>Price per item: <?php echo $itemCost ?></p>
                            <p>Total Price: <?php echo $itemTotalCost ?> </p>
                        </div>
                        <?php
                    }
                    
                    ?>
                    <div class="total-quotation-cost">
                        <h2>Total Quotation Cost: <?php echo $totalQuotationCost ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>