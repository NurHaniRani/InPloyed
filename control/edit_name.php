<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');
// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

$userid = $_SESSION['user_id'];
$newName = mysqli_real_escape_string($conn, $_POST['newName']);
// Get the current date and time
$currentDate = date("j F Y"); // Example: 3 January 2023
$currentTime = date("g:i a"); // Example: 3:00 pm

$sqlUpdateName = "UPDATE user SET UserName = '$newName', LastUpdateTime = '$currentTime', LastUpdateDate ='$currentDate' WHERE UserID = '$userid'";
$resultUpdateName = $conn->query($sqlUpdateName);

if ($resultUpdateName) {
    // Name updated successfully
    echo "<script>alert('Name Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
    exit();
} else {
    // Handle the case when the update fails
    echo "Error updating name: " . $conn->error;
}
?>
