<?php

// Include the database connection file
require_once('dbconnect.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch values from login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch the hashed password from the database based on the email
    $sql = "SELECT UserID, UserPassword, UserType FROM user WHERE UserEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword, $userType);
    $stmt->fetch();
    $stmt->close();

    // Verify the password using md5
    if ($hashedPassword === md5($password) && $userType === 'Admin') {
        // Admin login successful
        $_SESSION['user_id'] = $userId; // Store the user ID in the session
        echo "Admin login successful";
        header("Location: ../admin/dashboard.php");
    } else {
        // Invalid login
        echo "<script>alert('Invalid Login Info!'); window.location.href='../admin/login.php';</script>";
    }
} else {
    // Redirect to the login page if someone tries to access this file directly without a POST request
    header("Location: ../admin/login.php");
    exit();
}

$conn->close();

?>

