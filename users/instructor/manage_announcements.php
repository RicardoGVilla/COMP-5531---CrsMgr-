<?php
session_start();
require_once '../../database.php';

$selectedCourseId = $_SESSION["selected_course_id"] ?? null;

if (!$selectedCourseId) {
    $_SESSION['error'] = "No course selected.";
    header("Location: some_other_page.php"); // Redirect if no course selected
    exit();
}

// Fetch course name for the selected course
try {
    $query = "SELECT CourseID, Name FROM Course WHERE CourseID = :courseId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['courseId' => $selectedCourseId]);
    $selectedCourse = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching course: " . $e->getMessage();
    header("Location: manage_announcements.php");
    exit();
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: manage_announcements.php");
        exit();
    }

    try {
        $query = "INSERT INTO Announcement (CourseID, Title, Content, AnnouncementDate) 
                  VALUES (:course_id, :title, :content, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':course_id', $selectedCourseId);
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

// Fetch existing announcements for the selected course
try {
    $query = "SELECT AnnouncementID, Title, Content, AnnouncementDate, Course.Name AS CourseName 
              FROM Announcement 
              INNER JOIN Course ON Announcement.CourseID = Course.CourseID
              WHERE Announcement.CourseID = :courseId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['courseId' => $selectedCourseId]);
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
            <h1>Welcome Instructor </h1>
        </header> 
    
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQSs</button>
            <button onclick="location.href='manage_announcements.php'">Manage Announcements</button>
            <button onclick="location.href='internal_emails.php'">Email</button>

        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Course Announcements</h2>
            </div>
            <div id="add-announcement" class="announcement-form table-wrapper">
    <h2>Add New Announcement for <?= htmlspecialchars($selectedCourse['Name']) ?></h2>
    <form class="inline-form" action="post_announcement.php" method="post"> 
        <div class="input-body">
            <input type="hidden" name="course_id" value="<?= htmlspecialchars($selectedCourseId) ?>">
            <input type="text" name="title" placeholder="Announcement Title" required />
        </div>
        <textarea name="content" placeholder="Announcement Content" rows="7" required></textarea>
        <div>
            <button class="button is-primary" type="submit">Post Announcement</button>
        </div>
    </form>
</div>


            <div class="announcement-list table-wrapper">
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
                            <br>
                            <hr>
                            <br>
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
