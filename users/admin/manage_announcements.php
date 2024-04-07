<?php
// Start session and include database configuration
session_start();
require_once '../../database.php';

// Fetch course names from the database
try {
    $query = "SELECT CourseID, Name FROM Course";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching courses: " . $e->getMessage();
    $courses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Announcements</title>
    <link rel="stylesheet" href="../../css/index.css"> 
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header> 
    
        <div class="sidebar">
            <button onclick="location.href='manage_user.php'">Manage Users</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button class="is-selected" onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Course Announcements</h2>
            </div>
            <div id="add-announcement" class="announcement-form table-wrapper">
                <h2>Add New Announcement</h2>
                <form class="inline-form" action="post_announcement.php" method="post"> 
                    <div class="input-body">
                        <select name="course_id" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['CourseID']; ?>"><?php echo $course['Name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="title" placeholder="Announcement Title" required />
                    </div>
                    <div class="input">
                        <textarea name="content" placeholder="Announcement Content" required></textarea>
                    </div>
                    <div>
                        <button class="button is-primary" type="submit">Post Announcement</button>
                    </div>
                </form>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
