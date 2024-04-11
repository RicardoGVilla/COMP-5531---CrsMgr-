<?php
session_start(); // Start the session.
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Retrieving form data
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            // Generating a 6-character random password
            $password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6/strlen($x)))), 1, 6);

            try {
                // Inserting the user into the database with NewUser set to TRUE
                $query = "INSERT INTO User (Name, EmailAddress, Password, NewUser) VALUES (:name, :email, :password, TRUE)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':name' => $name, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

                // Obtaining the ID of the newly inserted user
                $userID = $pdo->lastInsertId();

                // Associating the user with their role
                $queryUserRole = "INSERT INTO UserRole (UserID, RoleID) VALUES (:userID, :roleID)";
                $stmtUserRole = $pdo->prepare($queryUserRole);
                $stmtUserRole->execute([':userID' => $userID, ':roleID' => $role]);

                $_SESSION['message'] = "User added successfully. Password emailed to $email (Role: $role). Password is $password";
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage(); // Setting the error message if an exception occurs
            }
        } elseif ($_POST['action'] === 'delete') {
            $userID = $_POST['user_id'];
            
            try {
                // Deleting the user from the database
                $query = "DELETE FROM User WHERE UserID = :userID";
                $stmt = $pdo->prepare($query);
                if(!$stmt->execute([':userID' => $userID])){
                    throw new Exception('Failed to delete the user.');
                }
                if($stmt->rowCount() == 0){
                    throw new Exception("User ID {$userID} not found.");
                }
                $_SESSION['message'] = "User deleted successfully";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'update') {
            $userID = $_POST['user_id'];
            $newName = $_POST['new_name'];
            $newEmail = $_POST['new_email'];
            $newPassword = $_POST['new_password'];
            $newRole = $_POST['new_role'];
        
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

                if ($newRole) {
                    $queryUpdateUserRole = "UPDATE UserRole SET RoleID = :roleID WHERE UserID = :userID";
                    $stmtUpdateUserRole = $pdo->prepare($queryUpdateUserRole);
                    $stmtUpdateUserRole->execute([':roleID' => $newRole, ':userID' => $userID]);
                }

                $pdo->commit();
                $_SESSION['message'] = "User updated successfully";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Error updating user: " . $e->getMessage();
            }
        }
    }

    header('Location: manage_user.php');
    exit;
}