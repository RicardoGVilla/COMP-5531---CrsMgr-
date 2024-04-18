<?php
session_start();
require_once '../../database.php'; // Adjust path as needed

if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['email_id'])) {
    $email_id = $_GET['email_id'];
    $stmt = $pdo->prepare("SELECT InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, `User`.Name AS SenderName FROM InternalEmail JOIN `User` ON InternalEmail.SenderID = `User`.UserID WHERE InternalEmail.EmailID = ?");
    $stmt->execute([$email_id]);
    $email = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Email - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        .email-system {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            padding: 20px;
        }
        .email-content h1, p {
            margin-bottom: 10px;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <header class="header">
            <h1>Welcome Admin <?php echo htmlspecialchars($_SESSION['userName']); ?></h1>
        </header>
        <!-- Sidebar -->
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
        <!-- Main content area -->
        <main class="main">
            <div class="email-system">
                <?php if ($email): ?>
                    <h1><?php echo htmlspecialchars($email['Subject']); ?></h1>
                    <p><strong>From:</strong> <?php echo htmlspecialchars($email['SenderName']); ?></p>
                    <p><strong>Received:</strong> <?php echo $email['Timestamp']; ?></p>
                    <p><?php echo nl2br(htmlspecialchars($email['Body'])); ?></p>
                <?php else: ?>
                    <p>Email not found or you do not have permission to view it.</p>
                <?php endif; ?>
            </div>
        </main>
        <!-- Footer -->
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
