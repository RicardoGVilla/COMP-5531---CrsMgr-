<?php
session_start(); 
require_once '../../database.php'; 
$message = '';
$error = '';

// Check if a course is selected
$selectedCourseId = isset($_SESSION['selected_course_id']) ? $_SESSION['selected_course_id'] : null;
if (!$selectedCourseId) {
    $error = "No course selected.";
} else {
    // Connect to the database and fetch course details
    try {
        $courseQuery = "SELECT CourseID, Name FROM Course WHERE CourseID = :courseId";
        $stmt = $pdo->prepare($courseQuery);
        $stmt->execute([':courseId' => $selectedCourseId]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }

    // Fetch FAQs for the selected course
    try {
        $faqsQuery = "SELECT FAQID, Question, Answer FROM FAQ WHERE CourseID = :courseId ORDER BY FAQID ASC";
        $stmt = $pdo->prepare($faqsQuery);
        $stmt->execute([':courseId' => $selectedCourseId]);
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $faqs = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Instructor</h1>
        </header>

        <!-- Sidebar Navigation -->
        <div class="sidebar">
        <button class="is-selected" onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQSs</button>
        </div>

        <!-- Main Content -->
        <main class="main">
            <div class="main-header">
                <h2>Manage FAQs for <?= htmlspecialchars($course['Name'] ?? 'Selected Course') ?></h2>
                <?php if ($message): ?>
                    <div class="alert alert-success">
                        <?= $message ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add FAQ Form -->
            <div id="add-faq" class="faq-form table-wrapper">
                <h2>Add New FAQ</h2>
                <form class="inline-form" action="edit_faq_endpoint.php" method="post">
                    <div class="input-body">
                        <input type="text" name="question" placeholder="FAQ Question" required />
                        <select name="course_id">
                            <!-- Displaying only the selected course in dropdown -->
                            <option value="<?= htmlspecialchars($selectedCourseId) ?>" selected>
                                <?= htmlspecialchars($course['Name']) ?>
                            </option>
                        </select>
                    </div>
                    <textarea name="answer" placeholder="FAQ Answer" rows="7" required></textarea>
                    <div>
                        <button class="button is-primary" type="submit">Add FAQ</button>
                    </div>
                </form>
            </div>

            <!-- Update FAQ Form -->
            <div id="update-faq" class="faq-form table-wrapper" style="display: none;">
                <h2>Update FAQ</h2>
                <form class="inline-form" action="edit_faq_endpoint.php" method="post">
                    <div class="input-body">
                        <input type="number" name="faq_id" placeholder="FAQ ID" required />
                        <input type="text" name="new_question" placeholder="New Question" />
                        <select name="new_course_id">
                            <!-- Displaying only the selected course in dropdown -->
                            <option value="<?= htmlspecialchars($selectedCourseId) ?>" selected>
                                <?= htmlspecialchars($course['Name']) ?>
                            </option>
                        </select>
                    </div>
                    <textarea name="new_answer" placeholder="New Answer"></textarea>
                    <div>
                        <button class="button is-secondary" type="submit">Update FAQ</button>
                    </div>
                </form>
            </div>

            <!-- Delete FAQ Form -->
            <div id="delete-faq" class="faq-form" style="display: none;">
                <h2>Delete FAQ</h2>
                <form action="edit_faq_endpoint.php" method="post">
                    <input type="number" name="faq_id" placeholder="FAQ ID" required />
                    <button class="button is-delete" type="submit">Delete FAQ</button>
                </form>
            </div>

            <div class="faq-actions">
                <button class="button is-primary"  onclick="showForm('add')">Add FAQ</button>
                <button class="button is-secondary" onclick="showForm('update')">Update FAQ</button>
                <button class="button is-delete"  onclick="showForm('delete')">Delete FAQ</button>
            </div>

            <!-- Course and FAQs Overview -->
            <div class="course-faq-section">
                <?php if (!empty($faqs)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>FAQ ID</th>
                                <th>Question</th>
                                <th>Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faqs as $faq): ?>
                                <tr>
                                    <td><?= htmlspecialchars($faq['FAQID']) ?></td>
                                    <td><?= htmlspecialchars($faq['Question']) ?></td>
                                    <td><?= htmlspecialchars($faq['Answer']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No FAQs found for this course.</p>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        function showForm(formId) {
            var forms = document.querySelectorAll('.faq-form');
            forms.forEach(function(form) {
                form.style.display = 'none';
            });
            document.getElementById(formId + '-faq').style.display = 'block';
        }
    </script>
</body>
</html>
