<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');

$userid = $_SESSION['user_id'];
$newName = mysqli_real_escape_string($conn, $_POST['newName']);

$sqlUpdateName = "UPDATE user SET UserName = '$newName' WHERE UserID = '$userid'";
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
