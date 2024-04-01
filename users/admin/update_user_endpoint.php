<?php
session_start(); // Start the session.
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
    
            // Start building the update query
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

            // Update user role if it's changed
            $queryUpdateUserRole = "UPDATE UserRole SET RoleID = :roleID WHERE UserID = :userID";
            $stmtUpdateUserRole = $pdo->prepare($queryUpdateUserRole);
            $stmtUpdateUserRole->execute([':roleID' => $newRole, ':userID' => $userID]);
    
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
