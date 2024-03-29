<?php
session_start();
require_once '../../database.php';

var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'enroll_student') {
    $studentID = $_POST['student_id'];
    $courseID = $_POST['course_id'];
    $sectionID = $_POST['section_id'];

    // Initialize a message variable
    $message = "";

    $checkEnrollment = $pdo->prepare("SELECT COUNT(*) FROM StudentEnrollment WHERE StudentID = ? AND CourseID = ? AND SectionID = ?");
    $checkEnrollment->execute([$studentID, $courseID, $sectionID]);
    $isEnrolled = $checkEnrollment->fetchColumn() > 0;

    if (!$isEnrolled) {
        $insertEnrollment = $pdo->prepare("INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES (?, ?, ?, NOW())");
        if ($insertEnrollment->execute([$studentID, $courseID, $sectionID])) {
            $message = "Enrollment successful. Student ID: $studentID, Course ID: $courseID, Section ID: $sectionID.";
        } else {
            $message = "Failed to enroll the student.";
        }
    } else {
        $message = "Student is already enrolled in this course section.";
    }
    
    // Display the message and a Print button for confirmation
    echo "<!DOCTYPE html><html><head><title>Enrollment Confirmation</title></head><body>";
    echo "<h2>Enrollment Confirmation</h2>";
    echo "<p>{$message}</p>";
    echo "<button onclick='window.print()'>Print this page</button>";
    echo "</body></html>";
}
?>
