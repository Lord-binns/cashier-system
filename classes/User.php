<?php
require_once "Database.php";

class User extends Database {
    private $error;
    private $user_id;

    // Method for registering a user
    public function register($first_name, $last_name, $username, $password) {
        // Check if the username already exists
        $sql_check = "SELECT * FROM users WHERE username = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            $this->error = "Username already taken";
            return false;
        }
    
        // Proceed with registration if username is available
        $sql = "INSERT INTO users (first_name, last_name, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $first_name, $last_name, $username, $password);
        
        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            $this->error = $stmt->error; // Capture any error
            return false; // Registration failed
        }
    }

    // Method for logging in a user
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            $this->user_id = $user['id']; // Store the user's ID for session
            return true;
        } else {
            $this->error = "Invalid username or password";
            return false;
        }
    }

    // Method to retrieve the user ID (used for session management)
    public function getUserId() {
        return $this->user_id;
    }

    // Method to retrieve errors
    public function getError() {
        return $this->error;
    }

    // Method to retrieve user details by username
    public function getUserDetails($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method to fetch all users
    public function getAllUsers() {
        $sql = "SELECT * FROM users"; // Query to select all users
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); // Return users as an associative array
        } else {
            die("Error executing SQL statement: " . $stmt->error);
        }
    }

    // Method to update a user
    public function updateUser($id, $first_name, $last_name, $username, $role) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, username = ?, role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $first_name, $last_name, $username, $role, $id);
        
        return $stmt->execute(); // Execute and return true/false
    }
}
