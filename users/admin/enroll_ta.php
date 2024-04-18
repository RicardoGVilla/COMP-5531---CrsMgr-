<?php
session_start();
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get section ID and TA ID from form submission
    $sectionID = $_POST['section_id'];
    $taID = $_POST['ta_id'];

    // Check if the section exists
    try {
        $sectionQuery = "SELECT * FROM CourseSection WHERE SectionID = :sectionID";
        $stmt = $pdo->prepare($sectionQuery);
        $stmt->execute(['sectionID' => $sectionID]);
        $sectionExists = $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    if (!$sectionExists) {
        die("Section does not exist.");
    }

    // Verify that the user is a TA
    try {
        $roleCheckQuery = "SELECT * FROM UserRole WHERE UserID = :userID AND RoleID = (SELECT RoleID FROM Role WHERE RoleName = 'TA')";
        $roleCheckStmt = $pdo->prepare($roleCheckQuery);
        $roleCheckStmt->execute(['userID' => $taID]);
        $isTA = $roleCheckStmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    if (!$isTA) {
        die("User is not a TA.");
    }

    // Check if the TA is already enrolled in the section
    try {
        $enrollmentQuery = "SELECT * FROM StudentEnrollment WHERE SectionID = :sectionID AND StudentID = :taID";
        $enrollmentStmt = $pdo->prepare($enrollmentQuery);
        $enrollmentStmt->execute(['sectionID' => $sectionID, 'taID' => $taID]);
        if ($enrollmentStmt->rowCount() > 0) {
            die("TA is already enrolled in this section.");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

    // Enroll the TA in the section
    try {
        $enrollQuery = "INSERT INTO StudentEnrollment (SectionID, StudentID) VALUES (:sectionID, :taID)";
        $enrollStmt = $pdo->prepare($enrollQuery);
        $enrollStmt->execute(['sectionID' => $sectionID, 'taID' => $taID]);
        echo "TA enrolled successfully!";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
