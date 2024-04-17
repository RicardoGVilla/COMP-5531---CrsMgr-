<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the action is set
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'add':
                addCourse();
                break;
            case 'update':
                updateCourse();
                break;
            case 'delete':
                deleteCourse();
                break;
            default:
                $_SESSION['error'] = "Invalid action.";
                break;
        }
    } else {
        $_SESSION['error'] = "Action not specified.";
    }
}

function addCourse() {
    global $pdo;

    // Retrieve data from the POST request
    $courseName = $_POST['course_name'];
    $courseCode = $_POST['course_code'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $sections = explode(',', $_POST['sections']); 
    $instructors = explode(',', $_POST['instructors']);

    try {
        // Insert the course into the Course table
        $query = "INSERT INTO Course (Name, CourseCode, StartDate, EndDate) VALUES (:courseName, :courseCode, :startDate, :endDate)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':courseName' => $courseName, ':courseCode' => $courseCode, ':startDate' => $startDate, ':endDate' => $endDate]);

        // Retrieve the ID of the newly inserted course
        $courseId = $pdo->lastInsertId();

        // Insert section numbers into the CourseSection table 
        foreach ($sections as $section) {
            $query = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES (:courseId, :section, :startDate, :endDate)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':courseId' => $courseId, ':section' => $section, ':startDate' => $startDate, ':endDate' => $endDate]);
        }

        // Insert instructor IDs into the CourseInstructor table
        foreach ($instructors as $instructor) {
            $query = "INSERT INTO CourseInstructor (CourseID, InstructorID) VALUES (:courseId, :instructor)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':courseId' => $courseId, ':instructor' => $instructor]);
        }

        $_SESSION['message'] = "Course added successfully";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding course: " . $e->getMessage();
    }

    // Redirect back to the manage courses page
    header('Location: manage_courses.php');
    exit;
}


function updateCourse()
{
    global $pdo;

    // Retrieve data from the POST request
    $courseID = $_POST['course_id'];
    $newCourseName = $_POST['new_course_name'];
    $newStartDate = $_POST['new_start_date'];
    $newEndDate = $_POST['new_end_date'];
    $newInstructorID = $_POST['new_instructors'];

    try {
        // Update the course details
        $query = "UPDATE Course SET Name = ?, StartDate = ?, EndDate = ? WHERE CourseID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newCourseName, $newStartDate, $newEndDate, $courseID]);

        // Update the instructor associated with the course
        $query = "UPDATE CourseInstructor SET InstructorID = ? WHERE CourseID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newInstructorID, $courseID]);

        $_SESSION['success'] = "Course updated successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating course: " . $e->getMessage();
    }
}

function deleteCourse()
{
    global $pdo;

    // Retrieve the course ID from the POST request
    $courseID = $_POST['course_id'];

    try {
        // Delete the course
        $query = "DELETE FROM Course WHERE CourseID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$courseID]);

        $_SESSION['success'] = "Course deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting course: " . $e->getMessage();
    }
}

// Redirect back to the manage courses page
header("Location: manage_courses.php");
exit();
?>
