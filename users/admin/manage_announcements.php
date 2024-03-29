<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Announcements</title>
    <link rel="stylesheet" href="../../css/home.css"> <!-- Ensure this path is correct for your CSS file -->
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header> 
    
        <div class="sidebar">
            <button onclick="location.href='manage_user.php'">Manage Users</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <div class="main">
            <div class="main-header">
                <h2>Manage Course Announcements</h2>
            </div>
            <!-- Add Announcement Form -->
            <div id="add-announcement" class="announcement-form">
                <h2>Add New Announcement</h2>
                <form onsubmit="event.preventDefault();"> <!-- Prevents actual submission for demonstration -->
                    <select name="course_id" required>
                        <option value="">Select Course</option>
                        <!-- Option values are hardcoded for demonstration purposes -->
                        <option value="1">Introduction to Database Systems</option>
                        <option value="2">Advanced Web Development</option>
                    </select>
                    <input type="text" name="title" placeholder="Announcement Title" required />
                    <textarea name="content" placeholder="Announcement Content" required></textarea>
                    <!-- Typically, you would include a date field here to specify when the announcement is made -->
                    <button type="submit">Post Announcement</button>
                </form>
            </div>
        </div>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </footer>
    </div>

    <!-- Additional JavaScript can be added here for dynamic functionality as needed -->
</body>
</html>
