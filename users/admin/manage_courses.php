<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../../css/home.css"> 
</head>
<body>
    <div class="header">
        <h1>Manage Courses</h1>
    </div>

    <div class="main-content">
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

    <div class="footer">
        <button onclick="location.href='../home.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
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
