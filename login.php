<?php
include_once 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query the database to fetch user details by email, including the NewUser attribute
    $stmt = $pdo->prepare("SELECT UserID, Name, Password, NewUser FROM `User` WHERE EmailAddress = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $passwordMatch = false;

        // Check if the password submitted starts with "hashed_"
        if (substr($password, 0, 7) === 'hashed_') {
            // Perform a direct string comparison
            $passwordMatch = ($user['Password'] === $password);
        } else {
            // Use password_verify() for comparison for non-prefixed passwords
            $_SESSION['change_password_user_id'] = $user['UserID']; // Store the user's ID in the session
            $passwordMatch = password_verify($password, $user['Password']);
        }

        if ($passwordMatch) {
            // Password is correct, check if NewUser is TRUE
            if ($user['NewUser']) {
                // Redirect to new_password.php for the user to set a new password
                header("Location: change_password.php");
                exit;
            }

            // If NewUser is FALSE, continue with the role-based redirection logic
            $_SESSION["user"] = $user;
            $roles = getUserRoles($user['UserID'], $pdo);

            if (in_array('Admin', $roles)) {
                header("Location: users/admin/home.php");
            } elseif (in_array('Instructor', $roles)) {
                header("Location: users/instructor/home.php");
            } elseif (in_array('TA', $roles)) {
                header("Location: users/ta/choose-role.php");
            } elseif (in_array('Student', $roles)) {
                $stmtCourses = $pdo->prepare("SELECT COUNT(DISTINCT CourseID) AS CourseCount FROM StudentEnrollment WHERE StudentID = :userId");
                $stmtCourses->execute(['userId' => $user['UserID']]);
                $coursesResult = $stmtCourses->fetch(PDO::FETCH_ASSOC);

                if ($coursesResult && $coursesResult['CourseCount'] > 1) {
                    header("Location: users/student/choose-class.php");
                } elseif ($coursesResult && $coursesResult['CourseCount'] == 1) {
                    header("Location: users/student/home.php");
                } else {
                    header("Location: home.php");
                }
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            // Invalid email or password
            header("Location: login.php?error=invalid_credentials");
            exit;
        }
    } else {
        // If no user is found with the email
        header("Location: login.php?error=invalid_credentials");
        exit;
    }
}

function getUserRoles($userID, $pdo) {
    $roles = [];
    $query = "SELECT RoleName FROM Role JOIN UserRole ON Role.RoleID = UserRole.RoleID WHERE UserID = :userID";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userID' => $userID]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['RoleName'];
    }
    
    return $roles;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>CGA System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="login-header">
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
