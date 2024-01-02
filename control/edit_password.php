<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');

$userid = $_SESSION['user_id'];
$oldPassword = mysqli_real_escape_string($conn, $_POST['oldPassword']);
$newPassword = md5(mysqli_real_escape_string($conn, $_POST['newPassword']));

// Verify old password
$sqlVerifyPassword = "SELECT UserPassword FROM user WHERE UserID = '$userid'";
$resultVerifyPassword = $conn->query($sqlVerifyPassword);

if ($resultVerifyPassword->num_rows == 1) {
    $row = $resultVerifyPassword->fetch_assoc();
    if ($row['UserPassword'] === md5($oldPassword)) {
        // Old password is correct, proceed to update the password
        $sqlUpdatePassword = "UPDATE user SET UserPassword = '$newPassword' WHERE UserID = '$userid'";
        $resultUpdatePassword = $conn->query($sqlUpdatePassword);

        if ($resultUpdatePassword) {
            // Password updated successfully
            echo "<script>alert('Password Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
            exit();
        } else {
            // Handle the case when the update fails
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Old password is incorrect
        echo "<script>alert('Old Password is Incorrect!'); window.location.href='../admin/profile.php';</script>";
    }
} else {
    // Handle the case when user information is not found
    echo "User information not found.";
}
?>
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');

$userid = $_SESSION['user_id'];
$oldPassword = mysqli_real_escape_string($conn, $_POST['oldPassword']);
$newPassword = md5(mysqli_real_escape_string($conn, $_POST['newPassword']));

// Verify old password
$sqlVerifyPassword = "SELECT UserPassword FROM user WHERE UserID = '$userid'";
$resultVerifyPassword = $conn->query($sqlVerifyPassword);

if ($resultVerifyPassword->num_rows == 1) {
    $row = $resultVerifyPassword->fetch_assoc();
    if ($row['UserPassword'] === md5($oldPassword)) {
        // Old password is correct, proceed to update the password
        $sqlUpdatePassword = "UPDATE user SET UserPassword = '$newPassword' WHERE UserID = '$userid'";
        $resultUpdatePassword = $conn->query($sqlUpdatePassword);

        if ($resultUpdatePassword) {
            // Password updated successfully
            echo "<script>alert('Password Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
            exit();
        } else {
            // Handle the case when the update fails
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Old password is incorrect
        echo "<script>alert('Old Password is Incorrect!'); window.location.href='../admin/profile.php';</script>";
    }
} else {
    // Handle the case when user information is not found
    echo "User information not found.";
}
?>
