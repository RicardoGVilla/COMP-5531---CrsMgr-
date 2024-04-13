<?php
session_start();
require_once '../../database.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Extract data from form
            $course_id = $_POST['course_id'] ?? '';
            $section_number = $_POST['section_number'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';

            // SQL to insert a new section
            $sql = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$course_id, $section_number, $start_date, $end_date]);

            // Redirect or handle response
            header('Location: manage_sections.php');
            break;

        case 'update':
            // Extract data from form
            $section_id = $_POST['section_id'] ?? '';
            $new_start_date = $_POST['new_start_date'] ?? '';
            $new_end_date = $_POST['new_end_date'] ?? '';

            // SQL to update the section
            $sql = "UPDATE CourseSection SET StartDate = ?, EndDate = ? WHERE SectionID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_start_date, $new_end_date, $section_id]);

            // Redirect or handle response
            header('Location: manage_sections.php');
            break;

        case 'delete':
            // Extract section ID from form
            $section_id = $_POST['section_id'] ?? '';

            // SQL to delete the section
            $sql = "DELETE FROM CourseSection WHERE SectionID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$section_id]);

            // Redirect or handle response
            header('Location: manage_sections.php');
            break;

        default:
            // Handle unknown action
            $_SESSION['error'] = "Invalid action specified.";
            header('Location: manage_sections.php');
            break;
    }
} else {
    // Not a POST request
    $_SESSION['error'] = "Invalid request method.";
    header('Location: manage_sections.php');
}
?>
