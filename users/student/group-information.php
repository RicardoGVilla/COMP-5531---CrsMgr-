<?php
// Start the session
session_start();

// Include your database connection file
require_once('../../database.php');

// Check if user is logged in
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php");
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) {
    header("Location: choose-class.php");
    exit;
}

// Initialize variables
$currentUserName = $_SESSION["user"]["Name"];
$currentCourseName = $_SESSION["selectedCourseName"];

try {
    // Fetch Course ID using the course name
    $courseStmt = $pdo->prepare("SELECT CourseID FROM Course WHERE Name = ?");
    $courseStmt->execute([$currentCourseName]);
    $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
    $currentCourseID = $course['CourseID'] ?? null;

    if (!$currentCourseID) {
        throw new Exception("Course not found.");
    }

    // Fetch all groups and their member details for the found course ID
    $groupStmt = $pdo->prepare("SELECT g.GroupID, u.UserID, u.Name, u.EmailAddress 
                                FROM `Group` g
                                JOIN StudentGroupMembership sgm ON g.GroupID = sgm.GroupID
                                JOIN `User` u ON sgm.StudentID = u.UserID
                                WHERE g.CourseID = ?");
    $groupStmt->execute([$currentCourseID]);
    $members = $groupStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Details - <?php echo htmlspecialchars($currentCourseName); ?></title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($currentUserName); ?></h1>
            <p>Course: <?php echo htmlspecialchars($currentCourseName); ?></p>
        </header>

        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button>My Group (Internal Communication)</button>
        </div>

        <main class="main">
            <h2>Group Members Details</h2>
            <?php if (!empty($members)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Group ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['GroupID']); ?></td>
                                <td><?php echo htmlspecialchars($member['UserID']); ?></td>
                                <td><?php echo htmlspecialchars($member['Name']); ?></td>
                                <td><?php echo htmlspecialchars($member['EmailAddress']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No group members found for this course.</p>
            <?php endif; ?>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
