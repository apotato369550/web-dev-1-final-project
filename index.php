<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Cebu Best Value Trading</title>
</head>
<body>
    <?php 
    if (isset($_SESSION["user_type"])) {
        $userType = $_SESSION["user_type"];
        header("Location: ".$userType."/".$userType.".php");
    }
    ?>
    <div class="index-main-container">
        <div class="index-login-container">
            <div class="index-login">
                <h1>Cebu Best Value Trading!</h1>
                <form action="includes/login.inc.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <br>
                    <input type="password" name="password" placeholder="Password" required>
                    <br>
                    <button type="submit" name="login">Login</button>
                </form>
                <?php 
                if (!empty($_GET["error"])) {
                    $errorMessage = $_GET["error"];
                    if ($errorMessage === "sqlprepare" || $errorMessage === "sqlexecute") {
                        echo "<p class='error-message'>Error occured executing SQL query</p>";
                    }
                    if ($errorMessage === "login") {
                        echo "<p class='error-message'>Sorry, your username or password is invalid.</p>";                        
                    }
                }
                ?>
            </div>
        </div>
        <div class="index-image-container">
            <div class="index-button-container">
                <h2>New user?</h2>
                <form action="register.php">
                    <button type="submit">Register</button>
                </form>
                <h2>Already registered? <br> View your application status!</h2>
                <form action="check-status.php">
                    <button>Check Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>