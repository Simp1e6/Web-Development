<?php
session_start();

include 'Database.php';
include 'User.php';

// Ensure the user is logged in, otherwise redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Create an instance of the Database class and get the connection
$database = new Database();
$db = $database->getConnection();

// Create an instance of the User class
$user = new User($db);

// Check if the form has been submitted via GET (for editing) or POST (for updating)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    // Retrieve the matric value from the GET request
    $matric = $_GET['matric'];

    // Fetch user details from the database
    $userDetails = $user->getUser($matric);

    // If no user is found, show an error message
    if (!$userDetails) {
        echo "User not found!";
        exit;
    }

    // Display the update form with pre-filled data
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update User</title>
    </head>
    <body>
        <h2>Update User Information</h2>

        <form action="update.php" method="post">
            <!-- Matric input field -->
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" value="<?php echo htmlspecialchars($userDetails['matric']); ?>" readonly><br><br>

            <!-- Name input field -->
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>"><br><br>

            <!-- Role selection dropdown -->
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="lecturer" <?php if ($userDetails['role'] == 'lecturer') echo "selected"; ?>>Lecturer</option>
                <option value="student" <?php if ($userDetails['role'] == 'student') echo "selected"; ?>>Student</option>
            </select><br><br>

            <!-- Submit button -->
            <input type="submit" value="Update">
        </form>

        <br><a href="read.php">Back to User List</a>
    </body>
    </html>
    <?php
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the data from the POST request (form submission)
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update the user in the database
    $result = $user->updateUser($matric, $name, $role);

    // Check if the update was successful
    if ($result === true) {
        // Update successful, show a success message and redirect back to the user list
        echo '<script>alert("Update Successful!");</script>';
        echo '<script>window.location.href="read.php";</script>';
    } else {
        // Display an error message if the update fails
        echo '<script>alert("Error: ' . $result . '");</script>';
        echo '<script>window.history.back();</script>'; // Go back to the previous page
    }
}

// Close the database connection
$db->close();
?>
