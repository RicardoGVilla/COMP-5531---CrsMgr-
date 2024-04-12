<?php
session_start();
require_once '../../database.php';

// Function to add new user with a default password and specific role
function addNewUser($name, $email, $role) {
    global $pdo; // Ensure $pdo is accessible within the function
    try {
        $pdo->beginTransaction();

        // Prepare the insert query for the User table
        $stmt = $pdo->prepare("INSERT INTO `User` (Name, EmailAddress, Password) VALUES (?, ?, ?)");
        $password = password_hash('defaultPassword', PASSWORD_DEFAULT); // Default password for new users
        $stmt->execute([$name, $email, $password]);
        $userId = $pdo->lastInsertId(); // Get the ID of the newly inserted user

        // Insert the role into the UserRole table
        $stmt = $pdo->prepare("INSERT INTO UserRole (UserID, RoleID) VALUES (?, ?)");
        $stmt->execute([$userId, $role]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Failed to add new user: ' . $e->getMessage()); // Log error to error log
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $role = $_POST['role'] ?? null;

        if ($name && $email && $role) {
            if (addNewUser($name, $email, $role)) {
                $_SESSION['message'] = 'User added successfully!';
            } else {
                $_SESSION['error'] = 'Failed to add user.';
            }
        } else {
            $_SESSION['error'] = 'Invalid user data provided.';
        }
    }

    header('Location: manage_user.php');
    exit;
}

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
            <!-- Additional sidebar buttons -->
        </div>
        
        <main class="main">
            <div class="main-header">
                <h2>Manage Users</h2>
            </div>
            <div id="add-user" class="user-form table-wrapper">
                <h2>Add User</h2>
                <form class="inline-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
                    <div>
                        <input type="hidden" name="action" value="add"> 
                        <div class="input-body">
                            <input type="text" name="name" placeholder="Full Name" required />
                            <input type="email" name="email" placeholder="Email" required />
                            <select name="role">
                                <option value="1">Student</option>
                                <option value="2">Instructor</option>
                                <option value="3">TA</option>
                                <option value="4">Admin</option>
                            </select>
                        </div>
                        <button class="button is-primary" type="submit">Add User</button>
                    </div>
                </form>
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
                                <th>Role</th>
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
                                    <td><?php echo htmlspecialchars($user['RoleName']); ?></td>
                                    <td>
                                        <button class="button is-secondary" onclick="alert('Update functionality needs to be implemented.');">Update User</button>
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
        </main>

        <footer class="footer">
            <button onclick="location.href='../home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
