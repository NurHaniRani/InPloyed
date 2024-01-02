<?php
// fetch_forum_posts_count.php

require_once('../control/dbconnect.php');

if (isset($_GET['forumId'])) {
    $forumId = $_GET['forumId'];

    // Fetch the number of posts for the given forum
    $sqlCountPosts = "SELECT COUNT(*) AS postCount FROM forum_post WHERE ForumID = '$forumId'";
    $resultCountPosts = $conn->query($sqlCountPosts);

    // Check for SQL errors
    if ($resultCountPosts === false) {
        echo "Error: " . $conn->error;
        exit();
    }

    $postCount = ($resultCountPosts) ? $resultCountPosts->fetch_assoc()['postCount'] : 0;

    echo $postCount;

    $conn->close();
}
?>
