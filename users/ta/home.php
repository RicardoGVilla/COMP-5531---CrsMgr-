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
            <h1>Welcome TA <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
            <p>You are signed in as a Teaching Assistant</p>
        </header>

        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Course Details</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='internal_email.php'">Internal Communication</button>
        </div>

        <main class="main">
            <h3>Welcome To TA Dashboard</h3>
            <h4>Current Class: <?php echo htmlspecialchars($_SESSION["selectedCourseName"]); ?></h4>
            
            <p>This dashboard provides tools and features tailored for Teaching Assistants. Below are the functionalities offered:</p>
            <b>Features</b>
            <ul type="disc">
                <li><b>Course Details</b>
                    <ul type="disc">
                        <li>View details from current course such as Course Code and  Section Number</li>
                    </ul>
                </li>
                <br>
                <li><b>Manage Student Groups</b>
                    <ul type="disc">
                        <li>Create and manage student groups for collaborative projects and discussions within courses.</li>
                    </ul>
                </li>
                <br>
                <li><b>FAQ Management</b>
                    <ul type="disc">
                        <li>Maintain a list of frequently asked questions (FAQs) by adding new questions and answers.</li>
                        <li>Help students by providing clear and concise answers to common queries.</li>
                    </ul>
                </li>
                <br>
                <li><b>Internal Communication</b>
                    <ul type="disc">
                        <li>Access internal messaging system to communicate with instructors, other TAs.</li>
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
