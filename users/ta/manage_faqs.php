<?php
session_start();
include_once '../../database.php';

if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selectedCourseId"])) {
    header("Location: ../../login.php");
    exit;
}

$selectedCourseId = $_SESSION["selectedCourseId"];

// Fetch existing FAQs
$query = "
    SELECT FAQID, Question, Answer, ContributorID
    FROM FAQ
    WHERE CourseID = :courseId
    ORDER BY FAQID;
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':courseId', $selectedCourseId, PDO::PARAM_INT);
$stmt->execute();
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage FAQs</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome TA <?php echo htmlspecialchars($userName); ?></h1>
            <p>You are signed in as a Teaching Assistant</p>
        </header>
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Course Details</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button class="is-selected" onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='internal_email.php'">Internal Email Communication </button>
        </div>
        <main class="main">
            <div class="table-wrapper">
                <h2>Course FAQs</h2>
                <?php if ($faqs): ?>
                    <table border="1">
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                        </tr>
                        <?php foreach ($faqs as $faq): ?>
                            <tr>
                                <td><?= htmlspecialchars($faq['FAQID']) ?></td>
                                <td><?= htmlspecialchars($faq['Question']) ?></td>
                                <td><?= htmlspecialchars($faq['Answer']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No FAQs found for this course.</p>
                <?php endif; ?>
            </div>
        
            <div class="table-wrapper">
                <h2>Add FAQ</h2>
                <form class="inline-form" action="add_faq.php" method="post">
                        <label for="question">Question:</label>
                        <input type="text" name="question" id="question" required>

                        <label for="answer">Answer:</label>
                        <textarea name="answer" id="answer" required></textarea>
                    <div>
                        <input class="button is-primary" type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </main>
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='choose_course.php'">Change Course</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
