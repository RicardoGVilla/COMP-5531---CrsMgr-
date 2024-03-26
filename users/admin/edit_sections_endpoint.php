<?php

session_start(); 
require_once '../../database.php'; 

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            // Validate and sanitize the incoming data
            $courseID = $_POST["course_id"];
            $sectionNumber = $_POST["section_number"];
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];

            // Check if a section with the same course ID and section number already exists
            $sql = "SELECT COUNT(*) AS count FROM CourseSection WHERE CourseID = ? AND SectionNumber = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$courseID, $sectionNumber]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // If a section already exists, redirect back with an error message
                header("Location: manage_sections.php?status=duplicate");
                exit();
            }

            // Insert the new section into the database
            try {
                $sql = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$courseID, $sectionNumber, $startDate, $endDate]);

                // Redirect back to the manage sections page with success message
                header("Location: manage_sections.php?status=success");
                exit();
            } catch (PDOException $e) {
                // Handle any database errors
                header("Location: manage_sections.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
            break;
        
        case 'delete':
            $sectionID = filter_input(INPUT_POST, 'section_id', FILTER_SANITIZE_NUMBER_INT);

            if ($sectionID) {
                $sql = "DELETE FROM CourseSection WHERE SectionID = ?";
                $stmt = $pdo->prepare($sql);

                try {
                    if ($stmt->execute([$sectionID])) {
                        header("Location: manage_sections.php?status=success&message=Section Deleted");
                        exit();
                    } else {
                        header("Location: manage_sections.php?status=error&message=Failed to delete the section");
                        exit();
                    }
                } catch (PDOException $e) {
                    header("Location: manage_sections.php?status=error&message=" . urlencode($e->getMessage()));
                    exit();
                }
            } else {
                header("Location: manage_sections.php?status=error&message=Invalid section ID");
                exit();
            }
            break;
            case 'update':
                $sectionID = filter_input(INPUT_POST, 'section_id', FILTER_SANITIZE_NUMBER_INT);
                $newCourseID = filter_input(INPUT_POST, 'new_course_id', FILTER_SANITIZE_NUMBER_INT);
                $newSectionNumber = filter_input(INPUT_POST, 'new_section_number', FILTER_SANITIZE_NUMBER_INT);
                $newStartDate = $_POST['new_start_date']; 
                $newEndDate = $_POST['new_end_date']; 
    
                // Building the UPDATE SQL statement dynamically based on provided inputs
                $updates = [];
                $params = [];
                if ($newCourseID) {
                    $updates[] = "CourseID = ?";
                    $params[] = $newCourseID;
                }
                if ($newSectionNumber) {
                    $updates[] = "SectionNumber = ?";
                    $params[] = $newSectionNumber;
                }
                if ($newStartDate) {
                    $updates[] = "StartDate = ?";
                    $params[] = $newStartDate;
                }
                if ($newEndDate) {
                    $updates[] = "EndDate = ?";
                    $params[] = $newEndDate;
                }
    
                if (count($updates) > 0) {
                    $sql = "UPDATE CourseSection SET " . implode(', ', $updates) . " WHERE SectionID = ?";
                    $params[] = $sectionID;
                    
                    try {
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);
                        header("Location: manage_sections.php?status=success&message=Section updated");
                    } catch (PDOException $e) {
                        header("Location: manage_sections.php?status=error&message=" . urlencode($e->getMessage()));
                    }
                } else {
                    // No fields to update
                    header("Location: manage_sections.php?status=error&message=No updates provided");
                }
                break;
    
            default:
                // Redirect back with an error message if the action is unrecognized
                header("Location: manage_sections.php?status=error&message=Invalid action");
                exit();
        }
    } else {
        // If the request method is not POST or the action is not set, redirect back to the manage sections page
        header("Location: manage_sections.php");
        exit();
    }
?>
