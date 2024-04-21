<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881



session_start();
// Include database connection
include_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the user's name from session data
$userName = $_SESSION["user"]["Name"];

$userId = $_SESSION["user"]["UserID"];

// Prepare a SQL query to fetch courses taught by the logged-in instructor
$sql = "SELECT c.CourseID, c.Name 
        FROM Course c 
        INNER JOIN CourseInstructor ci ON c.CourseID = ci.CourseID 
        WHERE ci.InstructorID = :userId";

$stmt = $pdo->prepare($sql);
$stmt->execute(['userId' => $userId]);

// Fetch all courses taught by the instructor
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Course</title>
    <link rel="stylesheet" href="../../css/index.css">  
    <style>
    </style>
</head>
<body>
    <div class="hundredvh-container">
        <div class="table-wrapper">
            <div class="login-header">
                <h2>Welcome, <?php echo htmlspecialchars($userName); ?>!</h2>
            </div>
            <h3>Select Your Course</h3>
            <form class="inline-form"  action="home.php" method="post">
                <div class="form-group input-body">
                    <label for="courseSelect">Choose a course:</label>
                    <select name="course" id="courseSelect" class="form-control">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['CourseID']); ?>">
                                <?php echo htmlspecialchars($course['Name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Add a hidden input field to send the course ID -->
                <input type="hidden" name="courseId" value="<?php echo htmlspecialchars($course['CourseID']); ?>">
                <button class="button is-primary"  type="submit" class="form-button">Go to Course</button>
            </form>
        </div>

    </div>
</body>
</html>
