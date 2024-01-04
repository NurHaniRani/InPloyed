<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


// Include the database connection file
require_once('dbconnect.php');

// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch values from the form
    $userid = $_SESSION['user_id'];
    $editForumId = $_POST['forum_id'];
    $editForumName = $_POST['edit_forum_name'];
    $editForumDescription = $_POST['edit_forum_description'];
    $currentDate = date("j F Y");
    $currentTime = date("g:i a");

    // Update the forum information in the database
    $sqlUpdateForum = "UPDATE forum SET ForumName = '$editForumName', ForumDescription = '$editForumDescription' WHERE ForumID = '$editForumId'";
    $resultUpdateForum = $conn->query($sqlUpdateForum);

    if ($resultUpdateForum) {
        // Forum update successful

        // Update the user's LastUpdateDate and LastUpdateTime
        $sqlUpdateUser = "UPDATE user SET LastUpdateDate = '$currentDate', LastUpdateTime = '$currentTime' WHERE UserID = '$userid'";
        $resultUpdateUser = $conn->query($sqlUpdateUser);

        if ($resultUpdateUser) {
            // User information update successful
            echo "<script>alert('Forum Updated Successfully!'); window.location.href='../admin/forum.php';</script>";
            exit();
        } else {
            // Handle the case when the user information update fails
            echo "<script>alert('Failed to Update User Information!'); window.location.href='../admin/forum.php';</script>";
            exit();
        }
    } else {
        // Forum update failed
        echo "<script>alert('Failed to Update Forum!'); window.location.href='../admin/forum.php';</script>";
        exit();
    }
} else {
    // Redirect to the forum page if someone tries to access this file directly without a POST request
    header("Location: ../admin/forum.php");
    exit();
}

$conn->close();
?>
