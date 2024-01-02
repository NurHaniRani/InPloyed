<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../control/dbconnect.php');

$userid = $_SESSION['user_id'];

// File upload handling
$targetDirectory = "../src/";
$targetFile = $targetDirectory . basename($_FILES["newImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if the image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["newImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
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

        // Check if the user already has an image in the database
        $sqlCheckImage = "SELECT * FROM user_image WHERE UserID = '$userid'";
        $resultCheckImage = $conn->query($sqlCheckImage);

        if ($resultCheckImage->num_rows > 0) {
            // User already has an image, perform an UPDATE operation
            $sqlUpdateImage = "UPDATE user_image 
                               SET img_name = '$targetFile', img_type = '$imageFileType', 
                                   img_size = '" . $_FILES["newImage"]["size"] . "', img_data = '$imageData'
                               WHERE UserID = '$userid'";
            $resultUpdateImage = $conn->query($sqlUpdateImage);

            if ($resultUpdateImage) {
                // Image data updated successfully
                echo "<script>alert('Image Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
                exit();
            } else {
                // Handle the case when the update fails
                echo "Error updating image data: " . $conn->error;
            }
        } else {
            // User does not have an image, perform an INSERT operation
            $imgId = uniqid();
            $sqlInsertImage = "INSERT INTO user_image (img_id, img_name, img_type, img_size, img_data, UserID) 
                                VALUES ('$imgId', '$targetFile', '$imageFileType', '" . $_FILES["newImage"]["size"] . "', '$imageData', '$userid')";
            $resultInsertImage = $conn->query($sqlInsertImage);

            if ($resultInsertImage) {
                // Image data inserted successfully
                echo "<script>alert('Image Updated Successfully!'); window.location.href='../admin/profile.php';</script>";
                exit();
            } else {
                // Handle the case when the insert fails
                echo "Error inserting image data: " . $conn->error;
            }
        }
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='../admin/profile.php';</script>";
    }
}
?>
