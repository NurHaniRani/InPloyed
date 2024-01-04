<?php
// fetch_forum_posts.php

require_once('../control/dbconnect.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


// Get the forum ID from the AJAX request
$forumId = isset($_GET['forumId']) ? intval($_GET['forumId']) : 0;

// Fetch forum posts from the database based on the forum ID
$sqlPosts = "SELECT * FROM forum_post WHERE ForumID = '$forumId'";
$resultPosts = $conn->query($sqlPosts);


// Check for SQL errors
if ($resultPosts === false) {
    echo "Error: " . $conn->error;
    exit();
}



// Display forum posts
while ($row = $resultPosts->fetch_assoc()) {

    echo "<div class='forum-post' style='border-radius: 8px; border-color: white; border-style: solid; border-width: 1px; background: linear-gradient(#f99777, #623aa2); width: 600px;'>";
    echo "<h3 style='margin-left: 20px; margin-top: 10px;'>" . $row['PostTitle'] . "</h3>";
    echo "<p style='margin-left: 20px;'>" . $row['PostDescription'] . "</p>";
    echo "<p style='margin-left: 20px; margin-bottom: 10px;'>Date: " . $row['PostDate'] . " Time: " . $row['PostTime'] . "</p>";
    echo "</div> <br>";
}


$conn->close();
?>
