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

// Fetch data from the database with date conversion
$sqlJobAds = "SELECT DATE_FORMAT(STR_TO_DATE(AdsDate, '%d %M %Y'), '%Y-%m') AS month, COUNT(*) AS adCount FROM job GROUP BY month";
$resultJobAds = $conn->query($sqlJobAds);

$jobAdData = [];
while ($row = $resultJobAds->fetch_assoc()) {
    $jobAdData[$row['month']] = $row['adCount'];
}

// Fetch data for accepted applicants this month
$sqlAcceptedApplicants = "SELECT 
                                DATE_FORMAT(STR_TO_DATE(ApplyStartDate, '%d %M %Y'), '%Y-%m') AS month,
                                COUNT(*) AS acceptedCount
                            FROM job_apply 
                            WHERE ApplyStatus = 'Approved'
                            GROUP BY month";

$resultAcceptedApplicants = $conn->query($sqlAcceptedApplicants);

$acceptedApplicantsData = [];
while ($row = $resultAcceptedApplicants->fetch_assoc()) {
    $acceptedApplicantsData[$row['month']] = $row['acceptedCount'];
}

// Fetch data for rejected applicants this month
$sqlRejectedApplicants = "SELECT 
                                DATE_FORMAT(STR_TO_DATE(ApplyStartDate, '%d %M %Y'), '%Y-%m') AS month,
                                COUNT(*) AS rejectedCount
                            FROM job_apply 
                            WHERE ApplyStatus = 'Failed'
                            GROUP BY month";

$resultRejectedApplicants = $conn->query($sqlRejectedApplicants);

$rejectedApplicantsData = [];
while ($row = $resultRejectedApplicants->fetch_assoc()) {
    $rejectedApplicantsData[$row['month']] = $row['rejectedCount'];
}

// Fetch data for job ads created this month
$sqlJobAds = "SELECT 
                    DATE_FORMAT(STR_TO_DATE(AdsDate, '%d %M %Y'), '%Y-%m') AS month,
                    COUNT(*) AS adCount
                FROM job 
                GROUP BY month";

$resultJobAds = $conn->query($sqlJobAds);

$jobAdData = [];
while ($row = $resultJobAds->fetch_assoc()) {
    $jobAdData[$row['month']] = $row['adCount'];
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
	<link rel="icon" href="../src/logoSnipped.png">
	<meta charset="UTF-8"> 
	<meta http-equiv="X-UA-Compatible"
		content="IE=edge"> 
	<meta name="viewport"
		content="width=device-width, 
				initial-scale=1.0"> 
	<title>Dashboard | CareerConnect</title> 
	<link rel="stylesheet"
		href="../css/dashboard.css"> 
	<link rel="stylesheet"
		href="responsive.css"> 

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

					<div class="nav-option option1"> 
						<h3 style="color: white;">Dashboard</h3> 
					</div> 

					<a href="forum.php" style="color: white; text-decoration: none;">
					<div class="option2 nav-option"> 
						<h3>Forum</h3> 
					</div> 
				</a>

				<a href="forumpost.php" style="color: white; text-decoration: none;">
					<div class="nav-option option3"> 
						<h3>Forum Posts</h3> 
					</div> </a>

					<a href="user.php" style="color: white; text-decoration: none;">
					<div class="nav-option option4"> 
						 
						<h3>Users</h3> 
					</div> </a>

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

				<div class="box box1"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo $totalUser; ?></h2> 
						<h2 class="topic">Total Accounts</h2> 
					</div> 
				</div> 

				<div class="box box2"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($acceptedApplicantsData[date('Y-m')]) ? $acceptedApplicantsData[date('Y-m')] : 0; ?></h2> 
						<h2 class="topic">Applicants Accepted This Month</h2> 
					</div> 
				</div> 

				<div class="box box3"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($rejectedApplicantsData[date('Y-m')]) ? $rejectedApplicantsData[date('Y-m')] : 0; ?></h2>
						<h2 class="topic">Applicants Rejected This Month</h2> 
					</div> 
				</div> 

				<div class="box box4"> 
					<div class="text"> 
						<h2 class="topic-heading"><?php echo isset($jobAdData[date('Y-m')]) ? $jobAdData[date('Y-m')] : 0; ?></h2> 
						<h2 class="topic">Job Advertisement This Month</h2> 
					</div> 
			</div> 

			<div class="report-container"> 
				<div class="report-header"> 
					<h1 class="recent-Articles">Chart</h1> 
					<button class="view" data-chart-type="applicants">Change</button>
				</div> 

				<div class="report-body"> 
					<div class="report-topic-heading"> 

					</div> 

					<div class="items"> 
						<canvas id="lineChart" width="400" height="200"></canvas>
					</div> 

					<div class="items">
						<canvas id="doubleLineChart" width="400" height="200"></canvas>
					</div>
				</div> 
			</div> 
		</div> 
	</div> 

	<script src="./index.js">
	</script> 

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var ctxLine = document.getElementById('lineChart').getContext('2d');
			var ctxDouble = document.getElementById('doubleLineChart').getContext('2d');

			var lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($jobAdData)); ?>,
        datasets: [{
            label: 'Number of Job Ads Created',
            data: <?php echo json_encode(array_values($jobAdData)); ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            fill: false,
        }]
    },
    options: {
        scales: {
            x: {
                type: 'category',
                labels: <?php echo json_encode(array_keys($jobAdData)); ?>,
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Number of Job Ads'
                },
                ticks: {
                    min: 0,
                    stepSize: 5
                }
            }
        }
    }
});

var doubleLineChart = new Chart(ctxDouble, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($acceptedApplicantsData)); ?>,
        datasets: [
            {
                label: 'Accepted Applications',
                data: <?php echo json_encode(array_values($acceptedApplicantsData)); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Rejected Applications',
                data: <?php echo json_encode(array_values($rejectedApplicantsData)); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        scales: {
            x: {
                type: 'category',
                labels: <?php echo json_encode(array_keys($acceptedApplicantsData)); ?>,
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Number of Applications'
                },
                ticks: {
                    min: 0,
                    stepSize: 5
                }
            }
        }
    }
});


    // Initially hide the double line chart
    document.getElementById('doubleLineChart').style.display = 'none';

    // Add a click event listener to the 'Applicants' button
    document.querySelector('.view[data-chart-type="applicants"]').addEventListener('click', function () {
        // Toggle visibility of the charts
        var lineChartDisplay = window.getComputedStyle(document.getElementById('lineChart')).display;
        var doubleLineChartDisplay = window.getComputedStyle(document.getElementById('doubleLineChart')).display;

        if (lineChartDisplay === 'block') {
            document.getElementById('lineChart').style.display = 'none';
            document.getElementById('doubleLineChart').style.display = 'block';
        } else {
            document.getElementById('lineChart').style.display = 'block';
            document.getElementById('doubleLineChart').style.display = 'none';
        }
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
