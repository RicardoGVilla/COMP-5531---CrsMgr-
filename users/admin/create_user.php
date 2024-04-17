<?php
session_start(); // Start the session at the very beginning

// Include database connection
include('../../database.php');

// Initialize an empty array to store users
$users = [];

// Try to fetch users from the database
try {
    $query = "SELECT `User`.UserID, `User`.Name, `User`.EmailAddress FROM `User`";
    $stmt = $pdo->query($query);

    // Fetch users into the array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching users: " . $e->getMessage();
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
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Admin</h1>
        </header>

        <div class="sidebar">
        <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
        </div>

        <main class="main">
            <div class="main-header">
                <h2>Manage Users</h2>
            </div>

            <!-- Display session messages -->
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <!-- Add, Update, and Delete functionality will go here -->

            <div class="user-table">
                <h2>Current Users</h2>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                            <td><?php echo htmlspecialchars($user['Name']); ?></td>
                            <td><?php echo htmlspecialchars($user['EmailAddress']); ?></td>
                            <td>
                                <button class="button is-secondary" onclick="openModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">Update</button>
                                <button class="button is-delete" onclick="confirmDelete(<?php echo $user['UserID']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = 'logic_user_endpoint.php?action=delete&user_id=' + userId;
            }
        }

        function openModal(user) {
            // Assuming you have a modal setup for updates, populate its fields and show it
            document.getElementById('update_user_id').value = user.UserID;
            document.getElementById('update_new_name').value = user.Name;
            document.getElementById('update_new_email').value = user.EmailAddress;
            // Show your modal, e.g., $('#myModal').modal('show');
        }
    </script>
</body>
</html>
