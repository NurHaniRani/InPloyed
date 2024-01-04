<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');
// Set the time zone to Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur');

$userid = $_SESSION['user_id'];

// File upload handling
$targetDirectory = "../src/";
$targetFile = $targetDirectory . basename($_FILES["newImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Get the current date and time
$currentDate = date("j F Y"); // Example: 3 January 2023
$currentTime = date("g:i a"); // Example: 3:00 pm

// Check if the image file is a real image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["newImage"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('Sorry, file is not an image.'); window.location.href='../admin/profile.php';</script>";
        $uploadOk = 0;
    }
}

// Check file size
if ($_FILES["newImage"]["size"] > 50000000) {
    echo "<script>alert('Sorry, your file is too large.'); window.location.href='../admin/profile.php';</script>";
    $uploadOk = 0;
}

// Allow certain file formats
$allowedFormats = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowedFormats)) {
    echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed'); window.location.href='../admin/profile.php';</script>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<script>alert('Sorry, your file is not uploaded.'); window.location.href='../admin/profile.php';</script>";
} else {
    if (move_uploaded_file($_FILES["newImage"]["tmp_name"], $targetFile)) {
        // Read the new image data
        $imageData = file_get_contents($targetFile);
        $imageData = mysqli_real_escape_string($conn, $imageData);

        // Update the user image data
        $sqlUpdateImage = "UPDATE user_image 
                          SET img_name = '$targetFile', img_type = '$imageFileType', 
                              img_size = '" . $_FILES["newImage"]["size"] . "', img_data = '$imageData'
                          WHERE UserID = '$userid'";
        $resultUpdateImage = $conn->query($sqlUpdateImage);

        // Update the LastUpdateDate and LastUpdateTime
        $sqlUpdateUser = "UPDATE user 
                          SET LastUpdateDate = '$currentDate', LastUpdateTime = '$currentTime'
                          WHERE UserID = '$userid'";
        $resultUpdateUser = $conn->query($sqlUpdateUser);

        if ($resultUpdateImage && $resultUpdateUser) {
            // Image data and user data updated successfully
            echo "<script>alert('Image Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
            exit();
        } else {
            // Handle the case when the update fails
            echo "Error updating image and user data: " . $conn->error;
        }
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='../admin/profile.php';</script>";
    }
}
?>
