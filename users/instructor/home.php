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
    $_SESSION['course_name'] = $courseName;
} elseif($_SESSION['selected_course_id']) {
    // If no course is selected or the request method is not POST, redirect to home page
    //header("Location: home.php");
    $courseName = $_SESSION['course_name'];
   // exit;
}else{

    //illegal access
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
            <h1>Welcome <?php echo htmlspecialchars($userName); ?> [Instructor]</h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='manage_announcements.php'">Manage Announcements</button>
            <button onclick="location.href='internal_email.php'">Internal Communication</button>
        </div>

        <main class="main">
            <h2>Welcome To Instructor Dashboard</h2>
            <h3>Current Course: <?php echo htmlspecialchars($courseName); ?></h3>
            <p>This dashboard provides tools and features tailored for Instructors. Below are the functionalities offered:</p>
            
            <b>Features</b>
            <ul type="disc">
                <li><b>Manage Courses</b>
                    <ul type="disc">
                        <li>Add and remove students from the course roster.</li>
                        <li>Update course details such as schedule, syllabus, and resources.</li>
                    </ul>
                </li>
                <br>
                <li><b>Manage Student Groups</b>
                    <ul type="disc">
                        <li>Create and manage student groups for collaborative projects.</li>
                        <li>Upload a CSV file to automatically create groups for the course.</li>
                    </ul>
                </li>
                <br>
                <li><b>Manage FAQs</b>
                    <ul type="disc">
                        <li>Create, edit, and delete frequently asked questions (FAQs) related to the course.</li>
                        <li>Provide clear and concise answers to common queries from students.</li>
                    </ul>
                </li>
                <br>
                <li><b>Manage Announcements</b>
                    <ul type="disc">
                        <li>Create and manage announcements to communicate important information to students enrolled in the course.</li>
                        <li>Notify students about upcoming events, deadlines, or changes to course materials.</li>
                    </ul>
                </li>
                <br>
                <li><b>Internal Communication</b>
                    <ul type="disc">
                        <li>Send messages to other users, such as TAs, fellow instructors, or individual students.</li>
                        <li>Communicate efficiently with course staff and students regarding course-related matters.</li>
                    </ul>
                </li>
            </ul>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='choose_course.php'">Change Course</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
