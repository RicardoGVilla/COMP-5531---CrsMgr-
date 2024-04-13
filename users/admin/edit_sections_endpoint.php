<?php

session_start(); 
require_once '../../database.php'; 

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            // Handling for adding a new section (already implemented)
            // ...
            break;
        
        case 'delete':
            // Handling for deleting a section (already implemented)
            // ...
            break;
            
            case 'update':
                // Handling for updating a section
                $courseName = $_POST['course_name']; 
                $newStartDate = $_POST['new_start_date']; 
                $newEndDate = $_POST['new_end_date']; 
                $sectionID = $_POST['section_id']; 
        
                // Building the UPDATE SQL statement based on provided inputs
                $sql = "UPDATE CourseSection SET ";
        
                $updates = [];
                $params = [];
        
                if ($newStartDate) {
                    $updates[] = "StartDate = ?";
                    $params[] = $newStartDate;
                }
                if ($newEndDate) {
                    $updates[] = "EndDate = ?";
                    $params[] = $newEndDate;
                }
        
                if (empty($updates)) {
                    header("Location: manage_sections.php?status=error&message=No updates provided");
                    exit();
                }
        
                $sql .= implode(', ', $updates) . " WHERE SectionID = ?";
        
                // Execute the SQL statement
                try {
                    $stmt = $pdo->prepare($sql);
                    $params[] = $sectionID;
                    $stmt->execute($params);
                    header("Location: manage_sections.php?status=success&message=Section updated");
                    exit();
                } catch (PDOException $e) {
                    header("Location: manage_sections.php?status=error&message=" . urlencode($e->getMessage()));
                    exit();
                }
        
            default:
                // Redirect back with an error message if the action is unrecognized
                header("Location: manage_sections.php?status=error&message=Invalid action");
                exit();
    }

}
?>
