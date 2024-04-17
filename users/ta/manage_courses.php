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
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f4f4f4; }
        h1, h2 { color: #333; }
        .content, form, .sidebar, header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 20px; }
        label, input, textarea, button { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #003975; }
        .email-item { margin-bottom: 20px; padding: 10px; background-color: #fff; border-left: 5px solid #0056b3; }
        .sidebar { width: 200px; float: left; margin-right: 20px; }
        .page { display: flex; justify-content: start; align-items: flex-start; }
        .main { flex-grow: 1; }
    </style>
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
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>

        <div class="main">
            <!-- Form to Send Email -->
            <div class="content">
                <h2>Send an Email</h2>
                <form action="send_email.php" method="post">
                    <input type="hidden" name="action" value="send_email">
                    <label for="recipients">Recipients (comma-separated IDs):</label>
                    <input type="text" id="recipients" name="recipients" required>
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                    <label for="body">Body:</label>
                    <textarea id="body" name="body" rows="6" required></textarea>
                    <button type="submit">Send Email</button>
                </form>
            </div>

            <!-- Buttons for Navigation -->
            <div class="content">
                <h2>Email Navigation</h2>
                <button onclick="window.location.href='email_system.php?action=view_inbox';">View Inbox</button>
                <button onclick="window.location.href='email_system.php?action=view_sent';">View Sent Emails</button>
            </div>
        </div>

        <!-- Container to Display Emails -->
        <div class="content" id="emailDisplay"></div>
    </div>
</body>
</html>
