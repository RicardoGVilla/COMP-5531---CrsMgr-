<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Sections</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .table-container {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .table-container th, .table-container td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header>

        <div class="sidebar">
        <<button onclick="location.href='manage_user.php'">Manage Users</button>
            <button class="is-selected" onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <main class="main">
            <div class="form-container">
                <h2>Add Course Section</h2>
                <form id="addSectionForm">
                    <input type="hidden" name="action" value="add">
                    <label for="courseID">Course ID:</label>
                    <input type="text" id="courseID" name="courseID" required>
                    <label for="sectionNumber">Section Number:</label>
                    <input type="text" id="sectionNumber" name="sectionNumber" required>
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" required>
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate" required>
                    <button type="button" onclick="submitForm('addSectionForm');">Add Section</button>
                </form>
            </div>

            <div class="form-container">
                <h2>Update/Delete Course Section</h2>
                <form id="updateSectionForm">
                    <input type="hidden" name="action" value="update">
                    <label for="sectionID">Section ID:</label>
                    <input type="text" id="sectionID" name="sectionID" required>
                    <label for="newSectionNumber">New Section Number:</label>
                    <input type="text" id="newSectionNumber" name="newSectionNumber">
                    <label for="newStartDate">New Start Date:</label>
                    <input type="date" id="newStartDate" name="newStartDate">
                    <label for="newEndDate">New End Date:</label>
                    <input type="date" id="newEndDate" name="newEndDate">
                    <button type="button" onclick="submitForm('updateSectionForm');">Update Section</button>
                    <button type="button" onclick="submitForm('updateSectionForm', 'delete');">Delete Section</button>
                </form>
            </div>

            <div class="table-container">
    <h2>Current Course Sections</h2>
    <table>
        <thead>
            <tr>
                <th>Section ID</th>
                <th>Section Number</th>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once '../../database.php';  

            try {
                $sql = 'SELECT cs.SectionID, cs.SectionNumber, cs.CourseID, c.Name AS CourseName, cs.StartDate, cs.EndDate 
                        FROM CourseSection cs 
                        JOIN Course c ON cs.CourseID = c.CourseID';
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['SectionID']}</td>
                            <td>{$row['SectionNumber']}</td>
                            <td>{$row['CourseID']}</td>
                            <td>{$row['CourseName']}</td>
                            <td>{$row['StartDate']}</td>
                            <td>{$row['EndDate']}</td>
                          </tr>";
                }
            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
</div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </footer>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    function submitForm(formId, action = 'update') {
        var data = $('#' + formId).serialize();
        if (action === 'delete') {
            data += '&action=delete';
        }
        $.ajax({
            url: 'edit_sections_endpoint.php',
            type: 'post',
            data: data,
            success: function(response) {
                alert(response);
                // Consider refreshing the table or parts of the page here if needed
            },
            error: function() {
                alert("An error occurred while processing your request.");
            }
        });
    }
    </script>
</body>
</html>
