<?php
session_start();
require_once '../../database.php'; 

try {
    $query = "SELECT c.CourseCode, c.Name AS CourseName, f.Question, f.Answer 
              FROM Course c
              LEFT JOIN FAQ f ON c.CourseID = f.CourseID
              ORDER BY c.CourseCode, f.Question";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $faqsByCourse = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $faqsByCourse[$row['CourseCode']][] = $row;
    }
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course FAQs</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
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
            <h2>Course FAQs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Current FAQs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqsByCourse as $courseCode => $faqs): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($courseCode); ?></td>
                            <td><?php echo htmlspecialchars($faqs[0]['CourseName']); ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($faqs as $faq): ?>
                                        <li><?php echo htmlspecialchars($faq['Question']); ?> - <?php echo htmlspecialchars($faq['Answer']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <button onclick="openModal('<?php echo htmlspecialchars($courseCode); ?>')">Add FAQs</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Add FAQs</h3>
                    <form id="faqForm" action="edit_faq_endpoint.php" method="post">
                        <input type="hidden" id="courseCode" name="courseCode">
                        <label for="question">Question:</label><br>
                        <input type="text" id="question" name="question" required><br><br>
                        <label for="answer">Answer:</label><br>
                        <textarea id="answer" name="answer" rows="4" required></textarea><br><br>
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        var modal = document.getElementById('myModal');

        function closeModal() {
            modal.style.display = "none";
        }

        function openModal(courseCode) {
            document.getElementById('courseCode').value = courseCode; 
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
