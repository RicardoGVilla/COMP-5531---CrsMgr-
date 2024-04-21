<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start();
require_once '../../database.php';

header('Content-Type: application/json');

if (!isset($_SESSION["user"]["UserID"])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionID = $_POST['section_id'] ?? '';
    $userID = $_POST['user_id'] ?? '';
    $action = $_POST['action'] ?? '';

    // Check if the section exists
    $sectionExists = checkSectionExists($sectionID, $pdo);

    if (!$sectionExists) {
        echo json_encode(['error' => 'Section does not exist.']);
        exit;
    }

    switch ($action) {
        case 'enroll_student':
            echo json_encode(enrollUser($sectionID, $userID, 'Student', $pdo));
            break;
        case 'enroll_ta':
            echo json_encode(enrollUser($sectionID, $userID, 'TA', $pdo));
            break;
        case 'remove_student':
            echo json_encode(removeUser($sectionID, $userID, $pdo));
            break;
        default:
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}

// Function definitions go below

function checkSectionExists($sectionID, $pdo) {
    try {
        $sectionQuery = "SELECT * FROM CourseSection WHERE SectionID = :sectionID";
        $stmt = $pdo->prepare($sectionQuery);
        $stmt->execute(['sectionID' => $sectionID]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false; // In production, you may want to log this error.
    }
}

function enrollUser($sectionID, $userID, $role, $pdo) {
    if ($role === 'TA') {
        if (!checkRole($userID, 'TA', $pdo)) {
            return ['error' => 'User is not a TA.'];
        }
    }

    // Check if the user is already enrolled in the section
    if (isUserEnrolled($sectionID, $userID, $pdo)) {
        return ['error' => $role . ' is already enrolled in this section.'];
    }

    // Enroll the user in the section
    try {
        $enrollQuery = "INSERT INTO StudentEnrollment (SectionID, StudentID) VALUES (:sectionID, :userID)";
        $stmt = $pdo->prepare($enrollQuery);
        $stmt->execute(['sectionID' => $sectionID, 'userID' => $userID]);
        return ['success' => $role . ' enrolled successfully!'];
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}

function checkRole($userID, $roleName, $pdo) {
    try {
        $roleQuery = "SELECT * FROM UserRole WHERE UserID = :userID AND RoleID = (SELECT RoleID FROM Role WHERE RoleName = :roleName)";
        $stmt = $pdo->prepare($roleQuery);
        $stmt->execute(['userID' => $userID, 'roleName' => $roleName]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function isUserEnrolled($sectionID, $userID, $pdo) {
    try {
        $enrollmentQuery = "SELECT * FROM StudentEnrollment WHERE SectionID = :sectionID AND StudentID = :userID";
        $stmt = $pdo->prepare($enrollmentQuery);
        $stmt->execute(['sectionID' => $sectionID, 'userID' => $userID]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

function removeUser($sectionID, $userID, $pdo) {
    try {
        $removeQuery = "DELETE FROM StudentEnrollment WHERE SectionID = :sectionID AND StudentID = :userID";
        $stmt = $pdo->prepare($removeQuery);
        $stmt->execute(['sectionID' => $sectionID, 'userID' => $userID]);
        return ['success' => 'User removed successfully from the section.'];
    } catch (PDOException $e) {
        return ['error' => 'Database error: ' . $e->getMessage()];
    }
}
?>
