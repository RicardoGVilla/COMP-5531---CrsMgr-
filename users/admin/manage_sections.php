<?php
session_start();
require_once '../../database.php';

// Retrieve all courses from the database
$coursesQuery = "SELECT * FROM Course";
$coursesStmt = $pdo->query($coursesQuery);
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve all sections with associated course details
$query = "SELECT 
            CourseSection.SectionID, 
            Course.Name AS CourseName, 
            Course.StartDate, 
            Course.EndDate, 
            CourseSection.SectionNumber, 
            User.Name AS InstructorName 
          FROM CourseSection
          JOIN Course ON CourseSection.CourseID = Course.CourseID
          LEFT JOIN CourseInstructor ON Course.CourseID = CourseInstructor.CourseID
          LEFT JOIN `User` ON CourseInstructor.InstructorID = User.UserID
          ORDER BY Course.Name, CourseSection.SectionNumber ASC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Extract unique start dates and end dates
$startDates = array_unique(array_column($sections, 'StartDate'));
$endDates = array_unique(array_column($sections, 'EndDate'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sections</title>
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
            <button class="is-selected" onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Sections</h2>
            </div>
<!-- Add Section Form -->
<div id="add-section" class="section-form table-wrapper">
    <h2>Add Section</h2>
    <form class="inline-form" method="POST" action="edit_sections_endpoint.php">
        <input type="hidden" name="action" value="add">  <!-- Add this line -->
        <div class="label-input-body">
            <div class="label-input">
                <label for="course_id">Course Name:</label>
                <select id="course_id" name="course_id" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['CourseID'] ?>"><?= $course['Name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="label-input">
                <label for="section_number">Section Number:</label>
                <input type="text" id="section_number" name="section_number" placeholder="Section Number" required />
            </div>
            <div class="label-input">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" placeholder="Start Date" required />
            </div>
            <div class="label-input">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" placeholder="End Date" required />
            </div>
        </div>
        <div>
            <button class="button is-primary" type="submit">Add Section</button>
        </div>
    </form>
</div>



       <!-- Update Section Form -->
<div id="update-section" class="section-form table-wrapper" style="display: none;">
    <h2>Update Section</h2>
    <form class="inline-form" method="POST" action="edit_sections_endpoint.php">
        <div class="label-input-body">
            <input type="hidden" name="action" value="update" />
            <div class="label-input">
                <label for="section_id">Select Section:</label>
                <select id="section_id" name="section_id" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= htmlspecialchars($section['SectionID']) ?>">
                            <?= htmlspecialchars($section['CourseName'] . ' - ' . $section['SectionNumber']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="label-input">
                <label for="new_start_date">New Start Date:</label>
                <input type="date" id="new_start_date" name="new_start_date" placeholder="New Start Date" />
            </div>
            <div class="label-input">
                <label for="new_end_date">New End Date:</label>
                <input type="date" id="new_end_date" name="new_end_date" placeholder="New End Date" />
            </div>
        </div> 
        <div>
            <button class="button is-secondary" type="submit">Update Section</button>
        </div>
    </form>
</div>

            <!-- Delete Section Form -->
            <div id="delete-section" class="section-form" style="display: none;">
                <h2>Delete Section</h2>
                <form method="POST" action="edit_sections_endpoint.php">
                    <input type="hidden" name="action" value="delete" />
                    <input type="number" name="section_id" placeholder="Section ID" required />
                    <button type="submit">Delete Section</button>
                </form>
            </div>

            <div class="section-actions">
                <button class="button is-primary" onclick="showForm('add')">Add Section</button>
                <button class="button is-secondary" onclick="showForm('update')">Update Section</button>
            </div>

            <div class="course-table">
                <h2>Current Sections</h2>
                <div class="table-wrapper">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Section ID</th>
                                <th>Course Name</th>
                                <th>Section Number</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Instructor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $section): ?>
                            <tr>
                                <td><?= htmlspecialchars($section['SectionID']) ?></td>
                                <td><?= htmlspecialchars($section['CourseName']) ?></td>
                                <td><?= htmlspecialchars($section['SectionNumber']) ?></td>
                                <td><?= htmlspecialchars($section['StartDate']) ?></td>
                                <td><?= htmlspecialchars($section['EndDate']) ?></td>
                                <td><?= htmlspecialchars($section['InstructorName'] ?: 'No instructor') ?></td>
                                <td>
                                    <button class="button is-delete" onclick="confirmDelete(<?php echo $section['SectionID']; ?>)">Delete Section</button>
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
            document.getElementById('add-section').style.display = 'none';
            document.getElementById('update-section').style.display = 'none';
            document.getElementById('delete-section').style.display = 'none';

            // Show the selected form
            document.getElementById(formId + '-section').style.display = 'block';
        }
        function confirmDelete(sectionId) {
            if (confirm("Are you sure you want to delete this section?")) {
                deleteSection(sectionId);
            }
        }

        function deleteSection(sectionId) {
            // Send an asynchronous request to edit_sections_endpoint.php with section ID to delete
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "edit_sections_endpoint.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.reload();
                }
            };
            xhr.send("action=delete&section_id=" + sectionId);
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
