<?php
session_start();
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selected_course_id"])) {
    header("Location: login.php"); // Redirect to login page if not logged in or course not selected
    exit;
}

// Get the selected course ID from the session
$selectedCourseId = $_SESSION["selected_course_id"];

try {
    // Retrieve information about the selected course and its sections
    $query = "
        SELECT 
            c.CourseID, 
            c.Name AS CourseName,
            cs.SectionID, 
            cs.SectionNumber, 
            cs.StartDate, 
            cs.EndDate, 
            COUNT(se.StudentID) AS ClassSize
        FROM 
            Course c
        JOIN 
            CourseSection cs ON c.CourseID = cs.CourseID
        LEFT JOIN 
            StudentEnrollment se ON cs.SectionID = se.SectionID
        WHERE 
            c.CourseID = :courseId
        GROUP BY 
            c.CourseID, cs.SectionID, cs.SectionNumber, cs.StartDate, cs.EndDate
        ORDER BY 
            cs.SectionNumber;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['courseId' => $selectedCourseId]);
    $courseSections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the course name and ID
    $courseName = $courseSections[0]['CourseName']; 
    $courseID = $courseSections[0]['CourseID']; 
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses Information</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Instructor</h1>
        </header> 
        
        <div class="sidebar">
            <button class="is-selected" onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='manage_announcements.php'">Manage Announcements</button>
        </div>

        <main class="main">
            <h2>Course Information</h2>
            <?php foreach ($courseSections as $section): ?>
                <div class="table-wrapper">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Course ID</th>
                                <th>Section</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Class Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= htmlspecialchars($courseName) ?></td> 
                                <td><?= htmlspecialchars($courseID) ?></td> 
                                <td><?= htmlspecialchars($section['SectionNumber']) ?></td> 
                                <td><?= htmlspecialchars($section['StartDate']) ?></td>
                                <td><?= htmlspecialchars($section['EndDate']) ?></td>
                                <td><?= htmlspecialchars($section['ClassSize']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- Close the table-wrapper -->
                <?php if ($section['ClassSize'] > 0): ?>
                <div class="table-wrapper">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Fetch student details for this section
                            $sql = "SELECT u.UserID, u.Name, u.EmailAddress
                                    FROM StudentEnrollment se
                                    JOIN `User` u ON se.StudentID = u.UserID
                                    WHERE se.SectionID = :sectionId";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['sectionId' => $section['SectionID']]);
                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['UserID']) ?></td>
                                    <td><?= htmlspecialchars($student['Name']) ?></td>
                                    <td><?= htmlspecialchars($student['EmailAddress']) ?></td>
                                    <td><button class="button is-delete" onclick="removeStudent(<?= $section['SectionID'] ?>, <?= $student['UserID'] ?>)">Remove Student</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                    <button class="button is-primary" onclick="openModal(<?= $section['SectionID'] ?>)">Add Student</button>
                    <!-- Modal -->
                    <div id="myModal<?= $section['SectionID'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal(<?= $section['SectionID'] ?>)">&times;</span>
                            <h3>Add Student</h3>
                            <form id="studentForm<?= $section['SectionID'] ?>" onsubmit="enrollStudent(event, <?= $section['SectionID'] ?>)" method="post" action="edit_courses_endpoint.php">
                                <input type="hidden" name="action" value="enroll_student">
                                <input type="hidden" name="course_id" value="<?= $courseID ?>">
                                <input type="hidden" name="section_id" value="<?= $section['SectionID'] ?>">
                                <label for="student_id<?= $section['SectionID'] ?>">Student ID:</label>
                                <input type="text" id="student_id<?= $section['SectionID'] ?>" name="student_id" required><br><br>
                                <input class="button is-primary" type="submit" value="Enroll Student">
                            </form>
                        </div>
                    </div>
                </div> <!-- Close the table-wrapper -->
                <?php else: ?>
                    <p>There are no students yet.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        </main>

        <footer class="footer">
            <button onclick="location.href='choose_course.php'">Change Course</button>
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        // Function to enroll a student asynchronously
        function enrollStudent(event, sectionID) {
            event.preventDefault(); // Prevent default form submission

            var form = document.getElementById('studentForm' + sectionID);
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'edit_courses_endpoint.php');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    alert(response); // Show the response message
                    // Optionally, you can update the page content dynamically here
                    window.location.reload();
                } else {
                    alert('Error: ' + xhr.statusText);
                }
            };
            xhr.onerror = function() {
                alert('Request failed.');
            };
            xhr.send(formData);
        }

        // Modal functions
        function openModal(sectionID) {
            var modal = document.getElementById('myModal' + sectionID);
            modal.style.display = "block";
        }

        function closeModal(sectionID) {
            var modal = document.getElementById('myModal' + sectionID);
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                var modal = modals[i];
                if (event.target == modal) {
                    var sectionID = modal.id.replace('myModal', '');
                    closeModal(sectionID);
                }
            }
        }
        function removeStudent(sectionID, studentID) {
            var confirmation = confirm("Are you sure you want to remove this student?");
            if (confirmation) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'edit_courses_endpoint.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        alert(response); // Show the response message
                        // Optionally, you can update the page content dynamically here
                        window.location.reload();
                    } else {
                        alert('Error: ' + xhr.statusText);
                    }
                };
                xhr.onerror = function() {
                    alert('Request failed.');
                };
                xhr.send('action=remove_student&section_id=' + sectionID + '&student_id=' + studentID);
            }
        }
    </script>

</body>
</html>