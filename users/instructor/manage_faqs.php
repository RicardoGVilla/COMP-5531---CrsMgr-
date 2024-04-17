<?php
session_start();
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selected_course_id"])) {
    header("Location: login.php"); // Redirect to login page if not logged in or course not selected
    exit;
}

// Get the selected course ID from the session
$selectedCourseId = $_SESSION["selected_course_id"];

// Retrieve FAQs for the selected course
try {
    $stmt = $pdo->prepare("SELECT FAQID, Question, Answer FROM FAQ WHERE CourseID = :courseId");
    $stmt->execute(['courseId' => $selectedCourseId]);
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT Name FROM Course WHERE CourseID = :courseId");
    $stmt->execute(['courseId' => $selectedCourseId]);
    $courseName = $stmt->fetch(PDO::FETCH_ASSOC)['Name'];
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
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
        <h1>Welcome Instructor</h1>
    </header>

    <div class="sidebar">
        <button onclick="location.href='manage_courses.php'">Manage Courses</button>
        <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
        <button onclick="location.href='manage_faqs.php'" >Manage FAQs</button>
    </div>

    <main class="main">
        <h2>Manage FAQs for <?php echo htmlspecialchars($courseName); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($faq['Question']); ?></td>
                        <td><?php echo htmlspecialchars($faq['Answer']); ?></td>
                        <td>
                            <button onclick="editFAQ(<?php echo $faq['FAQID']; ?>)">Edit</button>
                            <button onclick="deleteFAQ(<?php echo $faq['FAQID']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <form action="edit_faq_endpoint.php" method="post">
                        <td><input type="text" name="question" placeholder="New question"></td>
                        <td><input type="text" name="answer" placeholder="New answer"></td>
                        <td><button type="submit" name="action" value="add">Add FAQ</button></td>
                    </form>
                </tr>
            </tbody>
        </table>
    </main>

    <footer class="footer">
        <button onclick="location.href='home.php'">Home</button>
        <button onclick="location.href='../../logout.php'">Logout</button>
    </footer>
</div>


    <script>
        function editFAQ(faqID) {
            var questionText = document.getElementById('questionText' + faqID);
            var answerText = document.getElementById('answerText' + faqID);
            var editControls = document.getElementById('editControls' + faqID);

            questionText.style.display = 'none';
            answerText.style.display = 'none';
            editControls.style.display = 'block';
        }

        function deleteFAQ(faqID) {
            if (confirm('Are you sure you want to delete this FAQ?')) {
                var form = document.createElement('form');
                document.body.appendChild(form);
                form.method = 'post';
                form.action = 'edit_faq_endpoint.php';
                var inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = 'delete';
                form.appendChild(inputAction);
                var inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'faq_id';
                inputId.value = faqID;
                form.appendChild(inputId);
                form.submit();
            }
        }
    </script>

</body>
</html>
