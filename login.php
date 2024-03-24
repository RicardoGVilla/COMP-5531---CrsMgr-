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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="header">
                <div class="text">
                    <h1>Welcome to CrsMgr</h1>
                    <h2>-- The Course Manager System! --</h2>
                </div>
            </div>
            <div class="form-container">
                <form id="login-form" action="login.php" method="post">
                    
                    <div class="inputs">
                    <div class="input-container">
                        <img src="" alt="" />
                        <input id="email" type="email" name='email' placeholder='Email' required/>
                    </div>
                    </div>
                    <div class="inputs">
                    <div class="input-container">
                        <img src="" alt="" />
                        <input id="password" type="password" name='password' placeholder='Password' required/>
                    </div>
                    </div>
                    <div class="submit-container">
                        <button class="submit" name="submit" type="submit">Login</button>
                    </div>
                    <div class="forgot-password">Forgot Password? <span>Click here</span></div>
                
                    <?php
                    if (isset($_GET["error"]) && $_GET["error"] === "invalid_credentials") {
                        echo "<p style='color: red;'>Invalid email or password. Please try again.</p>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
