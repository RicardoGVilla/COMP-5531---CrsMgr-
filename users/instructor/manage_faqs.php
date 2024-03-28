<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course FAQs</title>
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
    <h2>Course FAQs</h2>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Current FAQs</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch courses and FAQs from the database
            try {
                require_once '../../database.php';

                $query = "SELECT Course.Name AS CourseName, FAQ.Question, FAQ.Answer FROM FAQ JOIN Course ON FAQ.CourseID = Course.CourseID";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $courseFaqs = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

                foreach ($courseFaqs as $courseName => $faqs) {
                    echo "<tr>";
                    echo "<td>$courseName</td>";
                    echo "<td>";
                    foreach ($faqs as $faq) {
                        echo "<ul><li>{$faq['Question']}</li></ul>";
                    }
                    echo "</td>";
                    echo "<td><button onclick=\"openModal(this)\">Add FAQs</button></td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                die("Could not connect to the database: " . $e->getMessage());
            }
            ?>
        </tbody>
    </table>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add FAQs</h3>
            <form id="faqForm" action="edit_faq_endpoint.php" method="post">
                <label for="course">Course:</label>
                <input type="text" id="course" name="course" readonly><br><br>
                <label for="question">Question:</label><br>
                <input type="text" id="question" name="question" required><br><br>
                <label for="answer">Answer:</label><br>
                <textarea id="answer" name="answer" rows="4" required></textarea><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        function closeModal() {
            modal.style.display = "none";
        }

        function openModal(btn) {
            var row = btn.parentNode.parentNode;
            var courseName = row.cells[0].innerText;
            document.getElementById('course').value = courseName;
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
