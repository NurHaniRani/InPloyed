<?php
// fetch_most_used_keyword.php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


require_once('../control/dbconnect.php');

if (isset($_GET['forumId'])) {
    $forumId = $_GET['forumId'];

    // Fetch the most used keyword in the forum posts
    $sqlMostUsedKeyword = "SELECT PostDescription FROM forum_post WHERE ForumID = '$forumId'";
    $resultMostUsedKeyword = $conn->query($sqlMostUsedKeyword);

    // Check for SQL errors
    if ($resultMostUsedKeyword === false) {
        echo "Error: " . $conn->error;
        exit();
    }

    $keywords = [];
    while ($row = $resultMostUsedKeyword->fetch_assoc()) {
        $postDescription = $row['PostDescription'];
        // Extract words from the post description
        $words = explode(' ', $postDescription);
        // Add words to the keywords array
        $keywords = array_merge($keywords, $words);
    }

    // Count occurrences of each word
    $keywordCounts = array_count_values($keywords);

    // Find the most used keyword
    $mostUsedKeyword = '';
    $maxCount = 0;
    foreach ($keywordCounts as $keyword => $count) {
        if ($count > $maxCount) {
            $mostUsedKeyword = $keyword;
            $maxCount = $count;
        }
    }

    echo $mostUsedKeyword;

    $conn->close();
}
?>
