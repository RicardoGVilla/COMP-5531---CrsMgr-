<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sections</title>
    <link rel="stylesheet" href="../../css/home.css"> 
</head>
<body>
    <div class="header">
        <h1>Manage Sections</h1>
    </div>

    <div class="main-content">
        <!-- Add Section Form -->
        <div id="add-section" class="section-form">
            <h2>Add Section</h2>
            <form method="POST" action="add_section_endpoint.php"> <!-- Update action to your endpoint script -->
                <select name="course_id" required>
                    <option value="">Select Course</option>
                    <!-- Populate with courses from the database -->
                    <option value="1">Introduction to Database Systems</option>
                    <option value="2">Advanced Web Development</option>
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
            <form method="POST" action="update_section_endpoint.php"> <!-- Update action to your endpoint script -->
                <input type="number" name="section_id" placeholder="Section ID" required />
                <select name="new_course_id">
                    <option value="">Select New Course (optional)</option>
                    <!-- Populate with courses from the database -->
                    <option value="1">Introduction to Database Systems</option>
                    <option value="2">Advanced Web Development</option>
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
            <form method="POST" action="delete_section_endpoint.php"> <!-- Update action to your endpoint script -->
                <input type="number" name="section_id" placeholder="Section ID" required />
                <button type="submit">Delete Section</button>
            </form>
        </div>

        <div class="section-actions">
            <button onclick="showForm('add')">Add Section</button>
            <button onclick="showForm('update')">Update Section</button>
            <button onclick="showForm('delete')">Delete Section</button>
        </div>
    </div>

    <div class="footer">
        <button onclick="location.href='../home.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
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
