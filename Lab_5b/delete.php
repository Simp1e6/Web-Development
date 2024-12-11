<?php
include 'Database.php';
include 'User.php';

// Start the session to manage login state
session_start();

// Ensure the user is logged in, otherwise redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve the matric value from the GET request
    if (isset($_GET['matric']) && !empty($_GET['matric'])) {
        $matric = $_GET['matric'];

        // Create an instance of the Database class and get the connection
        $database = new Database();
        $db = $database->getConnection();

        // Create an instance of the User class
        $user = new User($db);

        // Try to delete the user
        if ($user->deleteUser($matric)) {
            // Redirect with a success message
            header("Location: read.php?message=User deleted successfully");
        } else {
            // Redirect with an error message
            header("Location: read.php?message=Error deleting user");
        }

        // Close the connection
        $db->close();
    } else {
        // If no matric is provided, redirect with an error message
        header("Location: login.php?message=Invalid request");
    }
}
?>
