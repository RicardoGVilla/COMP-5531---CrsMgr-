<?php
session_start();
require_once '../../database.php';

$user_id = $_SESSION['UserID'];

try {
    $stmt = $pdo->prepare("SELECT InternalEmail.*, `User`.Name AS receiver_name FROM InternalEmail
        JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID
        JOIN `User` ON EmailRecipient.RecipientID = `User`.UserID
        WHERE InternalEmail.SenderID = ?");
    $stmt->execute([$user_id]);
    $sent_emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($sent_emails as $email) {
        echo "To: " . $email['receiver_name'] . "<br>Subject: " . $email['Subject'] . "<br>Body: " . $email['Body'] . "<br><br>";
    }
} catch (PDOException $e) {
    die("Error retrieving sent emails: " . $e->getMessage());
}
?>
