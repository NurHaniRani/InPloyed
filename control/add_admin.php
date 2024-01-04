<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


// Include the database connection file
require_once('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch values from the add admin form
    $adminID = $_POST['adminID'];
    $adminName = $_POST['adminName'];
    $adminEmail = $_POST['adminEmail'];
    $adminPassword = $_POST['adminPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $userStatus = $_POST['userStatus'];
    $userPosition = $_POST['userPosition'];

    // Check if passwords match
    if ($adminPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href='../admin/admin.php';</script>";
        exit();
    }

    // Hash the password using MD5
    $hashedPassword = md5($adminPassword);

    // Insert the new admin into the database
    $sqlAddAdmin = "INSERT INTO user (UserName, UserEmail, UserPassword, UserType, UserStatus, UserPosition) 
                    VALUES ('$adminName', '$adminEmail', '$hashedPassword', 'Admin', '$userStatus', '$userPosition')";
    
    $resultAddAdmin = $conn->query($sqlAddAdmin);

    if ($resultAddAdmin) {
        // Retrieve the last inserted ID
        $lastInsertedID = $conn->insert_id;

        // Update the AdminID for the newly inserted user
        $sqlUpdateAdminID = "UPDATE user SET AdminID = '$lastInsertedID' WHERE UserID = '$lastInsertedID'";
        $resultUpdateAdminID = $conn->query($sqlUpdateAdminID);

        if ($resultUpdateAdminID) {
            echo "<script>alert('Admin added successfully!'); window.location.href='../admin/admin.php';</script>";
        } else {
            echo "<script>alert('Error updating AdminID!'); window.location.href='../admin/admin.php';</script>";
        }
    } else {
        echo "<script>alert('Error adding admin!'); window.location.href='../admin/admin.php';</script>";
    }
} else {
    // Redirect to the dashboard page if someone tries to access this file directly without a POST request
    header("Location: ../admin/admin.php");
    exit();
}

$conn->close();
?>
