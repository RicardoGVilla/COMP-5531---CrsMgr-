<?php
// Start the session
session_start();

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) {
    // Redirect to choose-class.php to select a course if no course is selected
    header("Location: choose-class.php");
    exit;
}

// Check if the user's group ID is available in session
if (!isset($_SESSION["user"]["GroupID"])) {
    echo "Error: User's group ID not found.";
    exit;
}

// Retrieve the user's group ID from session
$groupID = $_SESSION["user"]["GroupID"];

// Specify the folder where group files will be stored
$groupFilesDir = "group-files/";

// Create the folder for the user's group if it doesn't exist
$groupFolder = $groupFilesDir . "group-" . $groupID . "/";
if (!file_exists($groupFolder)) {
    if (!mkdir($groupFolder, 0777, true)) {
        echo "Error: Failed to create group folder.";
        exit;
    }
}

// Check if file was uploaded without errors
if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    // Get the temporary file name of the uploaded file
    $tempName = $_FILES["file"]["tmp_name"];

    // Generate a unique file name to avoid overwriting existing files
    $fileName = uniqid() . "_" . basename($_FILES["file"]["name"]);

    // Specify the destination path for the uploaded file
    $uploadPath = $groupFolder . $fileName;

    // Move the uploaded file to the specified destination folder
    if (move_uploaded_file($tempName, $uploadPath)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded or an error occurred during upload.";
}
?>
