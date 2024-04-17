<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in and has a user ID stored in session
    if (!isset($_SESSION["user"]["UserID"])) {
        header("Location: login.php");
        exit;
    }

    // Validate input data
    $sectionID = $_POST['section_id'];
    $studentID = $_POST['student_id'];

    // Check if the section exists and if the student ID is valid
    $sectionExists = false;
    $studentExists = false;

    try {
        $sectionQuery = "SELECT * FROM CourseSection WHERE SectionID = :sectionID";
        $sectionStmt = $pdo->prepare($sectionQuery);
        $sectionStmt->execute(['sectionID' => $sectionID]);
        $sectionExists = $sectionStmt->rowCount() > 0;

        $studentQuery = "SELECT * FROM User WHERE UserID = :studentID";
        $studentStmt = $pdo->prepare($studentQuery);
        $studentStmt->execute(['studentID' => $studentID]);
        $studentExists = $studentStmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }

    if (!$sectionExists || !$studentExists) {
        echo "Invalid section ID or student ID.";
        exit;
    }

    // Check if the student is already enrolled in the section
    try {
        $enrollmentQuery = "SELECT * FROM StudentEnrollment WHERE SectionID = :sectionID AND StudentID = :studentID";
        $enrollmentStmt = $pdo->prepare($enrollmentQuery);
        $enrollmentStmt->execute(['sectionID' => $sectionID, 'studentID' => $studentID]);
        if ($enrollmentStmt->rowCount() > 0) {
            echo "Student is already enrolled in this section.";
            exit;
        }
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }

    // Enroll the student in the section
    try {
        $enrollQuery = "INSERT INTO StudentEnrollment (SectionID, StudentID) VALUES (:sectionID, :studentID)";
        $enrollStmt = $pdo->prepare($enrollQuery);
        $enrollStmt->execute(['sectionID' => $sectionID, 'studentID' => $studentID]);
        echo "Student enrolled successfully!";
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
