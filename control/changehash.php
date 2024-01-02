<?php
// Include the database connection file
require_once('../control/dbconnect.php');

// Fetch all users from the database
$sqlUsers = "SELECT UserID, UserPassword FROM user";
$resultUsers = $conn->query($sqlUsers);

if ($resultUsers->num_rows > 0) {
    while ($row = $resultUsers->fetch_assoc()) {
        // Hash the existing password
        $hashedPassword = password_hash($row['UserPassword'], PASSWORD_DEFAULT);

        // Update the user's password in the database
        $userId = $row['UserID'];
        $sqlUpdatePassword = "UPDATE user SET UserPassword = '$hashedPassword' WHERE UserID = '$userId'";
        $conn->query($sqlUpdatePassword);
    }
}

// Close the database connection
$conn->close();
?>