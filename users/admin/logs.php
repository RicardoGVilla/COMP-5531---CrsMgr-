<?php
session_start();
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php");
    exit;
}

// Query to fetch user login logs with user details
$query = "SELECT l.LogID, l.UserID, l.LoginTime, l.Success, u.EmailAddress, u.Name 
          FROM UserLoginLog l
          INNER JOIN `User` u ON l.UserID = u.UserID";
$stmt = $pdo->query($query);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>
    <div class="page">
        <header class="header">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?> [Admin]</h1>
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
            <button onclick="location.href='logs.php'">User Logs</button>
            <button onclick="location.href='internal_emails.php'">Internal Communication</button>
        </div>

        <main class="main">
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>User Logs</h2>
    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Login Time</th>
                <th>Success</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log['LogID']; ?></td>
                    <td><?php echo $log['UserID']; ?></td>
                    <td><?php echo $log['Name']; ?></td>
                    <td><?php echo $log['EmailAddress']; ?></td>
                    <td><?php echo $log['LoginTime']; ?></td>
                    <td><?php echo $log['Success'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

        </main>
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>


