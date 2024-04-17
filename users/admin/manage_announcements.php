<?php
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

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Validate form data (ensure required fields are not empty)
    if (empty($course_id) || empty($title) || empty($content)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: manage_announcements.php");
        exit();
    }

    // Insert announcement into the database
    try {
        $query = "INSERT INTO Announcement (CourseID, Title, Content, AnnouncementDate) 
                  VALUES (:course_id, :title, :content, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $_SESSION['success'] = "Announcement posted successfully.";
        header("Location: manage_announcements.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error posting announcement: " . $e->getMessage();
        header("Location: manage_announcements.php");
        exit();
    }
}

// Fetch existing announcements associated with courses
try {
    $query = "SELECT AnnouncementID, Title, Content, AnnouncementDate, Course.Name AS CourseName 
              FROM Announcement 
              INNER JOIN Course ON Announcement.CourseID = Course.CourseID";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching announcements: " . $e->getMessage();
    $announcements = [];
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
            <button class="is-selected" onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
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
                    <textarea name="content" placeholder="Announcement Content" required></textarea>
                    <div>
                        <button class="button is-primary" type="submit">Post Announcement</button>
                    </div>
                </form>
            </div>

            <div class="announcement-list">
                <h2>Existing Announcements</h2>
                <?php if (empty($announcements)): ?>
                    <p>No announcements found.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($announcements as $announcement): ?>
                            <li>
                                <h3><?php echo $announcement['Title']; ?></h3>
                                <p><?php echo $announcement['Content']; ?></p>
                                <p><strong>Course:</strong> <?php echo $announcement['CourseName']; ?></p>
                                <p><strong>Date:</strong> <?php echo $announcement['AnnouncementDate']; ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
