<?php
session_start(); // Start the session at the very beginning.

// Include database connection
include('../../database.php');

// Fetch users from the database
$query = "SELECT `User`.UserID, `User`.Name, `User`.EmailAddress, Role.RoleName FROM `User`
          JOIN UserRole ON `User`.UserID = UserRole.UserID
          JOIN Role ON UserRole.RoleID = Role.RoleID";
$stmt = $pdo->query($query);

// Initialize an empty array to store users
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

// header('Content-Type: application/json');
// echo json_encode($users);
// var_dump($users);


// Display success message if set
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); 
}

// Display error message if set
if (isset($_SESSION['error'])) {
    echo "<p>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../css/index.css"> 
</head>
<body>
<div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header>

        <div class="sidebar">
            <button class="is-selected" onclick="location.href='manage_user.php'">Manage Users</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_assignments.php'">Assignments/Projects</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>
        
        <main class="main">
            <div class="main-header">
                <h2>Manage Users</h2>
            </div>
            <!-- Add User Form -->
            <div id="add-user" class="user-form">
                <h2>Add User</h2>
                <form method="POST" action="edit_user_endpoint.php"> 
                    <input type="hidden" name="action" value="add"> 
                    <input type="text" name="name" placeholder="Full Name" required />
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <select name="role">
                        <option value="1">Student</option>
                        <option value="2">Instructor</option>
                        <option value="3">TA</option>
                        <option value="4">Admin</option>
                    </select>
                    <button class="button" type="submit">Add User</button>
                </form>
            </div>

            <!-- Update User Form -->
            <div id="update-user" class="user-form" style="display: none;">
                <h2>Update User</h2>
                <form method="POST" action="edit_user_endpoint.php">
                    <input type="hidden" name="action" value="update"> 
                    <input type="text" id="update_user_id" name="user_id" placeholder="User ID" required />
                    <input type="text" id="update_new_name" name="new_name" placeholder="New Full Name" />
                    <input type="email" id="update_new_email" name="new_email" placeholder="New Email" />
                    <input type="password" id="update_new_password" name="new_password" placeholder="New Password" />
                    <select id="update_new_role" name="new_role">
                        <option value="1">Student</option>
                        <option value="2">Instructor</option>
                        <option value="3">TA</option>
                        <option value="4">Admin</option>
                    </select>
                    <button class="button" type="submit">Update User</button>
                </form>
            </div>

            <!-- Delete User Form -->
            <div id="delete-user" class="user-form" style="display: none;">
                <h2>Delete User</h2>
                <form method="POST" action="delete_user_endpoint.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="text" name="user_id" placeholder="User ID" required />
                    <button type="submit">Delete User</button>
                </form>
            </div>

            <div class="user-actions">
                <button class="button is-primary" onclick="showForm('add')">Add User</button>
            </div>

            <!-- User Table -->
            <div class="user-table">
                <h2>Current Users</h2>
                <div class="table-wrapper">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th> <!-- New column for role -->
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                                    <td><?php echo htmlspecialchars($user['Name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['EmailAddress']); ?></td>
                                    <td><?php echo htmlspecialchars($user['RoleName']); ?></td> <!-- Display role -->
                                    <td>
                                        <button class="button is-secondary" onclick="openModal('<?php echo $user['UserID']; ?>', '<?php echo htmlspecialchars($user['Name']); ?>', '<?php echo htmlspecialchars($user['EmailAddress']); ?>', '', '<?php echo $user['RoleName']; ?>')">Update User</button>

                                    </td>
                                    <td>
                                        <button class="button is-delete" onclick="confirmDelete(<?php echo $user['UserID']; ?>)">Delete User</button>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <!-- Update User Form -->
                    <h2>Update User</h2>
                    <form class="form" id="update-user-form" method="POST" action="update_user_endpoint.php">
                        <input type="hidden" name="action" value="update"> 
                        <input type="hidden" id="update_user_id" name="user_id" />
                        <div class="input">
                            <label for="update_new_name">New Name:</label>
                            <input id="update_new_name" type="text" name="new_name" placeholder="New Full Name" />
                        </div>
                        <div class="input">
                            <label for="update_new_email">New Email:</label>
                            <input type="email" id="update_new_email" name="new_email" placeholder="New Email" />
                        </div>
                        <div class="input">
                            <label for="update_new_password">New Password:</label>
                            <input type="password" id="update_new_password" name="new_password" placeholder="New Password" />
                        </div>
                        <div class="input">
                            <select id="update_new_role" name="new_role">
                                <option value="1">Student</option>
                                <option value="2">Instructor</option>
                                <option value="3">TA</option>
                                <option value="4">Admin</option>
                            </select>
                        </div>
                        <div class="button-container">
                            <button class="button is-secondary" type="submit">Update User</button>
                        </div>
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
        // JavaScript function to confirm user deletion
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                // If confirmed, redirect to delete_user_endpoint.php with the user ID
                window.location.href = 'delete_user_endpoint.php?id=' + userId;
            }
        }
        function showForm(formId) {
            // Hide all forms
            document.getElementById('add-user').style.display = 'none';
            document.getElementById('update-user').style.display = 'none';

            // Show the selected form
            document.getElementById(formId + '-user').style.display = 'block';
        }

        var modal = document.getElementById('myModal');

        function openModal(userId, newName, newEmail, newPassword, newRole) {
            document.getElementById('update_user_id').value = userId;
            document.getElementById('update_new_name').value = newName;
            document.getElementById('update_new_email').value = newEmail;
            document.getElementById('update_new_password').value = newPassword;
            document.getElementById('update_new_role').value = newRole;
            // Update form action
            document.getElementById('update-user-form').action = "update_user_endpoint.php?id=" + userId;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        

    </script>
</body>
</html>
