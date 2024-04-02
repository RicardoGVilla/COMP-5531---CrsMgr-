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

            <div class="container">
                <h2>Selected Course Information</h2>
                <table>
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
      
      
