<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

// Start the session
session_start();

// Include your database connection file
require_once('../../database.php'); 

// Check if user is logged in
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); 
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) { 
    header("Location: choose-class.php"); 
    exit;
}

// Initialize variables
$currentUserName = $_SESSION["user"]["Name"];
$currentCourseName = $_SESSION["selectedCourseName"]; 

// Fetch Course ID using the course name
try {
    $stmt = $pdo->prepare("SELECT CourseID FROM Course WHERE Name = ?");
    $stmt->execute([$currentCourseName]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentCourseID = $course['CourseID'] ?? null;

    if (!$currentCourseID) {
        throw new Exception("Course not found.");
    }

    // Fetch FAQs for the found course ID
    $stmt = $pdo->prepare("SELECT Question, Answer FROM FAQ WHERE CourseID = ?");
    $stmt->execute([$currentCourseID]);
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - <?php echo htmlspecialchars($currentCourseName); ?></title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button class="is-selected" onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button onclick="location.href='manage_announcements.php'">Announcements</button>
            <button onclick="location.href='internal_email.php'">Internal Email Communication </button>
        </div>

        <main class="main">
            <h2>Current Course: <?php echo htmlspecialchars($_SESSION["selectedCourseName"]); ?></h2>
            <div class="table-wrapper">
                <h3>FAQs</h3>
                <?php if (!empty($faqs)): ?>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($faqs as $faq): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($faq['Question']); ?></td>
                                <td><?php echo htmlspecialchars($faq['Answer']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No FAQs available for this course.</p>
                <?php endif; ?>
            </div>

            <div class="table-wrapper">
                <h3>Add New FAQ</h3>
                <form class="inline-form" action="add_faq.php" method="post">
                    <div class="input-body">
                        <label for="question">Question:</label>
                        <input type="text" id="question" name="question" required>
                    </div>
                    <div>
                        <input class="button is-primary" type="submit" value="Submit FAQ">
                    </div>
                </form>
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

