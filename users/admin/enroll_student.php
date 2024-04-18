<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in and has a user ID stored in session
    if (!isset($_SESSION["user"]["UserID"])) {
        header("Location: login.php");
        exit;
    }

    // Check if 'action' parameter is set
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Enrollment logic
        if ($action === 'enroll_student') {
            // Validate input data
            $sectionID = $_POST['section_id'];
            $studentID = $_POST['student_id'];
            $courseID = $_POST['course_id'];

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
                $enrollQuery = "INSERT INTO StudentEnrollment (SectionID, StudentID, CourseID, EnrollmentDate) VALUES (:sectionID, :studentID, :courseID, :enrollmentDate)";
                $enrollStmt = $pdo->prepare($enrollQuery);

                // Assuming you have the CourseID and EnrollmentDate available in your application
                $enrollmentDate = date('Y-m-d'); // Assuming you want to use the current date for enrollment

                $enrollStmt->execute([
                    'sectionID' => $sectionID,
                    'studentID' => $studentID,
                    'courseID' => $courseID,
                    'enrollmentDate' => $enrollmentDate
                ]);
                echo "Student enrolled successfully!";
            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
        } elseif ($action === 'remove_student') { // Delete logic
            // Validate input data
            $sectionID = $_POST['section_id'];
            $studentID = $_POST['student_id'];

            try {
                // Delete the student enrollment from the database
                $deleteQuery = "DELETE FROM StudentEnrollment WHERE SectionID = :sectionID AND StudentID = :studentID";
                $deleteStmt = $pdo->prepare($deleteQuery);
                $deleteStmt->execute(['sectionID' => $sectionID, 'studentID' => $studentID]);
                echo "Student removed successfully!";
            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
        } else {
            echo "Invalid action.";
        }
    } else {
        echo "Action parameter is missing.";
    }
} else {
    echo "Invalid request method.";
}
?>
