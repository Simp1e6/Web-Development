<?php
session_start(); // Start the session to store session variables

include 'Database.php';
include 'User.php';

if (isset($_POST['submit']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();

    // Sanitize inputs using mysqli_real_escape_string
    $matric = $db->real_escape_string($_POST['matric']);
    $password = $db->real_escape_string($_POST['password']);

    // Validate inputs
    if (!empty($matric) && !empty($password)) {
        $user = new User($db);
        $userDetails = $user->getUser($matric);

        // Check if user exists and verify password
        if ($userDetails && password_verify($password, $userDetails['password'])) {
            // Successful login, store user details in session
            $_SESSION['user'] = $userDetails; // Store user details in session
            header("Location: read.php"); // Redirect to read.php
            exit(); // Ensure the script stops executing after redirect
        } else {
            // Invalid matric or password, set error message in session
            $_SESSION['login_error'] = "Invalid matric or password, try login again.";
            header("Location: login.php"); // Redirect to login page
            exit();
        }
    } else {
        // Missing required fields, set error message
        $_SESSION['login_error'] = "Please fill in all required fields.";
        header("Location: login.php"); // Redirect back to login page
        exit();
    }
}
?>
