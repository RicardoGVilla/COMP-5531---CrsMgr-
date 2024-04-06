<?php
session_start();
require_once '../../database.php';

// Fetch course data from the database
$query = "SELECT CourseID, Name FROM Course";
$stmt = $pdo->prepare($query);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Groups</title>
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
            <button class="is-selected" onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>
        
        <main class="main">
            <div class="main-header">
                <h2>Manage Groups</h2>
            </div>
            <!-- Add Group Form -->
            <div id="add-group" class="group-form">
                <h2>Add Group</h2>
                <form method="POST" action="add_group_endpoint.php"> <!-- Update action to your endpoint script -->
                    <select name="course_id" required>
                        <option value="">Select Course</option>
                        <!-- Populate with courses from the database -->
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['CourseID'] ?>"><?= $course['Name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <input type="number" name="group_leader_id" placeholder="Group Leader ID" required />
                    <input type="text" name="database_password" placeholder="Database Password" required />
                    <input type="number" name="max_size" placeholder="Max Size" required />
                    <button class="button is-primary" type="submit">Add Group</button>
                </form>
            </div>

            <!-- Update Group Form -->
            <div id="update-group" class="group-form" style="display: none;">
                <h2>Update Group</h2>
                <form method="POST" action="update_group_endpoint.php"> <!-- Update action to your endpoint script -->
                    <input type="number" name="group_id" placeholder="Group ID" required />
                    <select name="new_course_id">
                        <option value="">Select New Course (optional)</option>
                        <!-- Populate with courses from the database -->
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['CourseID'] ?>"><?= $course['Name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <input type="number" name="new_group_leader_id" placeholder="New Group Leader ID" />
                    <input type="text" name="new_database_password" placeholder="New Database Password" />
                    <input type="number" name="new_max_size" placeholder="New Max Size" />
                    <button type="submit">Update Group</button>
                </form>
            </div>

            <!-- Delete Group Form -->
            <div id="delete-group" class="group-form" style="display: none;">
                <h2>Delete Group</h2>
                <form method="POST" action="delete_group_endpoint.php"> <!-- Update action to your endpoint script -->
                    <input type="number" name="group_id" placeholder="Group ID" required />
                    <button type="submit">Delete Group</button>
                </form>
            </div>

            <div class="group-actions">
                <button class="button is-primary" onclick="showForm('add')">Add Group</button>
                <button class="button is-secondary" onclick="showForm('update')">Update Group</button>
                <button class="button is-delete"onclick="showForm('delete')">Delete Group</button>
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
            document.getElementById('add-group').style.display = 'none';
            document.getElementById('update-group').style.display = 'none';
            document.getElementById('delete-group').style.display = 'none';

            // Show the selected form
            document.getElementById(formId + '-group').style.display = 'block';
        }
    </script>
</body>
</html>
