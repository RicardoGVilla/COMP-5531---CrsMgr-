<?php
session_start();
require_once '../../database.php'; 

try {
    // Fetch courses from the database
    $query = "SELECT CourseID, Name, StartDate, EndDate FROM Course";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Information</title>
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
    </style>
</head>
<body>
    <h2>Group Information</h2>
    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['CourseID']) ?></td>
                <td><?= htmlspecialchars($course['Name']) ?></td>
                <td><?= htmlspecialchars($course['StartDate']) ?></td>
                <td><?= htmlspecialchars($course['EndDate']) ?></td>
                <td><button onclick="openModal(<?= $course['CourseID'] ?>)">Add Members</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add Student</h3>
            <form id="studentForm">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" required><br><br>
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" required><br><br>
                <label for="studentId">Student ID:</label>
                <input type="text" id="studentId" name="studentId" required><br><br>
                <input type="submit" value="Add Student">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        function closeModal() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Handle form submission
        document.getElementById('studentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var firstName = document.getElementById('fname').value;
            var lastName = document.getElementById('lname').value;
            var studentId = document.getElementById('studentId').value;
            console.log('First Name:', firstName, 'Last Name:', lastName, 'Student ID:', studentId);
            closeModal();
        });

        // Function to open the modal
        function openModal(courseID) {
            // Set a custom attribute to store the course ID
            modal.setAttribute('data-course', courseID);
            modal.style.display = "block";
        }
    </script>
</body>
</html>
