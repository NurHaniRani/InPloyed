<?php
// search_forum_posts.php

// Include the database connection file
require_once('../control/dbconnect.php');

// Retrieve the forum ID and search term from the GET parameters
$forumId = isset($_GET['forumId']) ? $_GET['forumId'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Make sure to validate and sanitize your input data

// Your SQL query to fetch forum posts based on the search term
$sqlSearch = "SELECT * FROM forum_post WHERE ForumID = '$forumId' AND (PostTitle LIKE '%$searchTerm%' OR PostDescription LIKE '%$searchTerm%')";
$resultSearch = $conn->query($sqlSearch);

if ($resultSearch === false) {
    echo "Error: " . $conn->error;
    exit();
}

// Fetch forum posts and display them (modify as needed)
while ($row = $resultSearch->fetch_assoc()) {
    // Output forum post details (customize based on your needs)
    echo '<div class="forum-post">';
    echo '<h3>' . $row['PostTitle'] . '</h3>';
    echo '<p>' . $row['PostDescription'] . '</p>';
    echo '</div>';
}

// Close the database connection
$conn->close();
?>
