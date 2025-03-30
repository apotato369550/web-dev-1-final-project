<nav class="nav-sidebar">
    <div class="nav-sidebar-title">
        <h1>Admin Controls</h1>
    </div>
    <?php 
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode("/", $uri)[count(explode("/", $uri)) - 1];
    // echo $uri;
    ?>
    <div class="nav-sidebar-container">
        <div class="nav-sidebar-content">
            <ul>
                <li>
                    <a href="admin.php" class="<?php if ($uri === "admin.php") { echo "nav-sidebar-selected"; } ?>">Home</a>
                </li>
                <li>
                    <a href="manage-accounts.php" class="<?php if ($uri === "manage-accounts.php") { echo "nav-sidebar-selected"; } ?>">Accounts</a>
                </li>
                <li>
                    <a href="manage-jobs.php" class="<?php if ($uri === "manage-jobs.php") { echo "nav-sidebar-selected"; } ?>">Jobs</a>
                </li>
                <li>
                    <a href="manage-requests.php" class="<?php if ($uri === "manage-requests.php") { echo "nav-sidebar-selected"; } ?>">Client Requests</a>
                </li>
                <li>
                    <a href="manage-announcements.php" class="<?php if ($uri === "manage-announcements.php") { echo "nav-sidebar-selected"; } ?>">Announcements</a>
                </li>
                <li>
                    <a href="#">Schedule</a>
                </li>
                <li>
                    <a href="#">Quotations</a>
                </li>
            </ul>
        </div>
    </div>
</nav>