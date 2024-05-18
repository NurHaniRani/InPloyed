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

// Fetch all users from the database
$sqlUsers = "SELECT * FROM user";
$resultUsers = $conn->query($sqlUsers);

// Check for SQL errors
if ($resultUsers === false) {
    echo "Error: " . $conn->error;
    exit();
}

// Fetch user information from the result set
$users = [];
while ($row = $resultUsers->fetch_assoc()) {
    $users[] = $row;
}

$sqlCounts = "SELECT UserType, COUNT(*) as count FROM user GROUP BY UserType";
$resultCounts = $conn->query($sqlCounts);

if ($resultCounts === false) {
    echo "Error: " . $conn->error;
    exit();
}

$userCounts = [];
while ($row = $resultCounts->fetch_assoc()) {
    $userCounts[$row['UserType']] = $row['count'];
}

// Fetch total number of users
$sqlTotalUser = "SELECT COUNT(*) AS totalUser FROM user";
$resultTotalUser = $conn->query($sqlTotalUser);
$totalUser = ($resultTotalUser) ? $resultTotalUser->fetch_assoc()['totalUser'] : 0;

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
	<title>Users | CareerConnect</title> 
	<link rel="stylesheet"
		href="../css/user.css"> 
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

				<a href="forumpost.php" style="color: white; text-decoration: none;">
					<div class="nav-option option3"> 
						<h3>Forum Posts</h3> 
					</div> </a>

					<div class="nav-option option4"> 
						<h3 style="color: white;">Users</h3> 
					</div> 

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
				</div> 
			</div> 

			<div class="box-container"> 

				<div class="box box1"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo $totalUser; ?></h2> 
						<h2 class="topic">Total Accounts</h2> 
					</div> 
				</div> 

				<div class="box box2"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($userCounts['Company']) ? $userCounts['Company'] : 0; ?></h2> 
						<h2 class="topic">Company Accounts</h2> 
					</div> 
				</div> 

				<div class="box box3"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($userCounts['Job Seeker']) ? $userCounts['Job Seeker'] : 0; ?></h2> 
						<h2 class="topic">Job Seeker Accounts</h2> 
					</div> 
				</div> 

				<div class="box box4"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($userCounts['Admin']) ? $userCounts['Admin'] : 0; ?></h2> 
						<h2 class="topic">Admin Accounts</h2> 
					</div> 
			</div> 

			<div class="report-container"> 
				<div class="report-header">
    <h1 class="recent-Articles">Users</h1>
    <div class="view-buttons">
        <button class="view" data-user-type="all">All</button>
        <button class="view" data-user-type="company">Company</button>
        <button class="view" data-user-type="job seeker">Job Seekers</button>
        <button class="view" data-user-type="admin">Admin</button>
    </div>
</div>

				<div class="report-body">
    <div class="items">
        <?php foreach ($users as $user): ?>
        <div class="item1" data-user-type="<?php echo strtolower($user['UserType']); ?>">
            <div class="user-info">
                <h3 class="t-op-nextlvl" style="font-weight: bold;">Name: <?php echo $user['UserName']; ?></h3>
                <h3 class="t-op-nextlvl">Email: <?php echo $user['UserEmail']; ?></h3>
                <h3 class="t-op-nextlvl">UserType: <?php echo $user['UserType']; ?></h3>
                <h3 class="t-op-nextlvl">Last Access Date: <?php echo $user['LastAccessDate']; ?></h3>
            </div>
            <?php if ($user['UserStatus'] == 'Active'): ?>
                <button class="t-op-nextlvl label-tag disable-btn" data-user-id="<?php echo $user['UserID'];?>" style="background-color: red;">Disable</button>
            <?php else: ?>
                <button class="t-op-nextlvl label-tag enable-btn" data-user-id="<?php echo $user['UserID']; ?>" style="background-color: green;">Enable</button>
            <?php endif; ?>						
        </div>
        <?php endforeach; ?>
    </div>
</div>

			</div> 
		</div> 
	</div> 

	<script src="./index.js">
	</script> 

	<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.view');
        const items = document.querySelectorAll('.item1');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const userTypeFilter = this.dataset.userType;

                items.forEach(item => {
                    const itemUserType = item.dataset.userType.toLowerCase();

                    if (userTypeFilter === 'all' || userTypeFilter === itemUserType) {
                        item.style.display = 'flex'; // Show the item
                    } else {
                        item.style.display = 'none'; // Hide the item
                    }
                });
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const disableButtons = document.querySelectorAll('.disable-btn');

    disableButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const newStatus = this.textContent === 'Disable' ? 'Inactive' : 'Active';

            // Display a confirmation dialog
            const isConfirmed = window.confirm('Are you sure you want to disable this account?');

            if (isConfirmed) {
                // Make an AJAX request to update the user status
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Update the button text
                            button.textContent = (newStatus === 'Active') ? 'Disable' : 'Enable';

                            // Display a success message
                            alert('User status updated successfully!');
                        } else {
                            // Handle error
                            console.error('Error updating user status:', xhr.responseText);
                        }
                    }
                };
                xhr.open('POST', '../control/update_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`userId=${userId}&newStatus=${newStatus}`);
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const enableButtons = document.querySelectorAll('.enable-btn');

    enableButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.dataset.userId;
            const newStatus = this.textContent === 'Enable' ? 'Active' : 'Inactive';

            // Display a confirmation dialog
            const isConfirmed = window.confirm('Are you sure you want to enable this account?');

            if (isConfirmed) {
                // Make an AJAX request to update the user status
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Update the button text
                            button.textContent = (newStatus === 'Active') ? 'Disable' : 'Enable';

                            // Display a success message
                            alert('User status updated successfully!');
                        } else {
                            // Handle error
                            console.error('Error updating user status:', xhr.responseText);
                        }
                    }
                };
                xhr.open('POST', '../control/update_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`userId=${userId}&newStatus=${newStatus}`);
            }
        });
    });
});
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
