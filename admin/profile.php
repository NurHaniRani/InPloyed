<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch the user image data
$sqlUserImage = "SELECT * FROM user_image WHERE UserID = '$userid'";
$resultUserImage = $conn->query($sqlUserImage);

if ($resultUserImage->num_rows == 1) {
    $userImageData = $resultUserImage->fetch_assoc();
}

?>


<!DOCTYPE html> 
<html lang="en"> 

<head> 
	<link rel="icon" href="../src/inployed.png">
	<meta charset="UTF-8"> 
	<meta http-equiv="X-UA-Compatible"
		content="IE=edge"> 
	<meta name="viewport"
		content="width=device-width, 
				initial-scale=1.0"> 
	<title>InPloyed | Profile</title> 
	<link rel="stylesheet"
		href="../css/profile.css"> 
	<link rel="stylesheet"
		href="responsive.css"> 

</head> 

<body> 
	
	<header> 

		<a href="dashboard.php">
			<div class="logosec"> 
				<div class="logo"><img src="../src/logo.png" style="margin-left: -5px;"></div> 
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

					<a href="dashboard.php" style="color: black; text-decoration: none;">
					<div class="nav-option option1"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210182148/Untitled-design-(29).png"
							class="nav-img"
							alt="dashboard"> 
						<h3> Dashboard</h3> 
					</div></a>

					<a href="forum.php" style="color: black; text-decoration: none;">
					<div class="option2 nav-option"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/9.png"
							class="nav-img"
							alt="articles"> 
						<h3>Forum</h3> 
					</div> </a>

					<a href="forumpost.php" style="color: black; text-decoration: none;">
					<div class="nav-option option3"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/5.png"
							class="nav-img"
							alt="report"> 
						<h3>Forum Posts</h3> 
					</div></a>

					<a href="user.php" style="color: black; text-decoration: none;">
					<div class="nav-option option4"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/6.png"
							class="nav-img"
							alt="institution"> 
						<h3>Users</h3> 
					</div></a>

					<div class="nav-option option5"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210183323/10.png"
							class="nav-img"
							alt="blog"> 
						<h3> Profile</h3> 
					</div>

					<a href="admin.php" style="color: black; text-decoration: none;">
					<div class="nav-option option6"> 
						<img src= 
"https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/4.png"
							class="nav-img"
							alt="settings"> 
						<h3>Admin</h3> 
					</div> </a>

					<a href="#" onclick="confirmLogout()" style="color: black; text-decoration: none;">
						<div class="nav-option logout"> 
							<img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/7.png" class="nav-img" alt="logout"> 
							<h3>Logout</h3> 
						</div>
					</a>

				</div> 
			</nav> 
		</div> 
		<div class="main">  

			<div class="report-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Admin Profile</h1> 
				</div> 

				<div class="report-body"> 

            <div class="user-info-container">

            	<?php if (!empty($userImageData['img_data'])) : ?>
                    <!-- Convert BLOB data to base64 and embed it directly in the src attribute -->
                    <img src="data:image/<?php echo $userImageData['img_type']; ?>;base64,<?php echo base64_encode($userImageData['img_data']); ?>" alt="User Image" class="user-image" id="userImage" >
                <?php else : ?>
                    <img src="../src/person-4.png" alt="User Image" class="user-image" id="userImage">
                <?php endif; ?>

                <!-- Display user information on the right side -->
                <div class="user-info">
                    <h3 class="t-op" style="font-size: 28px;"><?php echo $userInfo['UserName']; ?></h3>
                    <h3 class="t-op">Administrator</h3>
                    <h3 class="t-op" style="color: blue;"><?php echo $userInfo['UserEmail']; ?></h3>
                </div>
            </div>
					</div> 
				</div>  

			<div class="report-container edit-profile-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Edit Profile</h1> 
					<button class="view" id="nameBtn">Name</button>
					<button class="view" id="passwordBtn">Password</button>
					<button class="view" id="imageBtn">Image</button>
				</div> 

				<div class="report-body">
					<div id="editNameForm" style="display: none;">
        <!-- Form for editing name -->
        <form method="post" action="../control/edit_name.php">
            <label for="newName">New Name:</label>
            <input type="text" id="newName" name="newName" required>
            <button class="btn" type="submit">Save Name</button>
        </form>
    </div>

    <div id="editPasswordForm" style="display: none;">
        <!-- Form for editing password -->
        <form method="post" action="../control/edit_password.php">
        	<label for="oldPassword">Old Password:</label>
        	<input type="password" id="oldPassword" name="oldPassword" required> <br>
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required><br>
            <button class="btn" type="submit">Save Password</button>
        </form>
    </div>

    <div id="editImageForm" style="display: none;">
        <!-- Form for updating user image -->
        <form method="post" action="../control/edit_image.php" enctype="multipart/form-data">
            <label for="newImage">Select new image:</label>
            <input type="file" id="newImage" name="newImage" accept="image/*" required>
            <button class="btn" type="submit">Upload Image</button>
        </form>
    </div>
					
				</div>

				</div> 
			</div> 
		</div> 
	</div> 

	<script src="./index.js"></script>
<script>
    // Add event listener for the image click
    document.getElementById('userImage').addEventListener('click', function () {
        // Toggle the visibility of the name, password, and image forms
        toggleFormVisibility('editImageForm');
    });

    // Add event listeners for the name and password buttons
    document.getElementById('nameBtn').addEventListener('click', function () {
        // Hide the password and image forms and show the name form
        toggleFormVisibility('editNameForm');
    });

    document.getElementById('passwordBtn').addEventListener('click', function () {
        // Hide the name and image forms and show the password form
        toggleFormVisibility('editPasswordForm');
    });

    // Function to toggle the visibility of the forms
    function toggleFormVisibility(formId) {
        // Hide all forms by default
        document.getElementById('editNameForm').style.display = 'none';
        document.getElementById('editPasswordForm').style.display = 'none';
        document.getElementById('editImageForm').style.display = 'none';

        // If a formId is provided, show that form
        if (formId) {
            document.getElementById(formId).style.display = 'block';
        }
    }
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
    // Add event listener for the image button click
    document.getElementById('imageBtn').addEventListener('click', function () {
        // Toggle the visibility of the edit image form
        toggleFormVisibility('editImageForm');
    });

    // Function to toggle the visibility of the forms
    function toggleFormVisibility(formId) {
        // Hide all forms by default
        document.getElementById('editNameForm').style.display = 'none';
        document.getElementById('editPasswordForm').style.display = 'none';
        document.getElementById('editImageForm').style.display = 'none';

        // If a formId is provided, show that form
        if (formId) {
            document.getElementById(formId).style.display = 'block';
        }
    }
</script>

</body> 
</html>
