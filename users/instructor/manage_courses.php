<?php
session_start();
require_once '../../database.php';

try {
    $query = "
    SELECT 
    c.CourseID, 
    c.Name, 
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
GROUP BY 
    c.CourseID, cs.SectionID, cs.SectionNumber, cs.StartDate, cs.EndDate
ORDER BY 
    c.CourseID, cs.SectionNumber;
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <style>
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="page">
        <header class="header">
            <h1>Welcome Instructor</h1>
        </header> 
    
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQSs</button>
        </div>

        <main class="main">
            <h2>Courses Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Class Section</th>
                        <th>Class Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course['CourseID']) ?></td>
                        <td><?= htmlspecialchars($course['Name']) ?></td>
                        <td><?= htmlspecialchars($course['StartDate']) ?></td>
                        <td><?= htmlspecialchars($course['EndDate']) ?></td>
                        <td><?= htmlspecialchars($course['SectionNumber']) ?></td>
                        <td><?= htmlspecialchars($course['ClassSize']) ?></td>
                        <td><button onclick="openModal(<?= $course['CourseID'] ?>, <?= $course['SectionID'] ?>)">Add Members</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Add Student</h3>
                    <form id="studentForm" method="post" action="edit_courses_endpoint.php">
                        <input type="hidden" name="action" value="enroll_student">
                        <input type="hidden" id="course_id" name="course_id" value="">
                        <input type="hidden" id="section_id" name="section_id" value="">
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" required><br><br>
                        <input type="submit" value="Enroll Student">
                    </form>
                </div>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    
    </div>

    <script>
        var modal = document.getElementById('myModal');

        function openModal(courseID, sectionID) {
            document.getElementById('course_id').value = courseID;
            document.getElementById('section_id').value = sectionID;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>