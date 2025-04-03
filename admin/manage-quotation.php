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
                <div>
                        <!-- do stuff here -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>