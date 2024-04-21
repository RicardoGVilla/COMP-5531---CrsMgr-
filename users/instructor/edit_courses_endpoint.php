<?php

// code written by:
// Ricardo Gutierrez, 40074308


session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'enroll_student') {
        // Enrollment logic
        $studentID = $_POST['student_id'];
        $courseID = $_POST['course_id'];
        $sectionID = $_POST['section_id'];

        // Initialize message variables
        $studentName = "";
        $courseName = "";
        $message = "";

        // Fetch student name based on student ID
        $studentQuery = $pdo->prepare("SELECT Name FROM User WHERE UserID = ?");
        $studentQuery->execute([$studentID]);
        $studentName = $studentQuery->fetchColumn();

        // Fetch course name based on course ID
        $courseQuery = $pdo->prepare("SELECT Name FROM Course WHERE CourseID = ?");
        $courseQuery->execute([$courseID]);
        $courseName = $courseQuery->fetchColumn();

        // Check if the student is already enrolled in this course section
        $checkEnrollment = $pdo->prepare("SELECT COUNT(*) FROM StudentEnrollment WHERE StudentID = ? AND CourseID = ? AND SectionID = ?");
        $checkEnrollment->execute([$studentID, $courseID, $sectionID]);
        $isEnrolled = $checkEnrollment->fetchColumn() > 0;

        if (!$isEnrolled) {
            // If the student is not already enrolled, insert the enrollment record
            $insertEnrollment = $pdo->prepare("INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES (?, ?, ?, NOW())");
            if ($insertEnrollment->execute([$studentID, $courseID, $sectionID])) {
                $message = "Enrollment successful. Student Name: $studentName, Course Name: $courseName, Section ID: $sectionID.";
            } else {
                $message = "Failed to enroll the student.";
            }
        } else {
            $message = "Student is already enrolled in this course section.";
        }

        // Display the message
        echo $message;
    } elseif ($_POST['action'] == 'remove_student') {
        // Removal logic
        $studentID = $_POST['student_id'];
        $sectionID = $_POST['section_id'];

        // Remove the student from the StudentEnrollment table
        $deleteEnrollment = $pdo->prepare("DELETE FROM StudentEnrollment WHERE StudentID = ? AND SectionID = ?");
        if ($deleteEnrollment->execute([$studentID, $sectionID])) {
            $message = "Student removed successfully.";
        } else {
            $message = "Failed to remove the student.";
        }

        // Display the message
        echo $message;
    }
}
?>
