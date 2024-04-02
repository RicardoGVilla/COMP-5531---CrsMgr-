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
    <title>Student Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>

        <div class="sidebar">
            <button>Contact Information</button>
            <button>FAQ</button>
            <button>My Group (Internal Communication)</button>
            <button>Course Material</button>
        </div>

        <main class="main">
            <h2>Current Course: <?php echo htmlspecialchars($_SESSION["selectedCourseName"]); ?></h2>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
