<?php
// Start the session
session_start();

// Include database connection
include_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selectedCourseId"])) {
    header("Location: login.php"); // Redirect to login page if not logged in or course not selected
    exit;
}

$userId = $_SESSION["user"]["UserID"];
$selectedCourseId = $_SESSION["selectedCourseId"];

// Prepare a SQL query to fetch course, section, start and end dates, professor name, and professor email associated with the selected course
$sql = "SELECT c.Name AS CourseName, cs.SectionNumber, cs.StartDate, cs.EndDate, u.Name AS Professor, u.EmailAddress AS ProfessorEmail
        FROM Course c
        INNER JOIN CourseSection cs ON c.CourseID = cs.CourseID
        INNER JOIN CourseInstructor ci ON cs.CourseID = ci.CourseID
        INNER JOIN `User` u ON ci.InstructorID = u.UserID
        WHERE c.CourseID = :selectedCourseId
        AND EXISTS (
            SELECT 1 
            FROM StudentEnrollment se 
            WHERE se.StudentID = :userId AND se.CourseID = c.CourseID
        )";

$stmt = $pdo->prepare($sql);
$stmt->execute(['selectedCourseId' => $selectedCourseId, 'userId' => $userId]);

// Fetch the course and section associated with the selected course
$course = $stmt->fetch(PDO::FETCH_ASSOC);
?>




<?php
// Start the session
session_start();

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) {
    // Redirect to choose-class.php to select a course if no course is selected
    header("Location: choose-class.php");
    exit;
}

$selectedCourseID = $_SESSION["selectedCourseName"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - CrsMgr+</title>
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
            <button>My Group (Internal Communication)</button>
            <button>Course Material</button>
        </div>
        <main class="main">
            <div class="table-wrapper">
                <h2>Selected Course Information</h2>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Professor</th>
                            <th>Professor Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($course['CourseName']); ?></td>
                            <td><?php echo htmlspecialchars($course['SectionNumber']); ?></td>
                            <td><?php echo htmlspecialchars($course['StartDate']); ?></td>
                            <td><?php echo htmlspecialchars($course['EndDate']); ?></td>
                            <td><?php echo htmlspecialchars($course['Professor']); ?></td>
                            <td><?php echo htmlspecialchars($course['ProfessorEmail']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>




            

            