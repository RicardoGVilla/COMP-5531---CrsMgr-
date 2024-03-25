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
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <div class="main">
            <div class="main-header">
                <h2>Manage Courses</h2>
            </div>
            <!-- Add Course Form -->
            <div id="add-course" class="course-form">
                <h2>Add Course</h2>
                <form method="POST" action="add_course_endpoint.php"> <!-- Update action to your endpoint script -->
                    <input type="text" name="course_name" placeholder="Course Name" required />
                    <input type="date" name="start_date" placeholder="Start Date" required />
                    <input type="date" name="end_date" placeholder="End Date" required />
                    <button type="submit">Add Course</button>
                </form>
            </div>

            <!-- Update Course Form -->
            <div id="update-course" class="course-form" style="display: none;">
                <h2>Update Course</h2>
                <form method="POST" action="update_course_endpoint.php"> <!-- Update action to your endpoint script -->
                    <input type="text" name="course_id" placeholder="Course ID" required />
                    <input type="text" name="new_course_name" placeholder="New Course Name" />
                    <input type="date" name="new_start_date" placeholder="New Start Date" />
                    <input type="date" name="new_end_date" placeholder="New End Date" />
                    <button type="submit">Update Course</button>
                </form>
            </div>

            <!-- Delete Course Form -->
            <div id="delete-course" class="course-form" style="display: none;">
                <h2>Delete Course</h2>
                <form method="POST" action="delete_course_endpoint.php"> <!-- Update action to your endpoint script -->
                    <input type="text" name="course_id" placeholder="Course ID" required />
                    <button type="submit">Delete Course</button>
                </form>
            </div>

            <div class="course-actions">
                <button onclick="showForm('add')">Add Course</button>
                <button onclick="showForm('update')">Update Course</button>
                <button onclick="showForm('delete')">Delete Course</button>
            </div>
        </div>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='logout.php'">Logout</button>
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
    </script>
</body>
</html>
