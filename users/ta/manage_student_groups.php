<?php
// Start the session and include the database connection
session_start();
include_once '../../database.php';

// Check if the user is logged in and a course ID is stored in the session
if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selectedCourseId"])) {
    header("Location: ../../login.php");
    exit;
}

$selectedCourseId = $_SESSION["selectedCourseId"];

// Prepare the SQL query
$query = "
    SELECT 
        gr.GroupID,
        gr.GroupLeaderID,
        u.UserID,
        u.Name AS StudentName,
        u.EmailAddress
    FROM 
        `Group` gr
    JOIN 
        StudentGroupMembership sgm ON gr.GroupID = sgm.GroupID
    JOIN 
        `User` u ON sgm.StudentID = u.UserID
    WHERE 
        gr.CourseID = :courseId
    ORDER BY 
        gr.GroupID, u.UserID;
";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':courseId', $selectedCourseId, PDO::PARAM_INT);
$stmt->execute();

$groupMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome TA <?php echo htmlspecialchars($userName); ?></h1>
            <p>You are signed in as a Teaching Assistant</p>
        </header>
        
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Course Details</button>
            <button class="is-selected" onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='internal_email.php'">Internal Email Communication </button>
        </div>
        
        <main class="main">
            <h1>Student Groups</h1>
            <div class="table-wrapper">
                <?php
                    if ($groupMembers) {
                        echo "<table border='1'>";
                        echo "<tr><th>Group ID</th><th>Group Leader ID</th><th>Student ID</th><th>Student Name</th><th>Email Address</th></tr>";
                        foreach ($groupMembers as $member) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($member['GroupID']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['GroupLeaderID']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['UserID']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['StudentName']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['EmailAddress']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No student groups found for this course.</p>";
                    }
                ?>
            </div>
        </main>
        
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='choose_course.php'">Change Course</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>

