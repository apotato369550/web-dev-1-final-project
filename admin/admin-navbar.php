<?php
    include "../includes/get-pfp.inc.php";
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
                        <a href="#">Manage Account</a>
                    </li>
                    <li>
                        <a href="#">Manage Jobs</a>
                    </li>
                    <li>
                        <a href="#">Manage Client Requests</a>
                    </li>
                    <li>
                        <a href="#">Manage Announcements</a>
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