<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs</title>
    <link rel="stylesheet" href="../../css/home.css">
<body>
    <div class="header">
        <h1>Manage FAQs</h1>
    </div>

    <div class="main-content">
        <!-- Add FAQ Form -->
        <div id="add-faq" class="faq-form">
            <h2>Add New FAQ</h2>
            <form onsubmit="event.preventDefault();"> <!-- Prevents actual submission for demonstration -->
                <input type="text" name="question" placeholder="FAQ Question" required />
                <textarea name="answer" placeholder="FAQ Answer" required></textarea>
                <select name="course_id">
                    <option value="">Select Course (optional)</option>
                    <option value="1">Introduction to Database Systems</option>
                    <option value="2">Advanced Web Development</option>
                </select>
                <button type="submit">Add FAQ</button>
            </form>
        </div>

        <!-- Update FAQ Form - Simplified version without dynamic data loading -->
        <div id="update-faq" class="faq-form" style="display: none;">
            <h2>Update FAQ</h2>
            <form onsubmit="event.preventDefault();"> <!-- Prevents actual submission for demonstration -->
                <input type="number" name="faq_id" placeholder="FAQ ID" required />
                <input type="text" name="new_question" placeholder="New Question" />
                <textarea name="new_answer" placeholder="New Answer"></textarea>
                <select name="new_course_id">
                    <option value="">Select New Course (optional)</option>
                    <option value="1">Introduction to Database Systems</option>
                    <option value="2">Advanced Web Development</option>
                </select>
                <button type="submit">Update FAQ</button>
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
            <button onclick="showForm('add')">Add FAQ</button>
            <button onclick="showForm('update')">Update FAQ</button>
            <button onclick="showForm('delete')">Delete FAQ</button>
        </div>
    </div>

    <div class="footer">
        <button onclick="location.href='../home.php'">Home</button>
        <button onclick="location.href='logout.php'">Logout</button>
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
