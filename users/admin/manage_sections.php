<?php
session_start();
require_once '../../database.php';


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

$_currentSections = array_unique(array_map(function ($value) {
    return  $value['CourseName'];
}, $sections));


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

            <div id="add-section" class="section-form">
                <h2>Add Section</h2>
                <form method="POST" action="edit_sections_endpoint.php"> 
                    <select name="course_id" required>
                    <?php
                        print_r($_currentSections);
                        ?>
                    <?php foreach ($_currentSections as $row): ?>
                            <option> <?=$row?> </option>
                        <?php endforeach ?>
                    </select>
                    <input type="number" name="section_number" placeholder="Section Number" required />
                    <input type="date" name="start_date" placeholder="Start Date" required />
                    <input type="date" name="end_date" placeholder="End Date" required />
                    <button type="submit">Add Section</button>
                </form>
            </div>

            <!-- Update Section Form -->
            <div id="update-section" class="section-form" style="display: none;">
                <h2>Update Section</h2>
                <form method="POST" action="edit_sections_endpoint.php"> 
                    <input type="hidden" name="action" value="update" />
                    <input type="number" name="section_id" placeholder="Section ID" required />
                    <select name="new_course_id">
                        <option value="">Select New Course (optional)</option>
                    </select>
                    <input type="number" name="new_section_number" placeholder="New Section Number" />
                    <input type="date" name="new_start_date" placeholder="New Start Date" />
                    <input type="date" name="new_end_date" placeholder="New End Date" />
                    <button type="submit">Update Section</button>
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
                <button onclick="showForm('add')">Add Section</button>
                <button onclick="showForm('update')">Update Section</button>
                <button onclick="showForm('delete')">Delete Section</button>
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
    </script>
</body>
</html>