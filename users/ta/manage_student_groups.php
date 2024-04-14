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

echo "<h1>Student Groups</h1>";
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
