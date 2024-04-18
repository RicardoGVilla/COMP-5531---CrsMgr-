<?php
session_start();
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php"); 
}

try {
    $query = "
    SELECT 
        c.CourseID, 
        c.Name AS CourseName,
        cs.SectionID, 
        cs.SectionNumber, 
        cs.StartDate, 
        cs.EndDate, 
        COUNT(se.StudentID) AS ClassSize,
        GROUP_CONCAT(DISTINCT u.Name ORDER BY u.Name ASC SEPARATOR ', ') AS TAs
    FROM 
        Course c
    JOIN 
        CourseSection cs ON c.CourseID = cs.CourseID
    LEFT JOIN 
        StudentEnrollment se ON cs.SectionID = se.SectionID
    LEFT JOIN 
        (SELECT u.Name, se.SectionID
         FROM `User` u
         JOIN UserRole ur ON u.UserID = ur.UserID
         JOIN Role r ON ur.RoleID = r.RoleID
         JOIN StudentEnrollment se ON u.UserID = se.StudentID
         WHERE r.RoleName = 'TA') AS u ON u.SectionID = cs.SectionID
    GROUP BY 
        c.CourseID, cs.SectionID, cs.SectionNumber, cs.StartDate, cs.EndDate
    ORDER BY 
        c.Name, cs.SectionNumber;
";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $courseSections = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Organize data by course for easier display
$courses = [];
foreach ($courseSections as $section) {
    $courses[$section['CourseID']]['CourseName'] = $section['CourseName'];
    $courses[$section['CourseID']]['Sections'][$section['SectionID']] = $section;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Courses Information</title>
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
        <button class="is-selected" onclick="location.href='enrolling_students.php'">Course Enrollment</button>
    </div>

    <main class="main">
        <h2>All Courses Information</h2>
        <?php foreach ($courses as $courseID => $course): ?>
            <div class="table-wrapper">
                <h3><?= htmlspecialchars($course['CourseName']) ?> (Course ID: <?= $courseID ?>)</h3>
                <?php foreach ($course['Sections'] as $section): ?>
                    <div>
                        <h4>Section <?= htmlspecialchars($section['SectionNumber']) ?></h4>
                        <p>Start Date: <?= htmlspecialchars($section['StartDate']) ?></p>
                        <p>End Date: <?= htmlspecialchars($section['EndDate']) ?></p>
                        <p>Class Size: <?= htmlspecialchars($section['ClassSize']) ?></p>
                        <p>Teaching Assistants: <?= htmlspecialchars($section['TAs'] ?? 'None') ?></p>
                        <?php if ($section['ClassSize'] > 0): ?>
                            <table class="content-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>There are no students enrolled in this section yet.</p>
                        <?php endif; ?>
                        <br>
                        <!-- Button to open modal for adding a student -->
                        <button class="button is-primary" onclick="openModal('<?= $section['SectionID'] ?>')">Add Student</button>
                        <button class="button is-primary" onclick="openModalTA('<?= $section['SectionID'] ?>')">Add TA</button>
                    </div>

                    <!-- Modal for adding TAs -->
                                        <div id="myModalTA<?= $section['SectionID'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModalTA('<?= $section['SectionID'] ?>')">&times;</span>
                            <h3>Add TA to Section <?= htmlspecialchars($section['SectionNumber']) ?></h3>
                            <form method="post" action="enroll_ta.php">
                                <input type="hidden" name="section_id" value="<?= $section['SectionID'] ?>">
                                <label for="ta_id">TA ID:</label>
                                <input type="text" id="ta_id" name="ta_id" required><br><br>
                                <input class="button is-primary" type="submit" value="Enroll TA">
                            </form>
                        </div>
                    </div>
    
                    <!-- Modal for adding students -->
                    <div id="myModal<?= $section['SectionID'] ?>" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal('<?= $section['SectionID'] ?>')">&times;</span>
                            <h3>Add Student to Section <?= htmlspecialchars($section['SectionNumber']) ?></h3>
                            <form method="post" action="enroll_student.php">
                                <input type="hidden" name="section_id" value="<?= $section['SectionID'] ?>">
                                <label for="student_id">Student ID:</label>
                                <input type="text" id="student_id" name="student_id" required><br><br>
                                <input class="button is-primary" type="submit" value="Enroll Student">
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <footer class="footer">
        <button onclick="location.href='home.php'">Home</button>
        <button onclick="location.href='../../logout.php'">Logout</button>
    </footer>
</div>

<script>
    function openModalTA(sectionID) {
    var modalTA = document.getElementById('myModalTA' + sectionID);
    modalTA.style.display = "block";
}

function closeModalTA(sectionID) {
    var modalTA = document.getElementById('myModalTA' + sectionID);
    modalTA.style.display = "none";
}


window.onclick = function(event) {
    // Check if the clicked area is a modal background
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";  
    }
}

</script>
</body>
</html>
