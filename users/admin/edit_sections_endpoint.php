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

            // Check for duplicate entry
            $checkSql = "SELECT COUNT(*) FROM CourseSection WHERE SectionNumber = ? AND StartDate = ? AND EndDate = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$sectionNumber, $startDate, $endDate]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                // Duplicate found, send error message
                echo "A section with the same number, start date, and end date already exists.";
            } else {
                // No duplicate, proceed with insertion
                $sql = "INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$courseID, $sectionNumber, $startDate, $endDate]);
                echo "<script>alert('Section added successfully!');</script>";
            }
            break;

        case 'update':
            $sectionID = $_POST['sectionID'];
            $newSectionNumber = $_POST['newSectionNumber'];
            $newStartDate = $_POST['newStartDate'];
            $newEndDate = $_POST['newEndDate'];
            $sql = "UPDATE CourseSection SET SectionNumber = ?, StartDate = ?, EndDate = ? WHERE SectionID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newSectionNumber, $newStartDate, $newEndDate, $sectionID]);
            echo "Section updated successfully!";
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
