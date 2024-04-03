<?php

session_start(); 
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if action is set
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $userID = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$userID) {
            $_SESSION['error'] = "User ID not provided.";
            header('Location: manage_user.php');
            exit;
        }
        
        $newName = $_POST['new_name'];
        $newEmail = $_POST['new_email'];
        $newPassword = $_POST['new_password'];
        $newRole = $_POST['new_role'];

        try {
            $pdo->beginTransaction();
    
            // Start building the update query for user details
            $updateUserQuery = "UPDATE `User` SET Name = :newName, EmailAddress = :newEmail";
    
            // Parameters for the SQL statement
            $params = [
                ':newName' => $newName,
                ':newEmail' => $newEmail,
                ':userID' => $userID
            ];
    
            // Conditionally add password to the update statement if provided
            if (!empty($newPassword)) {
                $updateUserQuery .= ", Password = :newPassword";
                $params[':newPassword'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
    
            // Finalize the update statement with the WHERE clause
            $updateUserQuery .= " WHERE UserID = :userID";
    
            $stmtUpdateUser = $pdo->prepare($updateUserQuery);
            $stmtUpdateUser->execute($params);

            // Check existing roles for the user
            $existingRolesQuery = "SELECT RoleID FROM UserRole WHERE UserID = :userID";
            $stmtExistingRoles = $pdo->prepare($existingRolesQuery);
            $stmtExistingRoles->execute([':userID' => $userID]);
            $existingRoles = $stmtExistingRoles->fetchAll(PDO::FETCH_COLUMN, 0);

            // If the new role is not in the user's existing roles, update or insert as necessary
            if (!in_array($newRole, $existingRoles)) {
                // If user has only one role, update it
                if (count($existingRoles) == 1) {
                    $queryUpdateUserRole = "UPDATE UserRole SET RoleID = :newRole WHERE UserID = :userID AND RoleID = :currentRoleID";
                    $stmtUpdateUserRole = $pdo->prepare($queryUpdateUserRole);
                    $stmtUpdateUserRole->execute([':newRole' => $newRole, ':userID' => $userID, ':currentRoleID' => $existingRoles[0]]);
                } else {
                    // If user has multiple roles, add the new role (we added this logic to avoid duplicating roles)
                    $queryInsertUserRole = "INSERT INTO UserRole (UserID, RoleID) VALUES (:userID, :newRole)";
                    $stmtInsertUserRole = $pdo->prepare($queryInsertUserRole);
                    $stmtInsertUserRole->execute([':userID' => $userID, ':newRole' => $newRole]);
                }
            }
    
            $pdo->commit();
            $_SESSION['message'] = "User updated successfully";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Error updating user: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Invalid action.";
    }

    // Redirect back to manage_user.php
    header('Location: manage_user.php');
    exit;
}
?>