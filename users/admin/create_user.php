<?php
session_start(); 

include('../../database.php');

$query = "SELECT UserID, Name, EmailAddress FROM `User`";
$stmt = $pdo->query($query);

$users = [];

// Fetch users into the array
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $users[] = $row;
}

function compareUsers($a, $b) {
    return $a['UserID'] - $b['UserID'];
}

// Sort the users array using the compareUsers function
usort($users, 'compareUsers');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Users</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header>

        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <button class="is-selected" onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
            <button onclick="location.href='logs.php'">User Logs</button>
            <button onclick="location.href='internal_email.php'">Internal Communication</button>
        </div>
        <main class="main">
            <h2>Manage Users</h2>

            <!-- Add User Form -->
            <div class="table-wrapper">
                <form id="add-user-form" class="user-form inline-form" method="POST" action="logic_user_endpoint.php">
                    <h3>Add User</h3>
                    <div class="input-body">
                        <input type="hidden" name="action" value="add">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div>
                        <button class="button is-primary" type="submit">Add User</button>
                    </div>
                </form>
            </div>

            <!-- Update User Form -->
            <div class="table-wrapper">
                <form id="update-user-form" class="user-form inline-form" method="POST" action="logic_user_endpoint.php">
                    <h3>Update User</h3>
                    <div class="input-body">
                        <input type="hidden" name="action" value="update">
                        <input type="text" name="user_id" placeholder="User ID" required>
                        <input type="text" name="new_name" placeholder="New Full Name">
                        <input type="email" name="new_email" placeholder="New Email">
                    </div>
                    <div>
                        <button class="button is-secondary" type="submit">Update User</button>
                    </div>
                </form>
            </div>

            <!-- Delete User Form -->
            <div class="table-wrapper">
                <form id="delete-user-form" class="user-form inline-form" method="POST" action="logic_user_endpoint.php">
                    <h3>Delete User</h3>
                    <div class="input-body">
                        <input type="hidden" name="action" value="delete">
                        <input type="text" name="user_id" placeholder="User ID" required>
                        <button class="button is-delete" type="submit">Delete User</button>
                    </div>
                </form>
            </div>
            <!-- User Table -->
            <div class="user-table table-wrapper">
                <h3>Current Users</h3>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['EmailAddress']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        document.getElementById('add-user-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = new FormData(this);

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'logic_user_endpoint.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Show alert with response message
                    alert(xhr.responseText);
                    window.location.reload();
                } else {
                    // Handle error
                    console.error('Request failed with status:', xhr.status);
                }
            };
            xhr.onerror = function() {
                // Handle network errors
                console.error('Request failed');
            };
            xhr.send(formData);
        });
        document.getElementById('update-user-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = new FormData(this);

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'logic_user_endpoint.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Show alert with response message
                    alert(xhr.responseText);
                    window.location.reload();
                } else {
                    // Handle error
                    console.error('Request failed with status:', xhr.status);
                }
            };
            xhr.onerror = function() {
                // Handle network errors
                console.error('Request failed');
            };
            xhr.send(formData);
        });
        document.getElementById('delete-user-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = new FormData(this);

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'logic_user_endpoint.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Show alert with response message
                    alert(xhr.responseText);
                    window.location.reload();
                } else {
                    // Handle error
                    console.error('Request failed with status:', xhr.status);
                }
            };
            xhr.onerror = function() {
                // Handle network errors
                console.error('Request failed');
            };
            xhr.send(formData);
        });
    </script>

</body>
</html>