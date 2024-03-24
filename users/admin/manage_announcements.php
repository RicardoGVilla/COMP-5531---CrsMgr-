<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Announcements</title>
    <link rel="stylesheet" href="../../css/home.css"> <!-- Ensure this path is correct for your CSS file -->
</head>
<body>
    <div class="header">
        <h1>Manage Course Announcements</h1>
    </div>

    <div class="main-content">
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

    <div class="footer">
        <button onclick="location.href='../home.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
    </div>

    <!-- Additional JavaScript can be added here for dynamic functionality as needed -->
</body>
</html>
