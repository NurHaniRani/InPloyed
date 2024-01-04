<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include the database connection file
require_once('../control/dbconnect.php');

// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId']) && isset($_POST['newStatus'])) {
    $userId = $_POST['userId'];
    $newStatus = $_POST['newStatus'];
    $adminId = $_SESSION['user_id'];

    $currentDate = date("j F Y");
    $currentTime = date("g:i a");

    // Update the user status in the database
    $sqlUpdateStatus = "UPDATE user SET UserStatus = '$newStatus' WHERE UserID = '$userId'";
    $resultUpdateStatus = $conn->query($sqlUpdateStatus);

    if ($resultUpdateStatus === true) {
        // Update the admin's LastUpdateTime and LastUpdateDay
        $sqlUpdateAdmin = "UPDATE user SET LastUpdateTime = '$currentTime', LastUpdateDate = '$currentDate' WHERE UserID = '$adminId'";
        $resultUpdateAdmin = $conn->query($sqlUpdateAdmin);

        if ($resultUpdateAdmin === true) {
            echo 'Success';
        } else {
            echo 'Error updating admin information: ' . $conn->error;
        }
    } else {
        echo 'Error updating user status: ' . $conn->error;
    }

    $conn->close();
}
?>