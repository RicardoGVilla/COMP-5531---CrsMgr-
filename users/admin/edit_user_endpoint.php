<?php
session_start();
require_once '../../database.php'; // Ensure this path is correct for database connection

function getUserRoles($userID, $pdo) {
    $roles = [];
    $query = "SELECT RoleName FROM Role JOIN UserRole ON Role.RoleID = UserRole.RoleID WHERE UserID = :userID";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':userID' => $userID]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['RoleName'];
    }
    return $roles;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['user_id'] ?? null;

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6 / strlen($x)))), 1, 6);
                try {
                    $query = "INSERT INTO User (Name, EmailAddress, Password, NewUser) VALUES (:name, :email, :password, TRUE)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':name' => $name, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
                    $userID = $pdo->lastInsertId();
                    $_SESSION['message'] = "User added successfully. Password emailed to $email. Password is $password";
                } catch (PDOException $e) {
                    $_SESSION['error'] = $e->getMessage();
                }
                break;

            case 'delete':
                try {
                    $query = "DELETE FROM User WHERE UserID = :userID";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':userID' => $userID]);
                    $_SESSION['message'] = "User deleted successfully";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
                }
                break;

            case 'update':
                $newName = $_POST['new_name'];
                $newEmail = $_POST['new_email'];
                $newPassword = $_POST['new_password'];
                try {
                    $pdo->beginTransaction();
                    $updateUserQuery = "UPDATE User SET Name = :newName, EmailAddress = :newEmail WHERE UserID = :userID";
                    $params = [':newName' => $newName, ':newEmail' => $newEmail, ':userID' => $userID];

                    if (!empty($newPassword)) {
                        $updateUserQuery .= ", Password = :newPassword";
                        $params[':newPassword'] = password_hash($newPassword, PASSWORD_DEFAULT);
                    }

                    $stmtUpdateUser = $pdo->prepare($updateUserQuery);
                    $stmtUpdateUser->execute($params);
                    $pdo->commit();
                    $_SESSION['message'] = "User updated successfully";
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    $_SESSION['error'] = "Error updating user: " . $e->getMessage();
                }
                break;

            case 'add_role':
                $roleID = $_POST['role_id']; // Role to add
                try {
                    $query = "INSERT INTO UserRole (UserID, RoleID) VALUES (:userID, :roleID)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':userID' => $userID, ':roleID' => $roleID]);
                    $_SESSION['message'] = "Role added successfully.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Error adding role: " . $e->getMessage();
                }
                break;
            
            case 'remove_role':
                $roleID = $_POST['role_id']; // Role to remove
                try {
                    $query = "DELETE FROM UserRole WHERE UserID = :userID AND RoleID = :roleID";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([':userID' => $userID, ':roleID' => $roleID]);
                    if ($stmt->rowCount() > 0) {
                        $_SESSION['message'] = "Role removed successfully.";
                    } else {
                        $_SESSION['error'] = "No role found to remove.";
                    }
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Error removing role: " . $e->getMessage();
                }
                break;
        }
    }

    header('Location: manage_user.php');
    exit;
}
?>
