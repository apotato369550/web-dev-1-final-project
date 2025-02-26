<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Cebu Best Value Trading</title>
</head>
<body>
    <div class="index-container">
        <h1>Cebu Best Value Trading!</h1>
        <form action="includes/login.inc.php" method="post">
            <label for="username">Username: </label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password: </label>
            <input type="password" name="password" required>
            <br>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
    <div class="index-container">
        <h2>New user?</h2>
        <a href="register.php">Register</a>
        <h2>Already registered? View your application status!</h2>
        <a href="check-status.php">Check Status</a>
    </div>
</body>
</html>