<?php
session_start();

// Include the database connection file
require_once('../control/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId']) && isset($_POST['newStatus'])) {
    $userId = $_POST['userId'];
    $newStatus = $_POST['newStatus'];

    // Update the user status in the database
    $sqlUpdateStatus = "UPDATE user SET UserStatus = '$newStatus' WHERE UserID = '$userId'";
    $resultUpdateStatus = $conn->query($sqlUpdateStatus);

    if ($resultUpdateStatus === true) {
        echo 'Success';
    } else {
        echo 'Error: ' . $conn->error;
    }

    $conn->close();
}
?>
