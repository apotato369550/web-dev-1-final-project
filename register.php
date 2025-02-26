<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Register - Cebu Best Value Trading</title>
</head>
<body>
    <div class="register-container">
        <h1>Cebu Best Value Trading!</h1>
        <h2>Register</h2>
        <form action="includes/register.inc.php" method="post">
            <label for="first-name">First Name</label>
            <input type="text" name="first-name" placeholder="First Name" required>

            <label for="last-name">Last Name</label>
            <input type="text" name="last-name" placeholder="Last Name" required>

            <label for="email">Email: </label>
            <input type="email" name="email" placeholder="Email" required>

            <label for="phone-number">Phone Number: </label>
            <input type="text" name="phone-number" placeholder="Phone Number" required>

            <label for="username">Username: </label>
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <label for="password">Password: </label>
            <input type="password" name="password" required>
            <br>
            <label for="repeat-password">Repeat Password: </label>
            <input type="password" name="repeat-password" required>
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
    </div>
</body>
</html>