<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user's ID
$userId = $_SESSION['user_id'];

// Include the database connection file
require_once('../control/dbconnect.php');

// Fetch the information of the currently logged-in user
$userid = $_SESSION['user_id'];
$sqlUser = "SELECT * FROM user WHERE UserID = '$userid'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows == 1) {
    $userInfo = $resultUser->fetch_assoc();
} else {
    // Redirect or handle the case when user information is not found
    header("Location: login.php");
    exit();
}

// Fetch all forums from the database
$sqlForums = "SELECT * FROM forum WHERE AdminID = '$userid'";
$resultForums = $conn->query($sqlForums);

// Check for SQL errors
if ($resultForums === false) {
    echo "Error: " . $conn->error;
    exit();
}

// Fetch forum information from the result set
$forums = [];
while ($row = $resultForums->fetch_assoc()) {
    $forums[] = $row;
}

// Fetch total number of forums
$sqlTotalForums = "SELECT COUNT(*) AS totalForums FROM forum";
$resultTotalForums = $conn->query($sqlTotalForums);
$totalForums = ($resultTotalForums) ? $resultTotalForums->fetch_assoc()['totalForums'] : 0;

// Fetch number of forums created by the current adminid
$sqlForumsCreatedByAdmin = "SELECT COUNT(*) AS forumsCreatedByAdmin FROM forum WHERE AdminID = '$userId'";
$resultForumsCreatedByAdmin = $conn->query($sqlForumsCreatedByAdmin);
$forumsCreatedByAdmin = ($resultForumsCreatedByAdmin) ? $resultForumsCreatedByAdmin->fetch_assoc()['forumsCreatedByAdmin'] : 0;

// Fetch the user image data
$sqlUserImage = "SELECT * FROM user_image WHERE UserID = '$userid'";
$resultUserImage = $conn->query($sqlUserImage);

if ($resultUserImage->num_rows == 1) {
    $userImageData = $resultUserImage->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <link rel="icon" href="../src/logoSnipped.png">
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge"> 
    <meta name="viewport"
        content="width=device-width, 
                initial-scale=1.0"> 
    <title>Forum Posts | CareerConnect</title> 
    <link rel="stylesheet"
        href="../css/forumpost2.css"> 
    <link rel="stylesheet"
        href="responsive.css"> 
</head> 

<body> 
    
    <header> 

        <a href="dashboard.php">
            <div class="logosec"> 
                <div class="logo"><img src="../src/logo2Final.png"></div> 
            </div> 
        </a>

        <div class="message"> 
            <a href="#" onclick="confirmLogout()" style="color: black; text-decoration: none; margin-left: 10px;">Log Out</a>
            <div class="dp"> 
            <?php if (!empty($userImageData['img_data'])) : ?>
                    <!-- Convert BLOB data to base64 and embed it directly in the src attribute -->
                    <img src="data:image/<?php echo $userImageData['img_type']; ?>;base64,<?php echo base64_encode($userImageData['img_data']); ?>" class="dpicn" alt="dp" style="height: 40px;width: 40px;border-radius: 50%;">
                <?php else : ?>
                    <img src="../src/person-4.png" class="dpicn" alt="dp" style="height: 40px;width: 40px;border-radius: 50%;">
                <?php endif; ?>
            
            </div> 
        </div> 

    </header> 

    <div class="main-container"> 
        <div class="navcontainer"> 
            <nav class="nav"> 
                <div class="nav-upper-options"> 

                    <a href="dashboard.php" style="color: white; text-decoration: none;">
                    <div class="nav-option option1"> 
                        <h3> Dashboard</h3> 
                    </div> </a>

                    <a href="forum.php" style="color: white; text-decoration: none;">
                    <div class="option2 nav-option"> 
                        <h3>Forum</h3> 
                    </div> 
                </a>

                    <div class="nav-option option3"> 
                        <h3 style="color: white;">Forum Posts</h3> 
                    </div>

                    <a href="user.php" style="color: white; text-decoration: none;">
                    <div class="nav-option option4"> 
                        <h3>Users</h3> 
                    </div> 
                </a>

                <a href="profile.php" style="color: white; text-decoration: none;">
                    <div class="nav-option option5"> 
                        <h3> Profile</h3> 
                    </div> </a>

                    <a href="admin.php" style="color: white; text-decoration: none;">
                    <div class="nav-option option6"> 
                        <h3>Admin</h3> 
                    </div> </a>

                    <a href="#" onclick="confirmLogout()" style="color: white; text-decoration: none;">
                        <div class="nav-option logout"> 
                            <h3>Logout</h3> 
                        </div>
                    </a>

                </div> 
            </nav> 
        </div> 
        <div class="main"> 

            <div class="box-container"> 

            <div class="report-container"> 
                <div class="report-header"> 
                    <h1 class="recent-Articles">List of Forums</h1> 
                </div> 

                <div class="report-body"> 
                    <div class="report-topic-heading"> 
                        <h3 class="t-op">Name</h3> 
                        <h3 class="t-op">Action</h3>
                    </div> 

                    <div class="items"> 
                        <?php foreach ($forums as $forum): ?>
                        <div class="item1"> 
                            <h3 class="t-op-nextlvl"><?php echo $forum['ForumName']; ?></h3> 
                            <a href="#ViewPost">
                                <button class="t-op-nextlvl label-tag" onclick="viewForumPosts(<?php echo $forum['ForumID']; ?>)">View Posts</button>  
                                </a>       
                        </div> 
                        <?php endforeach; ?>

                    </div> 
                </div> 
            </div> 

<div class="box box1"> 
                    <div class="text"> 
                        <h2 class="topic-heading">0</h2> 
                        <h2 class="topic">Number of Posts</h2> 
                    </div> 
                </div>

<div class="box box2"> 
                    <div class="text"> 
                        <h2 class="topic-heading">None</h2> 
                        <h2 class="topic">Most Used Keyword</h2> 
                    </div> 
                </div> 

                <div class="box box3"> 
                    <div class="text"> 
                        <h2 class="topic-heading">0</h2> 
                        <h2 class="topic">Posts Today</h2> 
                    </div> 
                </div>

<div class="report-container"> 
    <div class="report-header"> 
        <h1 class="recent-Articles" id="ViewPost">View Posts</h1> 

    <div class="searchbar2">
    <input type="text" id="searchInput" placeholder="Search">
    <button type="button" onclick="searchForumPosts()">Search</button>
</div>

    </div> 

    <div class="report-body"> 

        <div class="items" id="viewPostsContainer"> 

        </div> 
    </div> 
</div>

<script>
    function searchForumPosts() {
        var forumId = /* Retrieve the forum ID as needed */;
        var searchTerm = document.getElementById('searchInput').value;

        // Make an AJAX request to the search_forum_posts.php file
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Display the fetched posts in the "View Posts" container
                    document.getElementById('viewPostsContainer').innerHTML = xhr.responseText;
                } else {
                    console.error('Error fetching forum posts');
                }
            }
        };

        // Specify the PHP file to handle the search request
        xhr.open('GET', '../control/search_forum_posts.php?forumId=' + forumId + '&search=' + searchTerm, true);
        xhr.send();
    }
