<?php
session_start();
require_once '../../database.php';

$user_id = $_SESSION['UserID'];

try {
    $stmt = $pdo->prepare("SELECT InternalEmail.*, `User`.Name AS sender_name FROM InternalEmail
        JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID
        JOIN `User` ON InternalEmail.SenderID = `User`.UserID
        WHERE EmailRecipient.RecipientID = ?");
    $stmt->execute([$user_id]);
    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($emails as $email) {
        echo "From: " . $email['sender_name'] . "<br>Subject: " . $email['Subject'] . "<br>Body: " . $email['Body'] . "<br><br>";
    }
} catch (PDOException $e) {
    die("Error retrieving emails: " . $e->getMessage());
}
?>
