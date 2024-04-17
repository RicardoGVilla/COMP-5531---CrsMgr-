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
                <h2>Manage Roles</h2>
            </div>
            <!-- Edit User Form -->
            <div class="table-wrapper">
                <h2>Edit User Roles</h2>
                <form class="inline-form" action="edit_user_endpoint.php" method="post">
                    <div class="label-input-body">
                        <div class="label-input">
                            <label for="userId">User ID:</label>
                            <input type="text" id="userId" name="userId" required>
                        </div>
                        <div class="label-input">
                            <label for="role">Role:</label>
                            <select name="role" id="role" required>
                                <option value="1">Student</option>
                                <option value="2">Instructor</option>
                                <option value="3">TA</option>
                                <option value="4">Admin</option>
                            </select>
                        </div>
                        <div class="label-input">
                            <label for="action">Action:</label>
                            <select name="action" id="action" required>
                                <option value="add">Add Role</option>
                                <option value="remove">Remove Role</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button class="button is-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
            <div class="table-wrapper">
                <h2>User Roles List</h2>
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Remove Role</th>
                    </tr>
                    <?php
                    // SQL query to fetch user roles
                    $sql = "SELECT u.UserID, u.Name, u.EmailAddress, r.RoleName, r.RoleID
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
                                        <td>
                                            <button class='button is-remove' onclick='removeRole(" . $row['UserID'] . ", \"" . $row['RoleID'] . "\")'>Remove Role</button>
                                        </td>
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
            </div>

        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button> 
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
    <script>
        function removeRole(userId, roleId) {
            if (confirm("Are you sure you want to remove the role? UserID: " + userId + ", RoleID: " + roleId)) {
                // Send AJAX request to edit_user_endpoint.php
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "edit_user_endpoint.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText); // Display success message or error
                            location.reload();// Refresh the page or update the UI as needed
                        } else {
                            alert("Error: Unable to remove role"); // Display error message
                        }
                    }
                };
                xhr.send("userId=" + encodeURIComponent(userId) + "&role=" + encodeURIComponent(roleId) + "&action=remove"); // Send POST data
            }
        }
    </script>
</body>
</html>