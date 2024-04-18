<?php
// Start the session
session_start();

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>
    <div class="page">
        <header class="header">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?> [Admin]</h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
            <button onclick="location.href='logs.php'">User Logs</button>
        </div>

        <main class="main">
        <h3>Welcome To Admin Dashboard</h3>
        <p>This dashboard will help you manage various aspects of the system. Below are the features offered:</p>
        <b>Features</b>
        <ul type="disc">
            <li><b>Manage Users</b>
                <ul type="disc">
                    <li>Add new users, update existing user information, and delete user accounts if necessary.</li>
                </ul>
            </li>
            <br>
            <li><b>Manage Roles</b>
                <ul type="disc">
                    <li>Create new roles and assign them to users, or remove roles from users who no longer require them.</li>
                </ul>
            </li>
            <br>
            <li><b>Manage Courses</b>
                <ul type="disc">
                    <li>Create new courses, update course details such as Course Name, Start Date, End Date, and Instructor.</li>
                </ul>
            </li>
            <br>
            <li><b>Manage Sections</b>
                <ul type="disc">
                    <li>Add new sections to courses, update section details such as Section ID, Section Number, Start Date, and End Date.</li>
                    <li>Remove sections if necessary.</li>
                </ul>
            </li>
            <br>
            <li><b>Manage Groups</b>
                <ul type="disc">
                    <li>Upload a CSV file containing group information to automatically create groups for different courses, facilitating collaborative work.</li>
                </ul>
            </li>
            <br>
            <li><b>Course Announcements</b>
                <ul type="disc">
                    <li>Create new announcements to communicate important information to students enrolled in courses.</li>
                </ul>
            </li>
            <br>
            <li><b>FAQ Management</b>
                <ul type="disc">
                    <li>Maintain a list of frequently asked questions (FAQs) by adding new questions and answers, updating existing ones, and removing outdated information.</li>
                </ul>
            </li>
            <br>
            <li><b>Course Enrollment</b>
                <ul type="disc">
                    <li>Access information about all available courses, including enrollment status and capacity, and enroll students in courses based on their academic requirements and preferences.</li>
                </ul>
            </li>
            <br>
            <li><b>Change Password</b>
                <ul type="disc">
                    <li>Allow users to change their passwords securely to maintain the integrity of their accounts.</li>
                </ul>
            </li>
            <br>
            <li><b>Change Email</b>
                <ul type="disc">
                    <li>Update users' email addresses as needed to ensure effective communication and account security.</li>
                </ul>
            </li>
        </ul>

        </main>
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
