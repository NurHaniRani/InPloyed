<?php

// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Include the database connection file
require_once('dbconnect.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch values from login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch the hashed password and user data from the database based on the email
    $sql = "SELECT UserID, UserPassword, UserType, LastAccessDate, LastAccessTime FROM user WHERE UserEmail = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Handle the error, print it for now
        die('Error: ' . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword, $userType, $lastAccessDate, $lastAccessTime);
    $stmt->fetch();
    $stmt->close(); // Close the result set before preparing the next query

    // Verify the password using md5
    if ($hashedPassword === md5($password) && $userType === 'Admin') {
        // Admin login successful
        $_SESSION['user_id'] = $userId; // Store the user ID in the session

        // Update LastAccessDate and LastAccessTime
        $currentDate = date('d F Y'); // Example: 12 November 2023
        $currentTime = date('h:i a'); // Example: 3:00 pm

        $updateSql = "UPDATE user SET LastAccessDate = ?, LastAccessTime = ? WHERE UserID = ?";
        $updateStmt = $conn->prepare($updateSql);

        if (!$updateStmt) {
            // Handle the error, print it for now
            die('Error: ' . $conn->error);
        }

        $updateStmt->bind_param("sss", $currentDate, $currentTime, $userId);
        $updateStmt->execute();
        $updateStmt->close();

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
