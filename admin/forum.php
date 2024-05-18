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
$sqlUser = "SELECT * FROM user WHERE UserID = '$userId'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows == 1) {
    $userInfo = $resultUser->fetch_assoc();
} else {
    // Redirect or handle the case when user information is not found
    header("Location: login.php");
    exit();
}

// Fetch all forums from the database
$sqlForums = "SELECT * FROM forum WHERE AdminID = '$userId'";
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
	<title>Forum | CareerConnect</title> 
	<link rel="stylesheet"
		href="../css/forum2.css"> 
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

					<div class="option2 nav-option"> 
						<h3 style="color: white;">Forum</h3> 
					</div> 

				<a href="forumpost.php" style="color: white; text-decoration: none;">
					<div class="nav-option option3"> 
						<h3>Forum Posts</h3> 
					</div> </a>

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

			<div class="searchbar2"> 
				<input type="text"
					name=""
					id=""
					placeholder="Search"> 
				<div class="searchbtn"> 
				<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210180758/Untitled-design-(28).png"
						class="icn srchicn"
						alt="search-button"> 
				</div> 
			</div> 

			<div class="box-container"> 

				<div class="box box1"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo $totalForums; ?></h2> 
						<h2 class="topic">Total Forums</h2> 
					</div> 
				</div> 

				<div class="box box2"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo $forumsCreatedByAdmin; ?></h2> 
						<h2 class="topic">Forum Created</h2> 
					</div> 
				</div> 

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
								<button class="t-op-nextlvl label-tag" onclick="openEditPopup('<?php echo $forum['ForumID']; ?>', '<?php echo $forum['ForumName']; ?>', '<?php echo $forum['ForumDescription']; ?>')">Edit</button>					
						</div> 
						<?php endforeach; ?>

					</div> 
				</div> 
			</div> 

<div class="report-container" id="editPopup"> 
    <div class="report-header"> 
        <h1 class="recent-Articles">Edit Forum</h1> 
    </div> 

			<div class="edit-popup">
    <form action="../control/process_edit_forum.php" method="post" class="edit-forum-form" onsubmit="return confirm('Are you sure you want to edit this forum?');">
        <div class="item1"> 
            <label for="edit_forum_name">Forum Name:</label>
            <input type="text" name="edit_forum_name" id="edit_forum_name" placeholder="Enter Forum Name" required>
        </div>
        <div class="item1"> 
            <label for="edit_forum_description">Forum Description:</label>
            <textarea name="edit_forum_description" id="edit_forum_description" placeholder="Enter Forum Description" rows="4" required></textarea>
        </div>
        <input type="hidden" name="forum_id" id="edit_forum_id">
        <div class="item1"> 
            <button type="submit" class="t-op-nextlvl label-tag" >Edit Forum</button>
        </div> 
    </form>
</div>
</div>

<div class="report-container"> 
    <div class="report-header"> 
        <h1 class="recent-Articles">Add Forum</h1> 
    </div> 

    <div class="report-body"> 

        <div class="items"> 
            <!-- Existing forum items display -->

            <!-- Form for adding a new forum -->
            <form action="../control/process_add_forum.php" method="post" class="add-forum-form" onsubmit="return confirm('Are you sure you want to add this forum?');">
                <div class="item1"> 
                    <label for="forum_name">Forum Name:</label>
                    <input type="text" name="forum_name" id="forum_name" placeholder="Enter Forum Name" required>
                </div>
                <div class="item1"> 
                    <label for="forum_description">Forum Description:</label>
                    <textarea name="forum_description" id="forum_description" placeholder="Enter Forum Description" rows="4" required></textarea>
                </div>
                 <div class="item1"> 
                        <button type="submit" class="t-op-nextlvl label-tag">Add Forum</button>
                    </div> 
            </form>
        </div> 
    </div> 
</div>

<script>
    function openEditPopup(forumId, forumName, forumDescription) {
        // Set values for the edit form fields
        document.getElementById('edit_forum_id').value = forumId;
        document.getElementById('edit_forum_name').value = forumName;
        document.getElementById('edit_forum_description').value = forumDescription;

        // Show the edit pop-up
        document.getElementById('editPopup').style.display = 'block';
    }

    function closeEditPopup() {
        // Hide the edit pop-up
        document.getElementById('editPopup').style.display = 'none';
    }
</script>

<?php

// Display success or error message
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = $_GET['message'];

    if ($status === 'success') {
        echo '<div class="success-message">' . $message . '</div>';
    } elseif ($status === 'error') {
        echo '<div class="error-message">' . $message . '</div>';
    }
}
?>

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

<script>
    function openEditPopup(forumId, forumName, forumDescription) {
        // Set values for the edit form fields
        document.getElementById('edit_forum_id').value = forumId;
        document.getElementById('edit_forum_name').value = forumName;
        document.getElementById('edit_forum_description').value = forumDescription;

        // Show the edit pop-up
        document.getElementById('editPopup').style.display = 'block';

        // Scroll to the editPopup element
        document.getElementById('editPopup').scrollIntoView({ behavior: 'smooth' });
    }

    function closeEditPopup() {
        // Hide the edit pop-up
        document.getElementById('editPopup').style.display = 'none';
    }
</script>


</body> 
</html>
