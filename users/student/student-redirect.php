<?php
// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start();

// Include database connection
include_once '../../database.php';

// Check if the form was submitted and a course was selected
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course'])) {
    $selectedCourseId = $_POST['course'];
    $userId = $_SESSION["user"]["UserID"];

    // Validate if the user is enrolled in the selected course
    $stmt = $pdo->prepare("SELECT Course.Name FROM Course INNER JOIN StudentEnrollment ON Course.CourseID = StudentEnrollment.CourseID WHERE StudentEnrollment.StudentID = :userId AND Course.CourseID = :courseId");
    $stmt->execute(['userId' => $userId, 'courseId' => $selectedCourseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        // Store the selected course ID and name in the session for later use
        $_SESSION["selectedCourseId"] = $selectedCourseId;
        $_SESSION["selectedCourseName"] = $course['Name'];

        // Redirect to the student home page
        header('Location: home.php');
        exit;
    } else {
        // If the user is not enrolled in the selected course, handle the error
        $_SESSION["error"] = "You are not enrolled in the selected course.";
        header("Location: choose-class.php");
        exit;
    }
} else {
    // If the form wasn't submitted properly, redirect back to choose-class
    header("Location: choose-class.php");
    exit;
}
?>
