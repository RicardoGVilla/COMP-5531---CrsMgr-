<?php
include_once 'database.php';
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and password from the form
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Query the database to fetch user details
    $stmt = $pdo->prepare("SELECT u.UserID, u.Name, r.RoleName 
                           FROM `User` u
                           INNER JOIN UserRole ur ON u.UserID = ur.UserID
                           INNER JOIN Role r ON ur.RoleID = r.RoleID
                           WHERE u.EmailAddress = :email AND u.Password = :password");
    $stmt->execute(['email' => $email, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if user exists and credentials are correct
    if ($user) {
        // Authentication successful, set session variables
        $_SESSION["user"] = $user;
        
        // Redirect user based on role
        switch ($user['RoleName']) {
            case 'Admin':
                header("Location: users/admin/home.php");
                break;
            case 'Instructor':
                header("Location: users/instructor/home.php");
                break;
            case 'TA':
                header("Location: users/student/home.php");
                break;
            case 'Student':
                header("Location: users/student/home.php");
                break;
            default:
                header("Location: home.php");
                break;
        }
        exit;
    } else {
        header("Location: login.php?error=invalid_credentials");
        exit;
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>CGA System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <h2>Welcome to CrsMgr+ -- Please Login</h2>
    <div id="login-form" class="form-body">
        <form id="login-form" class="login-email" action="login.php" method="post">
            <div class="input-group">
                <input id="email" name="email" type="email" placeholder="Email Address" required>
            </div>
            <div class="input-group">
                <input id="password" name="password" type="password" placeholder="Password" required> 
            </div>
            <div class="input-group">
                <button class="btn" name="submit" type="submit">Login</button>
            </div>
            <?php
            if (isset($_GET["error"]) && $_GET["error"] === "invalid_credentials") {
                echo "<p style='color: red;'>Invalid email or password. Please try again.</p>";
            }
            ?>
        </form>
    </div>
</body>
</html>
