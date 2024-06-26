<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

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
$successMessage = '';

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
        $stmt = $pdo->prepare("SELECT CourseID FROM Course WHERE CourseCode = ?");
        $stmt->execute([$courseCode]);
        $courseId = $stmt->fetchColumn();
        
        if ($courseId) {
            // Check for existing group with the same members
            $memberQuery = implode(',', array_map('intval', $studentIds));
            $stmt = $pdo->prepare("SELECT GroupID FROM `Group` WHERE GroupLeaderID = ? AND CourseID = ? AND 
                                   EXISTS(SELECT 1 FROM StudentGroupMembership WHERE GroupID = `Group`.GroupID AND StudentID IN ($memberQuery) 
                                   GROUP BY GroupID HAVING COUNT(*) = ?)");
            $stmt->execute([$groupLeaderId, $courseId, count($studentIds)]);
            $existingGroupId = $stmt->fetchColumn();
            
            if ($existingGroupId) {
                $successMessage = "A similar group already exists with ID: " . $existingGroupId;
            } else {
                try{
                $stmt = $pdo->prepare("INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$courseId, $groupLeaderId, $randomPassword, count($studentIds)])) {
                    $newGroupId = $pdo->lastInsertId();
                    $successMessage = "Group successfully added to course: " . htmlspecialchars($courseCode);

                    foreach ($studentIds as $studentId) {
                        $stmt = $pdo->prepare("INSERT INTO StudentGroupMembership (StudentID, GroupID) VALUES (?, ?)");
                        $stmt->execute([$studentId, $newGroupId]);
                    }
                } else {
                    $successMessage = "Failed to insert the group.";
                }
                }catch(Exception $e){
                    //echo $e->getMessage() . "<br/>";
                    $_SESSION['error'] = "Error updating group(s): " . $e->getMessage();
                        echo '<script type ="text/JavaScript">';  
                        echo 'alert("Error, could not upload correctly csv file")';  
                        echo '</script>';
                }
            }
        } else {
            $successMessage = "Error: Course code not found.";
        }
    }
}

// Fetch all courses and their groups from the database
$courses = [];
$stmt = $pdo->prepare("
    SELECT c.CourseCode, c.Name, g.GroupID, g.MaxSize, u.UserID, u.Name AS UserName
    FROM Course c
    LEFT JOIN `Group` g ON c.CourseID = g.CourseID
    LEFT JOIN StudentGroupMembership sgm ON g.GroupID = sgm.GroupID
    LEFT JOIN `User` u ON sgm.StudentID = u.UserID
    ORDER BY c.CourseCode, g.GroupID, u.UserID
");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $courses[$row['CourseCode']]['Name'] = $row['Name'];
    $courses[$row['CourseCode']]['Groups'][$row['GroupID']]['MaxSize'] = $row['MaxSize'];
    $courses[$row['CourseCode']]['Groups'][$row['GroupID']]['Members'][$row['UserID']] = $row['UserName'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Information</title>
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
        <button class="is-selected" onclick="location.href='manage_groups.php'">Manage Groups</button>
        <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
        <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
        <button onclick="location.href='logs.php'">User Logs</button>
        <button onclick="location.href='internal_email.php'">Internal Communication</button>
    </div>

    <main class="main">
        <div class="table-wrapper">
            <h2>Upload CSV File</h2>
            <form class="form" action="" method="post" enctype="multipart/form-data">
                <input type="file" name="studentGroupFile" required>
                <input type="hidden" name="form_submitted" value="1">
                
                <input type="submit" value="Upload File">
            </form>
        </div>

        <?php if (!empty($successMessage)): ?>
            <p><?= $successMessage ?></p>
        <?php endif; ?>
        <div class="table-wrapper">
            <?php if (!empty($courses)): ?>
                <h2>All Courses</h2>
                <?php foreach ($courses as $courseCode => $course): ?>
                    <h3><?= htmlspecialchars($courseCode) . ' - ' . htmlspecialchars($course['Name']) ?></h3>
                    <?php if (!empty($course['Groups'])): ?>
                        <?php foreach ($course['Groups'] as $groupId => $group): ?>
                            <div>Group ID: <?= htmlspecialchars($groupId) ?>, Max Size: <?= htmlspecialchars($group['MaxSize']) ?></div>
                            <ul>
                                <?php if (!empty($group['Members'])): ?>
                                    <?php foreach ($group['Members'] as $userId => $userName): ?>
                                        <li><?= htmlspecialchars($userName) ?> (ID: <?= htmlspecialchars($userId) ?>)</li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>No members in this group.</li>
                                <?php endif; ?>
                            </ul>
                            <br>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No groups for this course.</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No courses found.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <button onclick="location.href='home.php'">Home</button>
        <button onclick="location.href='../../logout.php'">Logout</button>
    </footer>
</div>
</body>
</html>
