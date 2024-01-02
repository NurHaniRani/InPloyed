<?php
// fetch_forum_posts.php

require_once('../control/dbconnect.php');

if (isset($_GET['forumId'])) {
    $forumId = $_GET['forumId'];

    // Fetch the count of posts posted today for the chosen forum
    $today = date('d F Y'); // Format matches the varchar format in the database
    $sqlTodayPostCount = "SELECT COUNT(*) AS postCount FROM forum_post WHERE ForumID = '$forumId' AND PostDate LIKE '%$today%'";
    $resultTodayPostCount = $conn->query($sqlTodayPostCount);

    // Check for SQL errors
    if ($resultTodayPostCount === false) {
        echo "Error: " . $conn->error;
        exit();
    }

    $todayPostCount = ($resultTodayPostCount) ? $resultTodayPostCount->fetch_assoc()['postCount'] : 0;

    echo $todayPostCount;

    $conn->close();
}
?>
