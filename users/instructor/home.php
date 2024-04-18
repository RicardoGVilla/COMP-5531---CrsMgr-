<?php
session_start();
include_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the user's name from session data
$userName = $_SESSION["user"]["Name"];

// Check if the form was submitted and the selected course is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course'])) {
    $courseId = $_POST['course'];
    // Store the selected course ID in the session
    $_SESSION['selected_course_id'] = $courseId;

    $sql = "SELECT Name FROM Course WHERE CourseID = :courseId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['courseId' => $courseId]);
    $courseName = $stmt->fetchColumn();
} else {
    // If no course is selected or the request method is not POST, redirect to home page
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome TA <?php echo htmlspecialchars($userName); ?> [Instructor]</h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQSs</button>
            <button onclick="location.href='manage_announcements.php'">Manage Announcements</button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>

        <main class="main">
            <h2> <?php echo htmlspecialchars($courseName); ?></h2>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
