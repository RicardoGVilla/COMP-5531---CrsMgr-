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
$currentUserID = $_SESSION["user"]["UserID"];
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

    // Fetch the groups associated with the current user ID (student)
    $groupStmt = $pdo->prepare("SELECT g.GroupID, g.CourseID, g.GroupLeaderID 
                                FROM `Group` g
                                JOIN StudentGroupMembership sgm ON g.GroupID = sgm.GroupID
                                WHERE g.CourseID = ? AND sgm.StudentID = ?");
    $groupStmt->execute([$currentCourseID, $currentUserID]);
    $groups = $groupStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h1>Welcome <?php echo htmlspecialchars($currentUserName); ?></h1>
            <p>Course: <?php echo htmlspecialchars($currentCourseName); ?></p>
        </header>

        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button class="is-selected" onclick="location.href='group-information.php'">My Group Information </button>
            <button>My Group (Internal Communication)</button>
        </div>

        <main class="main">
            <h2>Group Members Details</h2>
            <?php if (!empty($groups)): ?>
                <?php foreach ($groups as $group): ?>
                    <h3>Group ID: <?php echo htmlspecialchars($group['GroupID']); ?></h3>
                    <?php
                    // Fetch all members in the group
                    $membersStmt = $pdo->prepare("SELECT u.UserID, u.Name, u.EmailAddress FROM User u 
                                                  JOIN StudentGroupMembership sgm ON u.UserID = sgm.StudentID 
                                                  WHERE sgm.GroupID = ?");
                    $membersStmt->execute([$group['GroupID']]);
                    $members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <ul>
                    <?php foreach ($members as $member): ?>
                        <li><?php echo htmlspecialchars($member['Name']) . ' (' . htmlspecialchars($member['UserID']) . ') - ' . htmlspecialchars($member['EmailAddress']); ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No group found for the current user in this course.</p>
            <?php endif; ?>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
