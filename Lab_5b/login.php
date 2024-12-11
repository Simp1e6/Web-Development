<?php
session_start(); // Start the session to access session variables

// If user is already logged in, redirect to read.php
if (isset($_SESSION['user'])) {
    header("Location: read.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>

<body>
    <h1>Login Page</h1>
    
    <!-- Display error message if there's any -->
    <?php
    if (isset($_SESSION['login_error'])) {
        echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']); // Clear the error after displaying it
    }
    ?>

    <form action="authenticate.php" method="post">
        <label for="matric">Matric:</label>
        <input type="text" name="matric" id="matric" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input type="submit" name="submit" value="Login">
    </form>

    <p><a href="register.php">Register</a> here if you have not.</p>
</body>

</html>
