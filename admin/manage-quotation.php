<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Quotation - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "admin-navbar.php";
    ?>
    <script type="text/javascript">
    let xmlHttp = new XMLHttpRequest();
    let quotationItemLocalId = 0;

    function removeQuotationItem() {
        // figure this out
        console.log("Removing quotation item...");
        if (xmlHttp == null) {
            alert("Your browser does not support AJAX!");
            return;
        }
        let url = "../includes/remove-quotation-item.inc.php";

    }

    function clearInputFields() {
        document.getElementById("quotation-item-name").value = "";
        document.getElementById("quotation-item-description").value = "";
        document.getElementById("quotation-item-quantity").value = "";
        document.getElementById("quotation-item-price").value = "";
    }

    function stateChanged() {
        if (xmlHttp.readyState == 4) {
            if (xmlHttp.status == 200) {
                let response = xmlHttp.responseText;
                console.log(response);
                let quotationItemsResponse = document.getElementById("add-quotation-message");
                quotationItemsResponse.style.color = "green";
                quotationItemsResponse.innerHTML = response;
            } else {
                alert("Error: " + xmlHttp.statusText);
                let quotationItemsResponse = document.getElementById("add-quotation-message");
                quotationItemsResponse.style.color = "red";
                quotationItemsResponse.innerHTML = xmlHttp.statusText;
            }
        }
    }
    function addQuotationItem() {
        console.log("Adding Quotation item...");
        if (xmlHttp == null) {
            alert("Your browser does not support AJAX!");
            return;
        }
        let url = "../includes/add-quotation-item.inc.php";
        let quotationItemName = document.getElementById("quotation-item-name").value;
        let quotationItemDescription = document.getElementById("quotation-item-description").value;
        let quotationItemQuantity = document.getElementById("quotation-item-quantity").value;
        let quotationItemPrice = document.getElementById("quotation-item-price").value;

        // if empty, tell user to fill up form and return
        if (quotationItemName == "" || quotationItemDescription == "" || quotationItemQuantity == "" || quotationItemPrice == "") {
            let quotationItemsResponse = document.getElementById("add-quotation-message");
            quotationItemsResponse.innerText = "Please fill up all fields.";
            quotationItemsResponse.style.color = "red";
            return;
        }

        let edit = document.getElementById("edit").value;
        if (edit == "True") {
            let quotationId = document.getElementById("quotation-id").value;
            var params = "quotation-item-name=" + quotationItemName + "&quotation-item-description=" + quotationItemDescription + "&quotation-item-quantity=" + quotationItemQuantity + "&quotation-item-price=" + quotationItemPrice + "&quotation-id=" + quotationId;
        } else {
            let requestId = document.getElementById("request-id").value;
            let clientId = document.getElementById("client-id").value;
            var params = "quotation-item-name=" + quotationItemName + "&quotation-item-description=" + quotationItemDescription + "&quotation-item-quantity=" + quotationItemQuantity + "&quotation-item-price=" + quotationItemPrice + "&request-id=" + requestId + "&client-id=" + clientId;
        }
        console.log(params);
        // for debugging purposes
        xmlHttp.onreadystatechange=stateChanged; 
        xmlHttp.open("POST", url, true);
        xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlHttp.send(params);

        clearInputFields();

        let quotationItemsList = document.getElementById("quotation-items-list");

        let quotationItemDiv = document.createElement("div");
        quotationItemDiv.className = "quotation-item";

        // quotation information
        let quotationInformationDiv = document.createElement("div");
        let quotationItemNameHeader = document.createElement("h2");
        let quotationItemDescriptionText = document.createElement("p");
        let quotationItemQuantityText = document.createElement("p");
        let quotationItemPriceText = document.createElement("p");

        quotationInformationDiv.className = "quotation-item-information";
        quotationItemNameHeader.innerText = quotationItemName;
        quotationItemDescriptionText.innerText = quotationItemDescription;
        quotationItemQuantityText.innerText = "Quantity: " + quotationItemQuantity;
        quotationItemPriceText.innerText = "Price: " + quotationItemPrice;

        quotationInformationDiv.appendChild(quotationItemNameHeader);
        quotationInformationDiv.appendChild(quotationItemDescriptionText);
        quotationInformationDiv.appendChild(quotationItemQuantityText);
        quotationInformationDiv.appendChild(quotationItemPriceText);

        // make and append quotation remove button
        let quotationRemoveButtonDiv = document.createElement("div");
        let quotationRemoveButton = document.createElement("button");

        quotationRemoveButtonDiv.className = "quotation-item-buttons";
        quotationRemoveButton.addEventListener("click", removeQuotationItem);
        quotationRemoveButton.innerText = "Remove Item";

        quotationRemoveButtonDiv.appendChild(quotationRemoveButton);

        quotationItemDiv.appendChild(quotationInformationDiv);
        quotationItemDiv.appendChild(quotationRemoveButtonDiv);

        quotationItemsList.appendChild(quotationItemDiv);
        quotationItemDiv.setAttribute("id", "quotation-item-" + quotationItemLocalId);
        quotationItemLocalId++;
        console.log("Quotation item added successfully.");
    }
    
    </script>

    <div class="content">
        <?php
        include "admin-sidebar.php";
        ?>

        <div class="manage-quotation-container">
            <div class="manage-quotation">
                <div class="manage-quotation-title">
                    <?php
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true") {
                        ?>
                        <h1>Edit Quotation</h1>
                        <?php
                    } else {
                        ?>
                        <h1>Create Quotation</h1>
                        <?php
                    }
                    ?>
                </div>
                <div class="manage-quotation-form">
                    <?php
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["quotation-id"])) {
                        ?>
                        <form action="../includes/edit-quotation.inc.php" method="post" id="quotation-form">
                        <input type="hidden" name="quotation-id" value="<?php echo $_GET["quotation-id"]; ?>">
                        <?php
                    } else {
                        ?>
                        <form action="../includes/create-quotation.inc.php" method="post" id="quotation-form">
                        <input type="hidden" name="request-id" value="<?php echo $_GET["request-id"]; ?>">
                        <input type="hidden" name="client-id" value="<?php echo $_GET["client-id"]; ?>">
                        <?php
                    }
                    ?>
                        <input type="hidden" name="author-id" value="<?php echo $_SESSION["user_id"]; ?>">
                        <?php
                        if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["quotation-id"])) {
                            // if quotation exists
                            include "../includes/dbh.inc.php";
                            $stmt = mysqli_stmt_init($connection);
                            $sql = "SELECT * FROM quotations WHERE quotation_id=?";
                            $quotationId = $_GET["quotation-id"];

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "Error when preparing SQL statement.";
                            } 

                            mysqli_stmt_bind_param($stmt, "i", $quotationId);

                            if (!mysqli_stmt_execute($stmt)) {
                                echo "Error while executing SQL statement";
                            }
                            $results = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($results);

                            $quotationTitle = $row["quotation_name"];
                            $quotationLocation = $row["quotation_location"];
                            $quotationDescription = $row["quotation_description"];
                            ?>
                            <input type="text" name="quotation-name" value="<?php echo $quotationTitle; ?>" placeholder="Quotation Title" required>
                            <input type="text" name="quotation-location" value="<?php echo $quotationLocation; ?>" placeholder="Quotation Location" required>
                            <textarea name="quotation-description" placeholder="Quotation Description" required><?php echo $quotationDescription; ?></textarea>
                            <?php
                        } else {
                            // if quotation does not exist
                            ?>
                            <input type="text" name="quotation-name" placeholder="Quotation Title" required>
                            <input type="text" name="quotation-location" placeholder="Quotation Location" required>
                            <textarea name="quotation-description" placeholder="Quotation Description" required>Enter description Here</textarea>
                            <?php
                        }
                        ?>
                        <div class="quotation-submit-button">
                            <?php
                            if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["quotation-id"])) {
                                ?>
                                <button type="submit" name="edit-quotation">Edit Quotation</button>
                                <?php
                            } else {
                                ?>
                                <button type="submit" name="create-quotation">Create Quotation</button>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
                <div class="manage-quotation-title">
                    <h1>Add/Remove Quotation Items</h1>
                </div>
                <div class="manage-quotations-items-form">
                    <input type="text" id="quotation-item-name" placeholder="Quotation Item Name" required>
                    <input type="text" id="quotation-item-description" placeholder="Quotation Item Description" required>
                    <input type="number" id="quotation-item-quantity" placeholder="Quotation Item Quantity" required>
                    <input type="number" id="quotation-item-price" placeholder="Quotation Item Price" required>
                    <?php
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["quotation-id"])) {
                        ?>
                        <input type="hidden" id="quotation-id" value="<?php echo $_GET["quotation-id"]; ?>">
                        <input type="hidden" id="edit" value="True">
                        <?php
                    } else {
                        ?>
                        <input type="hidden" id="request-id" value="<?php echo $_GET["request-id"]; ?>">
                        <input type="hidden" id="client-id" value="<?php echo $_GET["client-id"]; ?>">
                        <input type="hidden" id="edit" value="False">
                        <?php

                    }
                    ?>
                    <button type="button" onclick="addQuotationItem()">Add Item</button>
                    <p id="add-quotation-message"></p>
                </div>
                <div class="manage-quotations-items-list" id="quotation-items-list">
                    <?php
                    // get all the tingz
                    include "../includes/dbh.inc.php";
                    if (isset($_GET["edit"]) && $_GET["edit"] == "true" && isset($_GET["quotation-id"])) {
                        $quotationId = $_GET["quotation-id"];
                        $sql = "SELECT * FROM quotation_items WHERE quotation_id=?";
                        $stmt = mysqli_stmt_init($connection);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }
                        mysqli_stmt_bind_param($stmt, "i", $quotationId);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }
                        $results = mysqli_stmt_get_result($stmt);
                        if (mysqli_num_rows($results) > 0) {
                            while ($row = mysqli_fetch_assoc($results)) {
                                $quotationItemName = $row["item_name"];
                                $quotationItemDescription = $row["item_description"];
                                $quotationItemQuantity = $row["item_quantity"];
                                $quotationItemPrice = $row["item_cost"];
                                ?>
                                <div class="quotation-item">
                                    <div class="quotation-item-information">
                                        <h2><?php echo $quotationItemName; ?></h2>
                                        <p><?php echo $quotationItemDescription; ?></p>
                                        <p>Quantity: <?php echo $quotationItemQuantity; ?></p>
                                        <p>Price: <?php echo $quotationItemPrice; ?></p>
                                    </div>
                                    <div class="quotation-item-buttons">
                                        <button type="button" onclick="removeQuotationItem()">Remove Item</button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?> 
                            <p>No quotation items found</p>
                            <?php
                        }
                    } else {
                        $requestId = $_GET["request-id"];
                        $clientId = $_GET["client-id"];
                        $sql = "SELECT * FROM quotation_items WHERE request_id=? AND client_id=?";
                        $stmt = mysqli_stmt_init($connection);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }
                        mysqli_stmt_bind_param($stmt, "ii", $requestId, $clientId);
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }
                        $results = mysqli_stmt_get_result($stmt);
                        if (mysqli_num_rows($results) > 0) {
                            while ($row = mysqli_fetch_assoc($results)) {
                                $quotationItemName = $row["item_name"];
                                $quotationItemDescription = $row["item_description"];
                                $quotationItemQuantity = $row["item_quantity"];
                                $quotationItemPrice = $row["item_cost"];
                                ?>
                                <div class="quotation-item">
                                    <div class="quotation-item-information">
                                        <h2><?php echo $quotationItemName; ?></h2>
                                        <p><?php echo $quotationItemDescription; ?></p>
                                        <p>Quantity: <?php echo $quotationItemQuantity; ?></p>
                                        <p>Price: <?php echo $quotationItemPrice; ?></p>
                                    </div>
                                    <div class="quotation-item-buttons">
                                        <button type="button" onclick="removeQuotationItem()">Remove Item</button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?> 
                            <p>No quotation items found</p>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>