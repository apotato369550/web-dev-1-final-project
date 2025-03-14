<?php

    include "../includes/dbh.inc.php";
    $userId = $_SESSION["user_id"];
    $profilePicPath = "../assets/default_pfp.png";

    $stmt = mysqli_stmt_init($connection);
    $sql = "SELECT * FROM users WHERE user_id=?";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $results = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($results);

    if (!empty($row["profile_picture"])) {
        $profilePicPath = "../uploads/profile-pictures/".$row["profile_picture"];
    }
?>

<nav>
    <script type="text/javascript">
        function openMenu() {
            let navbarContent = document.querySelector(".nav-dropdown-content");
            if (navbarContent.style.display == "none") {
                navbarContent.style.display = "block";
            } else {
                navbarContent.style.display = "none";
            }
        }
    </script>
    <div class="nav-title">
        <h1>Cebu Best Value Trading</h1>
    </div>
    <div class="nav-dropdown-menu">
        <div class="nav-dropdown-container">
            <div class="nav-dropdown-button-container">
                <button class="nav-dropdown-button" onclick="openMenu()">
                    <img src="../assets/down_arrow.png" alt="Down Arrow" class="nav-dropdown-button-arrow">
                    <img src="<?php echo $profilePicPath ?>" alt="Profile Pic" class="nav-dropdown-button-pfp">
                    <p><?php echo $_SESSION["username"] ?></p>
                </button>
            </div>
            <div class="nav-dropdown-content">
                <ul>
                    <li>
                        <a href="edit-profile.php">Edit Profile</a>
                    </li>
                    <li>
                        <a href="manage-accounts.php">Manage Accounts</a>
                    </li>
                    <li>
                        <a href="manage-jobs.php">Manage Jobs</a>
                    </li>
                    <li>
                        <a href="#">Manage Client Requests</a>
                    </li>
                    <li>
                        <a href="manage-announcements.php">Manage Announcements</a>
                    </li>
                    <li>
                        <a href="../includes/logout.inc.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script type="text/javascript">
            document.querySelector(".nav-dropdown-content").style.display = "none";
    </script>
</nav>