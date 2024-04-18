<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $courseID = $_POST['courseID'];
            $sectionNumber = $_POST['sectionNumber'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            //Checking for dates
            if(strtotime($endDate)>strtotime($startDate)){
                // Check for duplicate entry
                $checkSql = "SELECT COUNT(*) FROM CourseSection WHERE CourseID = ? AND SectionNumber = ? AND StartDate = ? AND EndDate = ?";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->execute([$courseID, $sectionNumber, $startDate, $endDate]);
                $exists = $checkStmt->fetchColumn();

                if ($exists > 0) {
                    // Duplicate found, send error message
                    echo "A section with the same course ID, number, start date, and end date already exists.";
                } else {
                    // No duplicate, proceed with insertion
                    $sql = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$courseID, $sectionNumber, $startDate, $endDate]);
                    echo "Section added successfully!";
                }
            } else{
                 $_SESSION['error'] = "Course cannot be added! End Date must be later than the Start Date";
            }
            break;

        case 'update':
            $sectionID = $_POST['sectionID'];
            $newSectionNumber = $_POST['newSectionNumber'];
            $newStartDate = $_POST['newStartDate'];
            $newEndDate = $_POST['newEndDate'];

            //Checking dates
            
        if (strtotime($newEndDate)>strtotime($newStartDate)){ 
            $sql = "UPDATE CourseSection SET SectionNumber = ?, StartDate = ?, EndDate = ? WHERE SectionID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newSectionNumber, $newStartDate, $newEndDate, $sectionID]);
            echo "Section updated successfully!";
        } else{
            $_SESSION['error'] = "Course cannot be added! End Date must be later than the Start Date";
        }
            break;

        case 'delete':
            $sectionID = $_POST['sectionID'];
            $sql = "DELETE FROM CourseSection WHERE SectionID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sectionID]);
            echo "Section deleted successfully!";
            break;

        default:
            echo "Invalid action!";
            break;
    }
}
?>
