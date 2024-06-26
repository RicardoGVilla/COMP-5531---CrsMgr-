<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052
require_once '../../database.php'; 

// Initialize messages
$message = '';
$error = '';

// Connect to the database and fetch courses
try {
    $coursesQuery = "SELECT CourseID, Name FROM Course ORDER BY Name ASC";
    $stmt = $pdo->query($coursesQuery);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Function to fetch FAQs for a specific course
function getFaqsForCourse($pdo, $courseId) {
    try {
        $faqsQuery = "SELECT FAQID, Question, Answer FROM FAQ WHERE CourseID = :courseId ORDER BY FAQID ASC";
        $stmt = $pdo->prepare($faqsQuery);
        $stmt->execute([':courseId' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
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
            <h1>Welcome Admin</h1>
        </header>
        
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button class="is-selected" onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
            <button onclick="location.href='logs.php'">User Logs</button>
            <button onclick="location.href='internal_email.php'">Internal Communication</button>
        </div>

        <!-- Main Content -->
        <main class="main">
            <div class="main-header">
                <h2>Manage FAQs</h2>
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
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= htmlspecialchars($course['CourseID']) ?>">
                                    <?= htmlspecialchars($course['Name']) ?>
                                </option>
                            <?php endforeach; ?>
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
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= htmlspecialchars($course['CourseID']) ?>">
                                    <?= htmlspecialchars($course['Name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <textarea name="new_answer" placeholder="New Answer"></textarea>
                    <div>
                        <button class="button is-secondary" type="submit">Update FAQ</button>
                    </div>
                </form>
            </div>

            <!-- Delete FAQ Form -->
            <div id="delete-faq" class="faq-form table-wrapper" style="display: none;">
                <h2>Delete FAQ</h2>
                <form action="edit_faq_endpoint.php" method="post">
                    <input type="number" name="faq_id" placeholder="FAQ ID" required />
                    <button class="button is-delete" type="submit">Delete FAQ</button>
                </form>
            </div>


            <!-- Export FAQ Form -->
            <div id="export-faq" class="faq-form table-wrapper" style="display: none;">
                <h2>Export FAQ</h2>
                <form class="inline-form" action="edit_faq_endpoint.php" method="post">
                    <div class="input-body">
                        <input type="number" name="faq_id" placeholder="FAQ ID" required />
                        <select name="new_course_id">
                            <option value="">Select New Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= htmlspecialchars($course['CourseID']) ?>">
                                    <?= htmlspecialchars($course['Name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button class="button is-secondary" type="submit">Export FAQ</button>
                    </div>
                </form>
            </div>



            <div class="faq-actions">
                <button class="button is-primary"  onclick="showForm('add')">Add FAQ</button>
                <button class="button is-secondary" onclick="showForm('update')">Update FAQ</button>
                <button class="button is-delete"  onclick="showForm('delete')">Delete FAQ</button>
                <button class="button is-export"  onclick="showForm('export')">Export FAQ</button>
            </div>

            <br>
            <!-- Course and FAQs Overview -->
            <?php foreach ($courses as $course): ?>
                <div class="course-faq-section table-wrapper">
                    <h3><?= htmlspecialchars($course['Name']) ?></h3>
                    <?php 
                        $faqs = getFaqsForCourse($pdo, $course['CourseID']);
                        if (count($faqs) > 0):
                    ?>
                    <table class="content-table">
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
            <?php endforeach; ?>
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


