<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Information</title>
    <link rel="stylesheet" href="../../css/home.css">
    <style>
        /* Styles for modal */
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
    <div class="page">
        <header class="header">
            <h1>Welcome Instructor</h1>
        </header> 
    
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQSs</button>
        </div>

        <main class="main">
            <h2>Group Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>Group Number</th>
                        <th>Group Size</th>
                        <th>Group Leader</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>5</td>
                        <td>John Doe</td>
                        <td><button onclick="openModal(1)">Add Members</button></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>4</td>
                        <td>Jane Smith</td>
                        <td><button onclick="openModal(2)">Add Members</button></td>
                    </tr>
                </tbody>
            </table>
    
            <!-- Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h3>Add Members to Group</h3>
                    <form id="memberForm" enctype="multipart/form-data">
                        <label for="file">Upload CSV File:</label>
                        <input type="file" id="file" name="file" accept=".csv" required><br><br>
                        <input type="submit" value="Upload File">
                    </form>
                </div>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        function openModal(groupNumber) {
            modal.style.display = "block";
            // Set a custom attribute to store the group number
            modal.setAttribute('data-group', groupNumber);
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
        document.getElementById('memberForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var groupNumber = modal.getAttribute('data-group');
            var file = document.getElementById('file').files[0];
            var formData = new FormData();
            formData.append('file', file);
            console.log('Group Number:', groupNumber, 'File:', file);
            // Close the modal
            closeModal();
        });
    </script>
</body>
</html>
