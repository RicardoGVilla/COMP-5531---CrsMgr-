<?php
// Start session and include database configuration
session_start();
require_once '../../database.php';

// all courses with their sections and instructors from the database
try {
    $query = "SELECT Course.CourseID, Course.Name AS CourseName, Course.StartDate, Course.EndDate, 
              GROUP_CONCAT(DISTINCT CourseSection.SectionNumber ORDER BY CourseSection.SectionNumber ASC SEPARATOR ', ') AS Sections,
              GROUP_CONCAT(DISTINCT User.Name ORDER BY User.Name ASC SEPARATOR ', ') AS Instructors
              FROM Course
              LEFT JOIN CourseSection ON Course.CourseID = CourseSection.CourseID
              LEFT JOIN CourseInstructor ON Course.CourseID = CourseInstructor.CourseID
              LEFT JOIN `User` ON CourseInstructor.InstructorID = User.UserID
              GROUP BY Course.CourseID";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../../css/index.css"> 
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header> 
       
        <div class="sidebar">
            <button onclick="location.href='manage_user.php'">Manage Users</button>
            <button class="is-selected" onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Courses</h2>
            </div>
            <!-- Add Course Form -->
            <div id="add-course" class="course-form">
                <h2>Add Course</h2>
                <form method="POST" action="edit_courses_endpoint.php">
                    <input type="hidden" name="action" value="add" />
                    <input type="text" name="course_name" placeholder="Course Name" required />
                    <input type="date" name="start_date" placeholder="Start Date" required />
                    <input type="date" name="end_date" placeholder="End Date" required />

                    <!-- Dropdown menu for selecting instructors -->
                    <select name="instructors" required>
                        <option value="" disabled selected>Select Instructor</option>
                        <?php foreach ($courses as $course): ?>
                            <?php if ($course['Instructors']): ?>
                                <?php $instructors = explode(', ', $course['Instructors']); ?>
                                <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?= htmlspecialchars($instructor) ?>">
                                        <?= htmlspecialchars($instructor) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <button class="button is-primary" type="submit">Add Course</button>
                </form>
            </div>

            <!-- Update Course Form -->
            <div id="update-course" class="course-form" style="display: none;">
                <h2>Update Course</h2>
                <form method="POST" action="edit_courses_endpoint.php">
                    <input type="hidden" name="action" value="update" />
                    
                    <!-- Dropdown menu for selecting the course to update -->
                    <select name="course_id" required>
                        <option value="" disabled selected>Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['CourseID']) ?>">
                                <?= htmlspecialchars($course['CourseName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="text" name="new_course_name" placeholder="New Course Name" />
                    <input type="date" name="new_start_date" placeholder="New Start Date" />
                    <input type="date" name="new_end_date" placeholder="New End Date" />
                    
                    <!-- Dropdown menu for selecting instructors -->
                    <select name="new_instructors" required>
                        <option value="" disabled selected>Select Instructor</option>
                        <?php foreach ($courses as $course): ?>
                            <?php if ($course['Instructors']): ?>
                                <?php $instructors = explode(', ', $course['Instructors']); ?>
                                <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?= htmlspecialchars($instructor) ?>">
                                        <?= htmlspecialchars($instructor) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    
                    <button class="button is-secondary" type="submit">Update Course</button>
                </form>
            </div>

            <!-- Delete Course Form -->
            <div id="delete-course" class="course-form" style="display: none;">
                <h2>Delete Course</h2>
                <form method="POST" action="edit_courses_endpoint.php">
                    <input type="hidden" name="action" value="delete" />
                    <input type="text" name="course_id" placeholder="Course ID" required />
                    <button type="submit">Delete Course</button>
                </form>
            </div>

            <div class="course-actions">
                <button class="button is-primary" onclick="showForm('add')">Add Course</button>
                <button class="button is-secondary" onclick="showForm('update')">Update Course</button>
            </div>

            <!-- Table to display courses with sections and instructors -->
            <div class="course-table">
                <h2>Current Courses</h2>
                <div class="table-wrapper">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Sections</th>
                                <th>Instructors</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= htmlspecialchars($course['CourseID']) ?></td>
                                    <td><?= htmlspecialchars($course['CourseName']) ?></td>
                                    <td><?= htmlspecialchars($course['StartDate']) ?></td>
                                    <td><?= htmlspecialchars($course['EndDate']) ?></td>
                                    <td><?= htmlspecialchars($course['Sections'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($course['Instructors'] ?: 'No instructors') ?></td>
                                    <td>
                                        <button class="button is-delete" onclick="confirmDelete(<?php echo $course['CourseID']; ?>)">Delete Course</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        function showForm(formId) {
            // Hide all forms
            document.getElementById('add-course').style.display = 'none';
            document.getElementById('update-course').style.display = 'none';
            document.getElementById('delete-course').style.display = 'none';

            // Show the selected form
            document.getElementById(formId + '-course').style.display = 'block';
        }

        function confirmDelete(courseId) {
            if (confirm("Are you sure you want to delete this course?")) {
                deleteCourse(courseId);
            }
        }

        function deleteCourse(courseId) {
            // Send an asynchronous request to edit_courses_endpoint.php with course ID to delete
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "edit_courses_endpoint.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.reload();
                }
            };
            xhr.send("action=delete&course_id=" + courseId);
        }
    </script>
</body>
</html>