<?php
session_start();
require_once '../../database.php';

// Fetch all instructors from the database for the dropdown menu
try {
    // Fetch distinct instructors associated with a course
    $query = "SELECT u.UserID, u.Name, u.EmailAddress
    FROM `User` u
    JOIN UserRole ur ON u.UserID = ur.UserID
    JOIN Role r ON ur.RoleID = r.RoleID
    WHERE r.RoleName = 'Instructor';
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error'] = "Error fetching instructors: " . $e->getMessage();
    $instructors = []; 
}

// Fetch all courses with sections and instructors from the database for the table
try {
    $query = "SELECT c.CourseID, c.CourseCode, c.Name AS CourseName, c.StartDate, c.EndDate, 
    GROUP_CONCAT(DISTINCT cs.SectionNumber SEPARATOR ', ') AS Sections, 
    (SELECT GROUP_CONCAT(DISTINCT u.Name SEPARATOR ', ')
     FROM User u
     JOIN CourseInstructor ci ON u.UserID = ci.InstructorID
     WHERE ci.CourseID = c.CourseID
    ) AS Instructors
FROM Course c
LEFT JOIN CourseSection cs ON c.CourseID = cs.CourseID
GROUP BY c.CourseID;
";
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
            <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button class="is-selected" onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
            <button onclick="location.href='logs.php'">User Logs</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Courses</h2>
            </div>
            <!-- Add Course Form -->
            <div id="add-course" class="course-form table-wrapper">
                <h2>Add Course</h2>
                <form class="inline-form"  method="POST" action="edit_courses_endpoint.php"> 
                    <div class="label-input-body">
                        <input type="hidden" name="action" value="add" />
                        <div class="label-input">
                            <label for="course_name">Course Name:</label>
                            <input type="text" id="course_name" name="course_name" placeholder="Course Name" required />
                        </div>
                        <div class="label-input">
                            <label for="course_code">Course Code:</label>
                            <input type="text" id="course_code" name="course_code" placeholder="Course Code" required />
                        </div>
                        <div class="label-input">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" placeholder="Start Date" required />
                        </div>
                        <div class="label-input">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" placeholder="End Date" required />
                        </div>
                        <div class="label-input">
                            <label for="instructors">Instructors:</label>
                            <!-- Dropdown menu for selecting instructors -->
                            <select id="instructors" name="instructors" required>
                                <option value="" disabled selected>Select Instructor</option>
                                <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?= htmlspecialchars($instructor['UserID']) ?>">
                                        <?= htmlspecialchars($instructor['Name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
    
                    </div>
                    <div>
                        <button class="button is-primary" type="submit">Add Course</button>
                    </div>
                </form>


            
            </div>

            <!-- Update Course Form -->
            <div id="update-course" class="course-form table-wrapper" style="display: none;">
                <h2>Update Course</h2>
                <form class="inline-form" method="POST" action="edit_courses_endpoint.php">
                    <div class="label-input-body">
                        <input type="hidden" name="action" value="update" />
                        <div class="label-input">
                            <label for="course_id">Select Course:</label>
                            <!-- Dropdown menu for selecting the course to update -->
                            <select id="course_id" name="course_id" required>
                                <option value="" disabled selected>Select Course</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= htmlspecialchars($course['CourseID']) ?>">
                                        <?= htmlspecialchars($course['CourseName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="label-input">
                            <label for="new_course_name">New Course Name:</label>
                            <input type="text" id="new_course_name" name="new_course_name" placeholder="New Course Name" />
                        </div>
                        <div class="label-input">
                            <label for="new_start_date">New Start Date:</label>
                            <input type="date" name="new_start_date" placeholder="New Start Date" />
                        </div>
                        <div class="label-input">
                            <label for="new_end_date">New End Date:</label>
                            <input type="date" name="new_end_date" placeholder="New End Date" />
                        </div>
                        <div class="label-input">
                            <label for="new_instructors">New Instructor:</label>
                            <!-- Dropdown menu for selecting instructors -->
                            <select id="new_instructors" name="new_instructors" required>
                                <option value="" disabled selected>Select Instructor</option>
                                <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?= htmlspecialchars($instructor['UserID']) ?>">
                                        <?= htmlspecialchars($instructor['Name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button class="button is-secondary" type="submit">Update Course</button>
                    </div>
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
                                <th>Course Code</th>
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
                                    <td><?= htmlspecialchars($course['CourseCode']) ?></td>
                                    <td><?= htmlspecialchars($course['CourseName']) ?></td>
                                    <td><?= htmlspecialchars($course['StartDate']) ?></td>
                                    <td><?= htmlspecialchars($course['EndDate']) ?></td>
                                    <td><?= htmlspecialchars($course['Sections'] ?: 'No Section Assigned Yet') ?></td>
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
            <button onclick="location.href='home.php'">Home</button>
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
    <script>
    // Check if there is an error message in the session
    <?php if(isset($_SESSION['error'])): ?>
        // Display a pop-up window with the error message
        alert("<?php echo $_SESSION['error']; ?>");
        // Remove the error message from the session
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </script>

</body>
</html>
