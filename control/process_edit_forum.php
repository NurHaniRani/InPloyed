<?php

// Include the database connection file
require_once('dbconnect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch values from the form
    $editForumId = $_POST['forum_id'];
    $editForumName = $_POST['edit_forum_name'];
    $editForumDescription = $_POST['edit_forum_description'];

    // Update the forum information in the database
    $sqlUpdateForum = "UPDATE forum SET ForumName = '$editForumName', ForumDescription = '$editForumDescription' WHERE ForumID = '$editForumId'";
    $resultUpdateForum = $conn->query($sqlUpdateForum);

    if ($resultUpdateForum) {
        // Forum update successful
        echo "<script>alert('Forum Updated Successfully!'); window.location.href='../admin/forum.php';</script>";
        exit();
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
