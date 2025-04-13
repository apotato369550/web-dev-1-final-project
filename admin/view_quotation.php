<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>View Quotation - Cebu Best Value Trading</title>
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
        <div class="view-quotation-title">
            <h1>View Quotation</h1>
        </div>
        <div class="view-quotation">
            <?php
            $quotationId = $_GET["quotation_id"];
            if (empty($quotationId)) {
                echo "quotation not found.";
            }
            include "../includes/dbh.inc.php";
            $sql = "SELECT * FROM quotations WHERE quotation_id = ?;";
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
                echo "quotation not found";
            } else {
                $quotationName = $row["quotation_name"];
                $quotationLocation = $row["quotation_location"];
                $quotationDescription = $row["quotation_description"];
                
            }
            ?>
        </div>
    </div>
</body>
</html>