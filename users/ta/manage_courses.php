<?php
// Start the session
session_start();

// Include database connection
include_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

if (!isset($_SESSION["user"]["Name"])) {
    header("Location: ../../login.php");
    exit;
}

// Check if a selected course ID is set in the session
if (!isset($_SESSION["selectedCourseId"])) {
    // Redirect to a course selection page or similar
    header("Location: choose_course.php");
    exit;
}

// Assuming your database connection is $pdo
$userId = $_SESSION["user"]["UserID"];
$selectedCourseId = $_SESSION["selectedCourseId"];
$userName = $_SESSION["user"]["Name"];

// Prepare SQL query to get the course details
$query = "SELECT c.CourseCode, c.Name, cs.SectionNumber, cs.StartDate, cs.EndDate 
          FROM Course c 
          JOIN CourseSection cs ON c.CourseID = cs.CourseID
          WHERE c.CourseID = :courseId";

// Prepare the statement
$stmt = $pdo->prepare($query);

// Bind the course ID parameter
$stmt->bindParam(':courseId', $selectedCourseId, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the course details
$courseDetails = $stmt->fetch(PDO::FETCH_ASSOC);
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
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
        </div>
        
        <main class="main">
            <h2>Current Class: <?php echo isset($_SESSION["selectedCourseName"]) ? htmlspecialchars($_SESSION["selectedCourseName"]) : "No class selected"; ?></h2>
            <?php if ($courseDetails): ?>
                <table border='1'>
                    <tr><th>Course Code</th><th>Course Name</th><th>Section Number</th><th>Start Date</th><th>End Date</th></tr>
                    <tr>
                        <td><?php echo htmlspecialchars($courseDetails['CourseCode']); ?></td>
                        <td><?php echo htmlspecialchars($courseDetails['Name']); ?></td>
                        <td><?php echo htmlspecialchars($courseDetails['SectionNumber']); ?></td>
                        <td><?php echo htmlspecialchars($courseDetails['StartDate']); ?></td>
                        <td><?php echo htmlspecialchars($courseDetails['EndDate']); ?></td>
                    </tr>
                </table>
            <?php else: ?>
                <p>Course details not found.</p>
            <?php endif; ?>
        </main>
        
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
