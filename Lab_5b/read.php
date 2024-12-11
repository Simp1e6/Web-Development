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

// Create an instance of the Database class and get the connection
$database = new Database();
$db = $database->getConnection();

// Create an instance of the User class
$user = new User($db);
$result = $user->getUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script>
        // Function to confirm deletion
        function confirmDelete(matric) {
            // Display confirmation dialog
            if (confirm("Are you sure you want to delete " + matric + "?")) {
                // If confirmed, redirect to delete.php with the matric
                window.location.href = "delete.php?matric=" + matric;
            }
        }
    </script>
</head>

<body>
    <h1>User List</h1>

    <!-- Logout button -->
    <form action="logout.php" method="post">
        <input type="submit" value="Logout" />
    </form><br>

    <table border="1">
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Role</th>
            <th colspan="2">Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["matric"]); ?></td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["role"]); ?></td>
                    <td><a href="update.php?matric=<?php echo urlencode($row["matric"]); ?>">Update</a></td>
                    <td><a href="javascript:void(0);" onclick="confirmDelete('<?php echo urlencode($row['matric']); ?>')">Delete</a></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='5'>No users found</td></tr>";
        }
        ?>
    </table>
</body>

</html>
