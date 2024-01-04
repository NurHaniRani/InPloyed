<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../admin/login.php");
    exit();
}

// Include the database connection file
require_once('dbconnect.php');

// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input to prevent SQL injection
    $forumName = mysqli_real_escape_string($conn, $_POST['forum_name']);
    $forumDescription = mysqli_real_escape_string($conn, $_POST['forum_description']);
    $adminId = $_SESSION['user_id'];

    // Get the current date and time
    $forumDate = date("j F Y");
    $forumTime = date("g:i a");

    // Insert the new forum into the database
    $sqlInsertForum = "INSERT INTO forum (ForumName, ForumDescription, ForumDate, ForumTime, AdminID) VALUES ('$forumName', '$forumDescription', '$forumDate', '$forumTime', '$adminId')";

    if ($conn->query($sqlInsertForum) === TRUE) {
        // Update the user's LastUpdateDate and LastUpdateTime
        $sqlUpdateUser = "UPDATE user SET LastUpdateDate = '$forumDate', LastUpdateTime = '$forumTime' WHERE UserID = '$adminId'";
        $resultUpdateUser = $conn->query($sqlUpdateUser);

        if ($resultUpdateUser) {
            // Redirect to forum.php with a success message
            echo "<script>alert('Forum Added Successfully!'); window.location.href='../admin/forum.php';</script>";
            exit();
        } else {
            // Handle the case when the update fails
            echo "<script>alert('Error updating user information'); window.location.href='../admin/forum.php';</script>";
            exit();
        }
    } else {
        // Redirect to forum.php with an error message
        echo "<script>alert('Sorry, Error Adding Forum, Try Again later'); window.location.href='../admin/forum.php';</script>";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
