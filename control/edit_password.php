<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

require_once('../control/dbconnect.php');

$userid = $_SESSION['user_id'];
$oldPassword = mysqli_real_escape_string($conn, $_POST['oldPassword']);
$newPassword = md5(mysqli_real_escape_string($conn, $_POST['newPassword']));

// Get the current date and time
$currentDate = date("j F Y"); // Example: 3 January 2023
$currentTime = date("g:i a"); // Example: 3:00 pm

// Verify old password
$sqlVerifyPassword = "SELECT UserPassword FROM user WHERE UserID = '$userid'";
$resultVerifyPassword = $conn->query($sqlVerifyPassword);

if ($resultVerifyPassword->num_rows == 1) {
    $row = $resultVerifyPassword->fetch_assoc();
    if ($row['UserPassword'] === md5($oldPassword)) {
        // Old password is correct, proceed to update the password and set the last update date and time
        $sqlUpdatePassword = "UPDATE user SET UserPassword = '$newPassword', LastUpdateDate = '$currentDate', LastUpdateTime = '$currentTime' WHERE UserID = '$userid'";
        $resultUpdatePassword = $conn->query($sqlUpdatePassword);

        if ($resultUpdatePassword) {
            // Password updated successfully
            echo "<script>alert('Password Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
            exit();
        } else {
            // Handle the case when the update fails
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Old password is incorrect
        echo "<script>alert('Old Password is Incorrect!'); window.location.href='../admin/profile.php';</script>";
    }
} else {
    // Handle the case when user information is not found
    echo "User information not found.";
}
?>
