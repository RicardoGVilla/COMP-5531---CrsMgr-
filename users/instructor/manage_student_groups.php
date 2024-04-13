<?php
session_start();
require_once '../../database.php'; 

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

$courseCode = $section = $groupLeaderId = '';
$studentIds = [];
$randomPassword = generateRandomPassword();
$currentInstructorId = $_SESSION['user']['UserID'] ?? null;
$selectedCourseId = $_SESSION["selected_course_id"] ?? null;

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["studentGroupFile"])) {
    $filename = $_FILES["studentGroupFile"]["tmp_name"];

    if (($handle = fopen($filename, "r")) !== FALSE) {
        $firstLine = fgets($handle);
        if (preg_match("/(COMP\s*\d{4})\/\d+\s*([A-Z]+)/", $firstLine, $matches)) {
            $courseCode = str_replace(" ", "", $matches[1]);
            $section = $matches[2];
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (empty(array_filter($data, 'strlen'))) {
                continue;
            }
            if ($data[0] === 'Student ID') {
                $groupLeaderData = fgetcsv($handle, 1000, ",");
                $groupLeaderId = $groupLeaderData[0];
                $studentIds[] = $groupLeaderId;
                
                while (($studentData = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty(array_filter($studentData, 'strlen'))) {
                        continue;
                    }
                    $studentIds[] = $studentData[0];
                }
                break;
            }
        }
        
        fclose($handle);
    }

    if (!empty($courseCode) && !empty($studentIds)) {
        // Find the course ID by the course code
        $stmt = $pdo->prepare("SELECT CourseID FROM Course WHERE CourseCode = ?");
        $stmt->execute([$courseCode]);
        $courseId = $stmt->fetchColumn();
        
        if ($courseId) {
            if ($courseId !== $selectedCourseId) {
                $errorMsg = "The uploaded file does not correspond to the selected course.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES (?, ?, ?, ?)");
                $stmt->execute([$courseId, $groupLeaderId, $randomPassword, count($studentIds)]);
                $newGroupId = $pdo->lastInsertId();

                foreach ($studentIds as $studentId) {
                    $stmt = $pdo->prepare("INSERT INTO StudentGroupMembership (StudentID, GroupID) VALUES (?, ?)");
                    $stmt->execute([$studentId, $newGroupId]);
                }
            }
        } else {
            $errorMsg = "Course not found.";
        }
    }
}

$courses = [];
$groups = [];

if ($currentInstructorId && $selectedCourseId) {
    $stmt = $pdo->prepare("SELECT c.CourseID, c.CourseCode, c.Name FROM Course c INNER JOIN CourseInstructor ci ON c.CourseID = ci.CourseID WHERE ci.InstructorID = ? AND c.CourseID = ?");
    $stmt->execute([$currentInstructorId, $selectedCourseId]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($courses as $course) {
        $stmt = $pdo->prepare("SELECT g.GroupID, g.GroupLeaderID, g.MaxSize FROM `Group` g WHERE g.CourseID = ?");
        $stmt->execute([$course['CourseID']]);
        $groups[$course['CourseID']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Information</title>
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>
<div class="page">
    <header class="header">
        <h1>Welcome Instructor</h1>
    </header> 

    <div class="sidebar">
        <button onclick="location.href='manage_courses.php'">Manage Courses</button>
        <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
        <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
    </div>

    <main class="main">
        <h2>Upload CSV File</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="studentGroupFile" required>
            <input type="hidden" name="form_submitted" value="1">
            <input type="submit" value="Upload File">
        </form>

        <?php if (!empty($errorMsg)): ?>
            <div class="error">
                <p><?= $errorMsg ?></p>
            </div>
        <?php endif; ?>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($courseCode)): ?>
            <div class="results">
                <h3>File Uploaded Successfully</h3>
                <p><strong>Course Code:</strong> <?= htmlspecialchars($courseCode) ?></p>
                <p><strong>Section:</strong> <?= htmlspecialchars($section) ?></p>
                <p><strong>Group Leader ID:</strong> <?= htmlspecialchars($groupLeaderId) ?></p>
                <p><strong>Random Password:</strong> <?= htmlspecialchars($randomPassword) ?></p>
                <p><strong>Student IDs:</strong> <?= implode(", ", array_map('htmlspecialchars', $studentIds)) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($courses)): ?>
            <h2>Your Courses and Groups</h2>
            <?php foreach ($courses as $course): ?>
                <h3><?= htmlspecialchars($course['CourseCode']) . ' - ' . htmlspecialchars($course['Name']) ?></h3>
                <?php if (!empty($groups[$course['CourseID']])): ?>
                    <?php foreach ($groups[$course['CourseID']] as $group): ?>
                        <div>Group ID: <?= htmlspecialchars($group['GroupID']) ?>, Leader: <?= htmlspecialchars($group['GroupLeaderID']) ?>, Max Size: <?= htmlspecialchars($group['MaxSize']) ?></div>
                        <ul>
                            <?php 
                            $stmt = $pdo->prepare("SELECT u.UserID, u.Name FROM `User` u INNER JOIN StudentGroupMembership sgm ON u.UserID = sgm.StudentID WHERE sgm.GroupID = ?");
                            $stmt->execute([$group['GroupID']]);
                            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($members as $member): ?>
                                <li><?= htmlspecialchars($member['Name']) ?> (ID: <?= htmlspecialchars($member['UserID']) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No groups for this course.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No courses found.</p>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <button onclick="location.href='home.php'">Home</button>
        <button onclick="location.href='../../logout.php'">Logout</button>
    </footer>
</div>
</body>
</html>
