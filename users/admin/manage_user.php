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
            <button onclick="location.href='manage_user.php'">Manage Users</button>
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
                    <input type="text" name="user_id" placeholder="User ID" required />
                    <input type="text" name="new_name" placeholder="New Full Name" />
                    <input type="email" name="new_email" placeholder="New Email" />
                    <input type="password" name="new_password" placeholder="New Password" />
                    <select name="new_role">
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
                <button class="button is-secondary" onclick="showForm('update')">Update User</button>
            </div>

            <!-- User Table -->
            <div class="user-table">
                <h2>Current Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th> <!-- New column for role -->
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
                                    <button class="button is-secondary" onclick="location.href='./delete_user_endpoint.php?id=<?php echo $user['UserID']; ?>'">Delete User</button>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button> 
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    <script>
        function showForm(formId) {
            // Hide all forms
            document.getElementById('add-user').style.display = 'none';
            document.getElementById('update-user').style.display = 'none';

            // Show the selected form
            document.getElementById(formId + '-user').style.display = 'block';
        }
    </script>
</body>
</html>
