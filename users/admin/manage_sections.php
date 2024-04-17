<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Sections</title>
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
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Sections</h2>
            </div>

            <!-- Add Course Form -->
            <div class="table-wrapper">
                <h2>Add Course Section</h2>
                <form id="addSectionForm" class="inline-form">
                    <div class="label-input-body">
                        <input type="hidden" name="action" value="add">
                        <div class="label-input">
                            <label for="courseID">Course ID:</label>
                            <input type="text" id="courseID" name="courseID" required>
                        </div>
                        <div class="label-input">
                            <label for="sectionNumber">Section Number:</label>
                            <input type="text" id="sectionNumber" name="sectionNumber" required>
                        </div>
                        <div class="label-input">
                            <label for="startDate">Start Date:</label>
                            <input type="date" id="startDate" name="startDate" required>
                        </div>
                        <div class="label-input">
                            <label for="endDate">End Date:</label>
                            <input type="date" id="endDate" name="endDate" required>
                        </div>
                    </div>
                    <div>
                        <button class="button is-primary" type="button" onclick="submitForm('addSectionForm');">Add Section</button>
                    </div>
                </form>
            </div>

            <!-- Update Course Form -->
            <div class="table-wrapper">
                <h2>Update/Delete Course Section</h2>
                <form class="inline-form" id="updateSectionForm">
                    <div class="label-input-body">
                        <input type="hidden" name="action" value="update">
                        <div class="label-input">
                            <label for="sectionID">Section ID:</label>
                            <input type="text" id="sectionID" name="sectionID" required>
                        </div>
                        <div class="label-input">
                            <label for="newSectionNumber">New Section Number:</label>
                            <input type="text" id="newSectionNumber" name="newSectionNumber">
                        </div>
                        <div class="label-input">
                            <label for="newStartDate">New Start Date:</label>
                            <input type="date" id="newStartDate" name="newStartDate">
                        </div>
                        <div class="label-input">
                            <label for="newEndDate">New End Date:</label>
                            <input type="date" id="newEndDate" name="newEndDate">
                        </div>
                    </div>
                    <div>
                        <button class="button is-secondary" type="button" onclick="submitForm('updateSectionForm');">Update Section</button>
                        <button class="button is-delete" type="button" onclick="submitForm('updateSectionForm', 'delete');">Delete Section</button>
                    </div>
                </form>
            </div>

            <!-- Table to display courses sections -->
            <div class="table-container">
                <h2>Current Course Sections</h2>
                <div class="table-wrapper">
                    <table class="content-table">
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
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
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
