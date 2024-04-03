<?php
// Start the session
session_start();

// Include your database connection file
require_once('../../database.php'); // Adjust the path as needed

// Check if user is logged in
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) { // Using course name now
    header("Location: choose-class.php"); // Redirect if no course name is selected
    exit;
}

// Initialize variables
$currentUserName = $_SESSION["user"]["Name"]; // Assuming this is stored in the session
$currentCourseName = $_SESSION["selectedCourseName"]; // Using course name from session

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
    <title>Student Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button>My Group (Internal Communication)</button>
            <button>Course Material</button>
        </div>

        <main class="main">
            <h2>Current Course: <?php echo htmlspecialchars($_SESSION["selectedCourseName"]); ?></h2>
        

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - <?php echo htmlspecialchars($currentCourseName); ?></title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <h3>FAQs</h3>
    <?php if (!empty($faqs)): ?>
    <table>
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
</body>
</html>

        
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>

