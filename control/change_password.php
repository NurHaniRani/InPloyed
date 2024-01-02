<!-- change_password.php -->
<?php
// Include your database connection file here
include 'dbconnect.php'; // Update with your actual database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get input values
  $email = $_POST["email"];
  $newPassword = md5($_POST["new_password"]);

  // Validate email (you may want to add more validation)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit;
  }

  // Check if the user with the given email exists
  $stmt = $conn->prepare("SELECT * FROM `user` WHERE `UserEmail` = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // If the user exists, update the password
  if ($result->num_rows > 0) {
    $updateQuery = "UPDATE `user` SET `UserPassword` = ? WHERE `UserEmail` = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $newPassword, $email);
    $stmt->execute();

    echo "Password changed successfully";
  } else {
    echo "User not found";
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>

