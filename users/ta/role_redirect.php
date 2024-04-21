<?php

// code logic written by:
// Ricardo Gutierrez, 40074308


session_start(); 

include_once '../../database.php'; 

// Check if the role was selected from the form submission
if (isset($_POST['role'])) {
    if ($_POST['role'] == 'TA') {
        // Check if the TA is teaching more than one course
        if (!isset($_SESSION["user"]["UserID"])) {
            // Redirect to the login page if the user ID is not set in the session
            header("Location: ../../login.php");
            exit;
        }
        $taID = $_SESSION["user"]["UserID"]; // Use the session variable
        
        // Prepare the SQL query to count distinct courses taught by the TA
        $query = "
            SELECT COUNT(DISTINCT c.CourseID) AS numCourses
            FROM `User` u
            JOIN UserRole ur ON u.UserID = ur.UserID
            JOIN Role r ON ur.RoleID = r.RoleID
            JOIN StudentEnrollment se ON u.UserID = se.StudentID
            JOIN CourseSection cs ON se.SectionID = cs.SectionID
            JOIN Course c ON cs.CourseID = c.CourseID
            WHERE r.RoleName = 'TA' AND u.UserID = :taId
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':taId', $taID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numCourses = $row['numCourses'];

        // Redirect based on the number of courses taught
        if ($numCourses > 1) {
            header('Location: ../ta/choose-class.php');
            exit;
        } else {
            header('Location: ../ta/home.php');
            exit;
        }
    } elseif ($_POST['role'] == 'Student') {
        // (Existing code for handling student role)
        if (!isset($_SESSION["user"]["UserID"])) {
            header("Location: ../../login.php");
            exit;
        }
        $studentID = $_SESSION["user"]["UserID"];
        
        $query = "SELECT COUNT(DISTINCT CourseID) AS numCourses FROM StudentEnrollment WHERE StudentID = :studentId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':studentId', $studentID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $numCourses = $row['numCourses'];

        if ($numCourses > 1) {
            header('Location: ../student/choose-class.php');
            exit;
        } else {
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
