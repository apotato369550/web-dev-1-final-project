<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Register - Cebu Best Value Trading</title>
</head>
<body>
    <div class="register-background-image-container">
        <div class="register-container">
            <div class="register-title-container">
                <h1>Cebu Best <br> Value Trading!</h1>
                <p>Note: Registration is to be reviewed and must <br>be approved by admin.</p>
                <?php 
                
                // do errors here

                ?>
            </div>
            <div class="register-form-container">
                <h2>Register</h2>
                <form action="includes/register.inc.php" method="post">
                    <h3>Name</h3>
                    <div class="register-form-duo">
                        <input type="text" name="first-name" placeholder="First Name" required>
                        <input type="text" name="last-name" placeholder="Last Name" required>
                    </div>
                    <h3>Contact Info</h3>
                    <div class="register-form-duo">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="text" name="phone-number" placeholder="Phone Number" required>
                    </div>
                    <h3>Username and Password</h3>                    
                    <input type="text" name="username" placeholder="Username" required>
                    <div class="register-form-duo">
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="password" name="repeat-password" placeholder="Repeat Password" required>
                    </div>
                    <h3>Account Type</h3>
                    <div class="register-form-duo">
                        <select name="account-type">
                            <option value="admin">Admin</option>
                            <option value="worker">Worker</option>
                            <option value="client">Client</option>
                        </select>
                        <button type="submit" name="register">Register</button> 
                    </div>         
                </form>
                <form action="index.php">
                    <p>Already have an account? Login instead!</p>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>