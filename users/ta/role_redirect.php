<?php
session_start(); 

include_once '../../database.php'; 

// Check if the role was selected from the form submission
if (isset($_POST['role'])) {
    // Check if the selected role is "TA"
    if ($_POST['role'] == 'TA') {
        // Redirect to the TA home page
        header('Location: home.php');
        exit;
    } elseif ($_POST['role'] == 'Student') {
        // Check if the student is enrolled in more than one course
        if (!isset($_SESSION["user"]["UserID"])) {
            // Redirect to the login page if the user ID is not set in the session
            header("Location: ../../login.php");
            exit;
        }
        $studentID = $_SESSION["user"]["UserID"]; 
        // Prepare the SQL query using PDO
        $query = "SELECT COUNT(DISTINCT CourseID) AS numCourses FROM StudentEnrollment WHERE StudentID = :studentId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':studentId', $studentID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numCourses = $row['numCourses'];

        // If the student is enrolled in more than one course, redirect them to choose-class.php
        if ($numCourses > 1) {
            header('Location: ../student/choose-class.php');
            exit;
        } else {
            // Redirect to the Student home page
            header('Location: ../student/home.php');
            exit;
        }
    }
} else {
    // Redirect to the login page if no role is selected or if the form wasn't submitted
    header('Location: ../../login.php');
    exit;
}

// If the script somehow reaches this point without a role or action, redirect to the login page as a fallback
header('Location: ../../login.php');
exit;
?>
