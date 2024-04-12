<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs</title>
    <link rel="stylesheet" href="../../css/index.css">
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
            <button class="is-selected" onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage FAQs</h2>
            </div>
            <!-- Add FAQ Form -->
            <div id="add-faq" class="faq-form table-wrapper">
                <h2>Add New FAQ</h2>
                <form class="inline-form" onsubmit="event.preventDefault();"> 
                    <div class="input-body">
                        <input type="text" name="question" placeholder="FAQ Question" required />
                        <select name="course_id">
                            <option value="">Select Course (optional)</option>
                            <option value="1">Introduction to Database Systems</option>
                            <option value="2">Advanced Web Development</option>
                        </select>
                    </div>
                    <textarea name="answer" placeholder="FAQ Answer" required></textarea>
                    <div>
                        <button class="button is-primary" type="submit">Add FAQ</button>
                    </div>
                </form>
            </div>

            <!-- Update FAQ Form - Simplified version without dynamic data loading -->
            <div id="update-faq" class="faq-form table-wrapper" style="display: none;">
                <h2>Update FAQ</h2>
                <form class="inline-form" onsubmit="event.preventDefault();"> <!-- Prevents actual submission for demonstration -->
                    <div class="input-body">
                        <input type="number" name="faq_id" placeholder="FAQ ID" required />
                        <input type="text" name="new_question" placeholder="New Question" />
                        <select name="new_course_id">
                            <option value="">Select New Course (optional)</option>
                            <option value="1">Introduction to Database Systems</option>
                            <option value="2">Advanced Web Development</option>
                        </select>
                    </div>
                    <textarea name="new_answer" placeholder="New Answer"></textarea>
                    <div>
                        <button class="button is-secondary" type="submit">Update FAQ</button>
                    </div>
                </form>
            </div>

            <!-- Delete FAQ Form -->
            <div id="delete-faq" class="faq-form" style="display: none;">
                <h2>Delete FAQ</h2>
                <form onsubmit="event.preventDefault();"> <!-- Prevents actual submission for demonstration -->
                    <input type="number" name="faq_id" placeholder="FAQ ID" required />
                    <button type="submit">Delete FAQ</button>
                </form>
            </div>

            <div class="faq-actions">
                <button class="button is-primary"  onclick="showForm('add')">Add FAQ</button>
                <button class="button is-secondary" onclick="showForm('update')">Update FAQ</button>
                <button class="button is-delete"  onclick="showForm('delete')">Delete FAQ</button>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        function showForm(formId) {
            document.getElementById('add-faq').style.display = 'none';
            document.getElementById('update-faq').style.display = 'none';
            document.getElementById('delete-faq').style.display = 'none';
            document.getElementById(formId + '-faq').style.display = 'block';
        }
    </script>
</body>
</html>
