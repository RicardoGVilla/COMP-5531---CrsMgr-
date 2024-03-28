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
