<?php
session_start();
require_once '../../database.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Add a new section
            $course_id = $_POST['course_id'];
            $section_number = $_POST['section_number'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // Prepare SQL query
            $insertQuery = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate)
                            VALUES (:course_id, :section_number, :start_date, :end_date)";
            $stmt = $pdo->prepare($insertQuery);
            $success = $stmt->execute([
                ':course_id' => $course_id,
                ':section_number' => $section_number,
                ':start_date' => $start_date,
                ':end_date' => $end_date
            ]);

            if ($success) {
                $_SESSION['message'] = "Section added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add section.";
            }
            break;

        case 'update':
            // Update an existing section
            $section_id = $_POST['section_id'];
            $new_start_date = $_POST['new_start_date'];
            $new_end_date = $_POST['new_end_date'];

            // Prepare SQL query
            $updateQuery = "UPDATE CourseSection SET StartDate = :new_start_date, EndDate = :new_end_date 
                            WHERE SectionID = :section_id";
            $stmt = $pdo->prepare($updateQuery);
            $params = [':section_id' => $section_id];
            if (!empty($new_start_date)) $params[':new_start_date'] = $new_start_date;
            if (!empty($new_end_date)) $params[':new_end_date'] = $new_end_date;

            $success = $stmt->execute($params);

            if ($success) {
                $_SESSION['message'] = "Section updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update section.";
            }
            break;

        case 'delete':
            // Delete a section
            $section_id = $_POST['section_id'];

            // Prepare SQL query
            $deleteQuery = "DELETE FROM CourseSection WHERE SectionID = :section_id";
            $stmt = $pdo->prepare($deleteQuery);
            $success = $stmt->execute([':section_id' => $section_id]);

            if ($success) {
                $_SESSION['message'] = "Section deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete section.";
            }
            break;
    }

    // Redirect back to the manage sections page
    header('Location: manage_sections.php');
    exit;
}
?>
