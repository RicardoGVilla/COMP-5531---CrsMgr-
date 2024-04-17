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

        <div class="sidebar">
            <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>
        
        <main class="main">
            <h2>Manage Users</h2>

            <!-- Add User Form -->
            <form id="add-user-form" class="user-form" method="POST" action="logic_user_endpoint.php">
                <h3>Add User</h3>
                <input type="hidden" name="action" value="add">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Add User</button>
            </form>

            <!-- Update User Form -->
            <form id="update-user-form" class="user-form" method="POST" action="logic_user_endpoint.php">
                <h3>Update User</h3>
                <input type="hidden" name="action" value="update">
                <input type="text" name="user_id" placeholder="User ID" required>
                <input type="text" name="new_name" placeholder="New Full Name">
                <input type="email" name="new_email" placeholder="New Email">
                <button type="submit">Update User</button>
            </form>

            <!-- Delete User Form -->
            <form id="delete-user-form" class="user-form" method="POST" action="logic_user_endpoint.php">
                <h3>Delete User</h3>
                <input type="hidden" name="action" value="delete">
                <input type="text" name="user_id" placeholder="User ID" required>
                <button type="submit">Delete User</button>
            </form>
            <!-- User Table -->
            <div class="user-table">
                <h3>Current Users</h3>
                <table>
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
            <button onclick="location.href='logout.php'">Logout</button>
        </footer>
    </div>

    
</body>
</html>