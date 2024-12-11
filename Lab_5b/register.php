<?php
include 'Database.php';
include 'User.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    // Collect and sanitize form inputs
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = new User($db);

    // Attempt to create the user
    $result = $user->createUser($matric, $name, $password, $role);

    // Handle the result
    if ($result === true) {
        echo '<script>alert("Registration successful! Redirecting to login page...");</script>';
        echo "<script>window.location.href='login.php';</script>";
    } else {
        // Display error message (duplicate matric or any other error)
        $error_message = $result; // Capture the error message for display
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <style>
        /* Basic styling for error messages */
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Register Page</h1>
    
    <!-- Display error message (if any) -->
    <?php
    if (isset($error_message)) {
        echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>

    <!-- Registration form -->
    <form action="register.php" method="post">
        <label for="matric">Matric:</label>
        <input type="text" name="matric" id="matric" required><br><br>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="">Please select</option>
            <option value="lecturer">Lecturer</option>
            <option value="student">Student</option>
        </select><br><br>

        <input type="submit" name="submit" value="Register">
        <p><a href="login.php">Login</a> here if you have already registered.</p>
    </form>
</body>

</html>
