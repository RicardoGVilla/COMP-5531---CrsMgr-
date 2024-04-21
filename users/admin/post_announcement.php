<?php

// code written by:
// Ricardo Gutierrez, 40074308


session_start();
require_once '../../database.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Validate form data (ensure required fields are not empty)
    if (empty($course_id) || empty($title) || empty($content)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: manage_announcements.php");
        exit();
    }

    // Insert announcement into the database
    try {
        $query = "INSERT INTO Announcement (CourseID, Title, Content, AnnouncementDate) 
                  VALUES (:course_id, :title, :content, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $_SESSION['success'] = "Announcement posted successfully.";
        header("Location: manage_announcements.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error posting announcement: " . $e->getMessage();
        header("Location: manage_announcements.php");
        exit();
    }
} else {
    // If the request method is not POST, redirect to the manage announcements page
    header("Location: manage_announcements.php");
    exit();
}
?>
