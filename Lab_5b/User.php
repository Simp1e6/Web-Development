<?php
class User
{
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new user
    public function createUser($matric, $name, $password, $role)
    {
        // First, check if the matric number already exists
        $checkQuery = "SELECT matric FROM users WHERE matric = ?";
        $stmt = $this->conn->prepare($checkQuery);

        if ($stmt) {
            $stmt->bind_param("s", $matric);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // If matric already exists, return error message
                $stmt->close();
                return "Error: The matric number is already in use. Please insert another.";
            }

            $stmt->close();
        } else {
            return "Error: " . $this->conn->error;
        }

        // Proceed to create user if matric number is unique
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $matric, $name, $password, $role);
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                return true; // Registration successful
            } else {
                $stmt->close();
                return "Error: " . $stmt->error;
            }
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Read all users
    public function getUsers()
    {
        $sql = "SELECT matric, name, role FROM users";
        $result = $this->conn->query($sql);
        return $result;
    }

    // Read a single user by matric
    public function getUser($matric)
    {
        $sql = "SELECT * FROM users WHERE matric = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $matric);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            $stmt->close();
            return $user;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Update a user's information
    public function updateUser($matric, $name, $role)
    {
        $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $role, $matric);
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                return true;
            } else {
                return "Error: " . $stmt->error;
            }
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Delete a user by matric
    public function deleteUser($matric)
    {
        $sql = "DELETE FROM users WHERE matric = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $matric);
            $result = $stmt->execute();

            if ($result) {
                $stmt->close();
                return true;
            } else {
                return "Error: " . $stmt->error;
            }

        } else {
            return "Error: " . $this->conn->error;
        }
    }
}

?>
