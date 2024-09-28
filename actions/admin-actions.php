<?php
// Start session and require login
require_once('../actions/require_login.php');

// Include the User class to interact with the database
require_once "../classes/User.php";

// Create a new User instance
$user = new User();

// Fetch all users from the database
$users = $user->getAllUsers();

// Handle user update if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Call updateUser method to update the user in the database
    if ($user->updateUser($id, $first_name, $last_name, $username, $role)) {
        header("Location: ../views/adminList.php"); // Redirect with a message
        exit;
    } else {
        echo "<script>alert('Failed to update user.');</script>";
    }
}

?>
