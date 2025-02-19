<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cebu Best Value Trading</title>
</head>
<body>
    <h1>Welcome to Cebu Best Value Trading!</h1>
    <h2>Register</h2>
    <form action="includes/register.inc.php" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username">
        <br>
        <label for="password">Password: </label>
        <input type="password" name="password">
        <br>
        <label for="repeat-password">Repeat Password: </label>
        <input type="password" name="repeat-password">
        <br>
        <label for="account-type">Account Type</label>
        <select name="account-type">
            <option value="admin">Admin</option>
            <option value="worker">Worker</option>
            <option value="client">Client</option>
        </select>
        <br>
        <input type="submit" name="register">
        <p>Note: Registrants must undergo admin approval</p>
    </form>
    <a href="login.php">Login</a>
</body>
</html>