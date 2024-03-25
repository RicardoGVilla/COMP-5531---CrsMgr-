<?php
session_start(); 
require_once '../../database.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
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
            break;
    }
}

function addCourse() {
    global $pdo;

    $courseName = $_POST['course_name'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $sections = explode(',', $_POST['sections']); // Split section numbers into an array
    $instructors = explode(',', $_POST['instructors']); // Split instructor IDs into an array

    try {
        // Insert the course into the Course table
        $query = "INSERT INTO Course (Name, StartDate, EndDate) VALUES (:courseName, :startDate, :endDate)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':courseName' => $courseName, ':startDate' => $startDate, ':endDate' => $endDate]);

        // Retrieve the ID of the newly inserted course
        $courseId = $pdo->lastInsertId();

        // Insert section numbers into the CourseSection table
        foreach ($sections as $section) {
            $query = "INSERT INTO CourseSection (CourseID, SectionNumber) VALUES (:courseId, :section)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':courseId' => $courseId, ':section' => $section]);
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

    header('Location: manage_courses.php');
    exit;
}


function deleteCourse() {
    global $pdo; 
    $courseId = $_POST['course_id'];

    try {
        $query = "DELETE FROM Course WHERE CourseID = :courseId";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':courseId' => $courseId]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Course deleted successfully";
        } else {
            // If no rows were affected, the course ID might not exist
            $_SESSION['error'] = "No course found with ID: $courseId";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting course: " . $e->getMessage();
    }

    // Redirect back to a specific page to show operation result
    header('Location: manage_courses.php');
    exit;
}



function updateCourse() {
    global $pdo; // Ensure $pdo is accessible

    $courseId = $_POST['course_id'];
    $newCourseName = $_POST['new_course_name'];
    $newStartDate = $_POST['new_start_date'];
    $newEndDate = $_POST['new_end_date'];

    try {
        // Prepare the query parts and parameters
        $queryParts = [];
        $params = [':courseId' => $courseId];

        if (!empty($newCourseName)) {
            $queryParts[] = "Name = :newCourseName";
            $params[':newCourseName'] = $newCourseName;
        }
        if (!empty($newStartDate)) {
            $queryParts[] = "StartDate = :newStartDate";
            $params[':newStartDate'] = $newStartDate;
        }
        if (!empty($newEndDate)) {
            $queryParts[] = "EndDate = :newEndDate";
            $params[':newEndDate'] = $newEndDate;
        }


        if (!empty($queryParts)) {
            $query = "UPDATE Course SET " . join(', ', $queryParts) . " WHERE CourseID = :courseId";
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Course updated successfully.";
            } else {
                // If no rows were affected, it could mean the ID doesn't exist or the data is the same
                $_SESSION['message'] = "No changes were made to the course.";
            }
        } else {
            $_SESSION['error'] = "No updates provided.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating course: " . $e->getMessage();
    }

    header('Location: manage_courses.php');
    exit;
}

// Integrates the updateCourse function into the switch statement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
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
            break;
    }
}


?>