</script>


<script>

    function viewForumPosts(forumId) {

        // Get the search term from the input field
    var searchTerm = document.getElementById('searchInput').value;

    // Make an AJAX request to fetch forum posts with the search term
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Display the fetched posts in the "View Posts" container
                document.getElementById('viewPostsContainer').innerHTML = xhr.responseText;
            } else {
                console.error('Error fetching forum posts');
            }
        }
    };

    // Specify the PHP file to handle the request, including the search term
    xhr.open('GET', 'fetch_forum_posts.php?forumId=' + forumId + '&search=' + searchTerm, true);
    xhr.send();

        // Make an AJAX request to fetch the number of posts
    var xhrCount = new XMLHttpRequest();
    xhrCount.onreadystatechange = function() {
        if (xhrCount.readyState === XMLHttpRequest.DONE) {
            if (xhrCount.status === 200) {
                // Display the fetched number of posts in the "Number of Posts" box
                document.querySelector('.box1 .topic-heading').textContent = xhrCount.responseText;
            } else {
                console.error('Error fetching number of posts');
            }
        }
    };

    // Specify the PHP file to handle the request
    xhrCount.open('GET', '../control/fetch_forum_posts_count.php?forumId=' + forumId, true);
    xhrCount.send();

    // Make an AJAX request to fetch the most used keyword
    var xhrKeyword = new XMLHttpRequest();
    xhrKeyword.onreadystatechange = function() {
        if (xhrKeyword.readyState === XMLHttpRequest.DONE) {
            if (xhrKeyword.status === 200) {
                // Display the fetched most used keyword in the "Most Used Keyword" box
                document.querySelector('.box2 .topic-heading').textContent = xhrKeyword.responseText;
            } else {
                console.error('Error fetching most used keyword');
            }
        }
    };

    // Specify the PHP file to handle the request
    xhrKeyword.open('GET', '../control/fetch_most_used_keyword.php?forumId=' + forumId, true);
    xhrKeyword.send();

    // Make an AJAX request to fetch today's post count
    var xhrTodayPostCount = new XMLHttpRequest();
    xhrTodayPostCount.onreadystatechange = function() {
        if (xhrTodayPostCount.readyState === XMLHttpRequest.DONE) {
            if (xhrTodayPostCount.status === 200) {
                // Display the fetched post count in the "Today's Post Count" box
                document.querySelector('.box3 .topic-heading').textContent = xhrTodayPostCount.responseText;
            } else {
                console.error('Error fetching today\'s post count');
            }
        }
    };

    // Specify the PHP file to handle the request
    xhrTodayPostCount.open('GET', '../control/fetch_forum_posts_today.php?forumId=' + forumId, true);
    xhrTodayPostCount.send();


        // Make an AJAX request to fetch forum posts
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Display the fetched posts in the "View Posts" container
                    document.getElementById('viewPostsContainer').innerHTML = xhr.responseText;
                } else {
                    console.error('Error fetching forum posts');
                }
            }
        };

        // Specify the PHP file to handle the request
        xhr.open('GET', 'fetch_forum_posts.php?forumId=' + forumId, true);
        xhr.send();
    }

</script>


    <script src="./index.js">
    </script> 

    <script>
    function confirmLogout() {
        // Display a confirmation dialog
        var confirmLogout = confirm("Are you sure you want to logout?");

        // If the user clicks OK, redirect to the logout page
        if (confirmLogout) {
            window.location.href = "logout.php";
        }
    }
</script>

</body> 
</html>
