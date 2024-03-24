<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/home.css">
</head>
<body>
    <div class="header">
        <h1>Welcome Admin</h1>
    </div>
    <div class="admin-menu">
        <button onclick="location.href='manage_user.php'">Manage Users</button>
        <button onclick="location.href='manage_courses.php'">Manage Courses</button>
        <button onclick="location.href='manage_sections.php'">Manage Sections</button>
        <button onclick="location.href='manage_groups.php'">Manage Groups</button>
        <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
        <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
        <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
    </div>
    <div class="main-content">
    </div>
    <div class="footer">
        <button onclick="location.href='home.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
