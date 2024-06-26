<?php
// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052


session_start();

include_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if selected course ID is available in session
if (!isset($_SESSION["selectedCourseId"])) {
    // Redirect to choose-class.php to select a course if no course is selected
    header("Location: choose-class.php");
    exit;
}

$selectedCourseId = $_SESSION["selectedCourseId"];

// Prepare a SQL query to fetch course, section, start and end dates, professor name, and professor email
$sql = "SELECT c.CourseCode, c.Name AS CourseName, cs.SectionNumber, cs.StartDate, cs.EndDate, u.Name AS Professor, u.EmailAddress AS ProfessorEmail
        FROM Course c
        INNER JOIN CourseSection cs ON c.CourseID = cs.CourseID
        INNER JOIN CourseInstructor ci ON cs.CourseID = ci.CourseID
        INNER JOIN `User` u ON ci.InstructorID = u.UserID
        WHERE c.CourseID = :selectedCourseId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':selectedCourseId', $selectedCourseId, PDO::PARAM_INT);
$stmt->execute();

// Fetch the course and section associated with the selected course
$course = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>
        
        <div class="sidebar">
            <button class="is-selected" onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button onclick="location.href='manage_announcements.php'">Announcements</button>
            <button onclick="location.href='internal_email.php'">Internal Email Communication </button>
        </div>

        <main class="main">
            <h2>Course Details</h2>
            <div class="table-wrapper">
                <?php if ($course): ?>
                    <table class="content-table">
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Section</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Professor</th>
                            <th>Professor Email</th>
                        </tr>
                        <tr>
                            <td><?php echo htmlspecialchars($course['CourseCode']); ?></td>
                            <td><?php echo htmlspecialchars($course['CourseName']); ?></td>
                            <td><?php echo htmlspecialchars($course['SectionNumber']); ?></td>
                            <td><?php echo htmlspecialchars($course['StartDate']); ?></td>
                            <td><?php echo htmlspecialchars($course['EndDate']); ?></td>
                            <td><?php echo htmlspecialchars($course['Professor']); ?></td>
                            <td><?php echo htmlspecialchars($course['ProfessorEmail']); ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>No details found for the selected course.</p>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='choose-class.php'">Change Course</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
