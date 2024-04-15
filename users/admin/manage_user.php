<?php
session_start(); 

// Include database connection
include('../../database.php');

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
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
        </div>
        
        <main class="main">
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Roles</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h2>Edit User Roles</h2>
<form action="edit_user_endpoint.php" method="post">
    <label for="userId">User ID:</label>
    <input type="text" id="userId" name="userId" required>

    <label for="role">Role:</label>
    <select name="role" id="role" required>
        <option value="1">Student</option>
        <option value="2">Instructor</option>
        <option value="3">TA</option>
        <option value="4">Admin</option>
    </select>

    <label for="action">Action:</label>
    <select name="action" id="action" required>
        <option value="add">Add Role</option>
        <option value="remove">Remove Role</option>
    </select>

    <button type="submit">Submit</button>
</form>
    <h1>User Roles List</h1>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email Address</th>
            <th>Role</th>
        </tr>
        <?php
        // SQL query to fetch user roles
        $sql = "SELECT u.UserID, u.Name, u.EmailAddress, r.RoleName 
                FROM UserRole ur
                JOIN `User` u ON ur.UserID = u.UserID
                JOIN Role r ON ur.RoleID = r.RoleID";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                foreach ($results as $row) {
                    echo "<tr>
                            <td>" . $row["UserID"] . "</td>
                            <td>" . $row["Name"] . "</td>
                            <td>" . $row["EmailAddress"] . "</td>
                            <td>" . $row["RoleName"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No results found</td></tr>";
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }

        ?>
    </table>
</body>
</html>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button> 
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>

    
</body>
</html>