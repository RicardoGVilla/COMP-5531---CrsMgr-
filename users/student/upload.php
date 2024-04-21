<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start();

require_once('../../database.php');

if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); 
    exit;
}

if (!isset($_SESSION["selectedCourseName"])) {
    header("Location: choose-class.php");
    exit;
}

// Initialize variables
$currentUserID = $_SESSION["user"]["UserID"];
$currentCourseName = $_SESSION["selectedCourseName"];

try {
    // Fetch Course ID using the course name
    $courseStmt = $pdo->prepare("SELECT CourseID FROM Course WHERE Name = ?");
    $courseStmt->execute([$currentCourseName]);
    $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
    $currentCourseID = $course['CourseID'] ?? null;

    if (!$currentCourseID) {
        throw new Exception("Course not found.");
    }

    // Fetch the first group associated with the current user ID (student) and selected course
    $groupStmt = $pdo->prepare("SELECT g.GroupID 
                                FROM `Group` g
                                JOIN StudentGroupMembership sgm ON g.GroupID = sgm.GroupID
                                WHERE g.CourseID = ? AND sgm.StudentID = ?
                                LIMIT 1");
    $groupStmt->execute([$currentCourseID, $currentUserID]);
    $group = $groupStmt->fetch(PDO::FETCH_ASSOC);

    if (!$group) {
        echo "Error: No group found for the user in this course. Please join a group.";
        exit;
    }

    // Store the group ID in the session
    $_SESSION["user"]["GroupID"] = $group['GroupID'];

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Retrieve the user's group ID from session
$groupID = $_SESSION["user"]["GroupID"];

// Specify the folder where group files will be stored
$groupFilesDir = "../../group-files/";

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

    // Sanitize the file name to avoid directory traversal issues
    $safeFileName = basename($_FILES["file"]["name"]);
    $fileName = uniqid() . "_" . $safeFileName;

    // Specify the destination path for the uploaded file
    $uploadPath = $groupFolder . $fileName;

    // Move the uploaded file to the specified destination folder
    if (move_uploaded_file($tempName, $uploadPath)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading file.";
    }
} else {
    if (isset($_FILES["file"])) {
        echo "Error: " . $_FILES["file"]["error"];
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
}
?>
