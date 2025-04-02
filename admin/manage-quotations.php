<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Quotations - Cebu Best Value Trading</title>
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
        <div class="manage-quotations-container">
            <div class="manage-quotations">
                <div class="manage-quotations-requests">
                    <div class="manage-quotations-title">
                        <h1>Requests to Quote</h1>
                    </div>
                    <div class="manage-quotations-requests-list">
                    <?php
                    include "../includes/dbh.inc.php";
                    $sql = "SELECT * FROM client_requests ORDER BY date_created DESC";
                    $stmt = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    if(!mysqli_stmt_execute($stmt)) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);

                    while($row = mysqli_fetch_assoc($results)) {
                        $requestId = $row["request_id"];
                        $authorId = $row["author_id"];
                        $requestTitle = $row["request_title"];
                        $requestDescription = $row["request_description"];
                        $requestLocation = $row["request_location"];
                        $requestStatus = $row["request_status"];
                        $dateCreated = $row["date_created"];

                        $sql = "SELECT * FROM users WHERE user_id=? AND application_status='approved'";

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "i", $authorId);
                    
                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }
                        

                        $authorResults = mysqli_stmt_get_result($stmt);
                        $authorRow = mysqli_fetch_assoc($authorResults);

                        $firstName = $authorRow["first_name"];
                        $lastName = $authorRow["last_name"];
                        $email = $authorRow["email"];
                        $phoneNumber = $authorRow["phone_number"];
                        $profilePicture = $authorRow["profile_picture"];
                        
                        
                        if (empty($profilePicture)) {
                            $profilePicture = "../assets/default_pfp.png";
                        }

                        ?>
                        <div class="request">
                            <div class="request-profile-picture">
                                <img src="<?php echo $profilePicture ?>" alt="">
                            </div>
                            <div class="request-info">
                                <h1><?php echo $requestTitle?></h1>
                                <p><?php echo $requestDescription ?></p>
                                <p>Location: <?php echo $requestLocation ?></p>
                                <p>Date Created: <?php echo $dateCreated ?></p>
                                <p>Status: <?php echo $requestStatus ?></p>
                                <p>Full Name: <?php echo $lastName.", ".$firstName ?></p>
                                <p>Email: <?php echo $email ?> </p>
                                <p>Phone Number: <?php echo $phoneNumber ?> </p>
                            </div>
                            <div class="request-buttons">
                                <?php 
                                // if quotation exists for this request, show the view button
                                $sql = "SELECT * FROM quotations WHERE request_id=? AND client_id=?";

                                if (!mysqli_stmt_prepare($stmt, $sql)) {
                                    echo "Error when preparing SQL statement.";
                                }
        
                                mysqli_stmt_bind_param($stmt, "ii",$requestId , $authorId);
                            
                                if (!mysqli_stmt_execute($stmt)) {
                                    echo "Error while executing SQL statement";
                                }

                                $quotationResults = mysqli_stmt_get_result($stmt);
                                $quotationRow = mysqli_fetch_assoc($quotationResults);

                                if ($quotationRow) {
                                    $quotationId = $quotationRow["quotation_id"];
                                    ?>
                                    <form action="get" action="view-quote.php">
                                        <input type="hidden" name="quotation_id" value="<?php echo $quotationId ?>">
                                        <button type="submit" name="view-quote">View Quote</button>
                                    </form>
                                    <form action="get" action="edit-quote.php">
                                        <input type="hidden" name="quotation_id" value="<?php echo $quotationId ?>">
                                        <button type="submit" name="view-quote">Edit Quote</button>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <form action="get" action="edit-quote.php">
                                        <input type="hidden" name="request_id" value="<?php echo $requestId ?>">
                                        <input type="hidden" name="client_id" value="<?php echo $authorId ?>">
                                        <button type="submit" name="view-quote">Create Quote</button>
                                    </form>
                                    <?php
                                }
                                ?>



                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
                <div class="manage-quotations-quotations">
                    <div class="manage-quotations-title">
                        <h1>Current Quotations</h1>
                    </div>
                    <div class="manage-quotations-list">
                    <?php 
                    $sql = "SELECT * FROM quotations ORDER BY date_created DESC";
                    $stmt = mysqli_stmt_init($connection);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        echo "Error when preparing SQL statement.";
                    }

                    if(!mysqli_stmt_execute($stmt) ) {
                        echo "Error while executing SQL statement";
                    }

                    $results = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($results)) {
                        $quotationId = $row["quotation_id"];
                        $requestId = $row["request_id"];
                        $clientId = $row["client_id"];
                        $quotationName = $row["quotation_name"];
                        $quotationDescription = $row["quotation_description"];
                        $quotationLocation = $row["quotation_location"];
                        $dateCreated = $row["date_created"];
                        $lastUpdated = $row["last_updated"];

                        $sql = "SELECT * FROM users WHERE user_id=? AND application_status='approved'";

                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "Error when preparing SQL statement.";
                        }

                        mysqli_stmt_bind_param($stmt, "i", $clientId);

                        if (!mysqli_stmt_execute($stmt)) {
                            echo "Error while executing SQL statement";
                        }

                        $clientResults = mysqli_stmt_get_result($stmt);
                        $clientRow = mysqli_fetch_assoc($clientResults);
                        $profilePicture = $authorRow["profile_picture"];

                        if (empty($profilePicture)) {
                            $profilePicture = "../assets/default_pfp.png";
                        }

                        ?>  

                        <div class="quotation">
                            <div class="quotation-profile-picture">
                                <img src="<?php echo $profilePicture ?>" alt="">
                            </div>
                            <div class="quotation-info">
                                <h1><?php echo $quotationName ?></h1>
                                <p><?php echo $quotationDescription ?></p>
                                <p>Location: <?php echo $quotationLocation ?></p>
                                <p>Date Created: <?php echo $dateCreated ?></p>
                                <p>Last Updated: <?php echo $lastUpdated ?></p>
                            </div>
                            <div class="quotation-buttons">
                                <form action="get" action="view-quote.php">
                                    <input type="hidden" name="quotation_id" value="<?php echo $quotationId ?>">
                                    <button type="submit" name="view-quote">View Quote</button>
                                </form>
                            </div>
                        </div>


                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>