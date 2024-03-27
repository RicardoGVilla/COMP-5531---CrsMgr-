<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Information</title>
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
    <h2>Computer Science Courses</h2>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Course Section</th>
                <th>Class Size</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Introduction to Programming</td>
                <td>CS101</td>
                <td>50</td>
                <td><button onclick="openModal('CS101')">Add Student</button></td>
            </tr>
            <tr>
                <td>Data Structures and Algorithms</td>
                <td>CS202</td>
                <td>40</td>
                <td><button onclick="openModal('CS202')">Add Student</button></td>
            </tr>
            <tr>
                <td>Database Management Systems</td>
                <td>CS303</td>
                <td>35</td>
                <td><button onclick="openModal('CS303')">Add Student</button></td>
            </tr>
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

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        function openModal(courseName) {
            modal.style.display = "block";
            // Set a custom attribute to store the course name
            modal.setAttribute('data-course', courseName);
        }

        // When the user clicks on <span> (x), close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Handle form submission
        document.getElementById('studentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var courseName = modal.getAttribute('data-course');
            var firstName = document.getElementById('fname').value;
            var lastName = document.getElementById('lname').value;
            var studentId = document.getElementById('studentId').value;
            console.log('Course:', courseName, 'First Name:', firstName, 'Last Name:', lastName, 'Student ID:', studentId);
            closeModal();
        });
    </script>
</body>
</html>
