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

$sql = "SELECT * FROM user WHERE UserType = 'Admin' AND UserID != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Fetch the admins as an associative array
$admins = $result->fetch_all(MYSQLI_ASSOC);

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
	<link rel="icon" href="../src/logoSnipped.png">
	<meta charset="UTF-8"> 
	<meta http-equiv="X-UA-Compatible"
		content="IE=edge"> 
	<meta name="viewport"
		content="width=device-width, 
				initial-scale=1.0"> 
	<title>Admin | CareerConnect</title> 
	<link rel="stylesheet"
		href="../css/admin.css"> 
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
					<div class="nav-option option2">  
						<h3>Forum</h3> 
					</div></a>

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

					<div class="nav-option option6"> 
						<h3 style="color: white;">Admin</h3> 
					</div>

					<a href="#" onclick="confirmLogout()" style="color: white; text-decoration: none;">
						<div class="nav-option logout"> 
							<h3>Logout</h3> 
						</div>
					</a>

				</div> 
			</nav> 
		</div> 
		<div class="main"> 

 <div class="report-container"> 
    <div class="report-header"> 
        <h1 class="recent-Articles">Admin List</h1> 
    </div> 

    <div class="report-body"> 
        <div class="user-info-container">
            <div class="user-info">

                <div class="items">
                    <?php foreach ($admins as $admin): ?>
                        <div class="admin-item">
                            <h3 class="admin-name"><?php echo $admin['UserName']; ?></h3>
                            <p class="admin-email"><?php echo $admin['UserEmail']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div> 
</div> 


			<div class="report-container edit-profile-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Add Admin</h1> 
				</div> 

				<div class="report-body">
					<form action="../control/add_admin.php" method="post">
						<input type="hidden" name="adminID" value="1">
						<input type="hidden" name="userStatus" value="Active">
						<input type="hidden" name="userPosition" value="Admin">

						<div class="form-group">
							<label for="adminName">Name:</label>
							<input type="text" id="adminName" name="adminName" required>
						</div>

						<div class="form-group">
							<label for="adminEmail">Email:</label>
							<input type="email" id="adminEmail" name="adminEmail" required>
						</div>

						<div class="form-group">
							<label for="adminPassword">Password:</label>
							<input type="password" id="adminPassword" name="adminPassword" required>
						</div>

						<div class="form-group">
							<label for="confirmPassword">Re-enter Password:</label>
							<input type="password" id="confirmPassword" name="confirmPassword" required>
						</div>

						<div class="form-group">
							<button class="t-op-nextlvl label-tag" style="background-color: green; color: white;" type="submit">Add Admin</button>
						</div>
					</form>
				</div>
			</div>
		</div> 
	</div> 
</div> 

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
