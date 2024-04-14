<?php
session_start();
require_once '../../database.php';

$user_id = $_SESSION['UserID'];

try {
    // Fetch all received emails
    $stmt = $pdo->prepare("SELECT InternalEmail.EmailID, InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, InternalEmail.SenderID, `User`.Name AS sender_name, IF(EmailRead.EmailID IS NULL, 'Unread', 'Read') AS Status
        FROM InternalEmail
        JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID
        JOIN `User` ON InternalEmail.SenderID = `User`.UserID
        LEFT JOIN EmailRead ON InternalEmail.EmailID = EmailRead.EmailID AND EmailRead.RecipientID = ?
        WHERE EmailRecipient.RecipientID = ?
        ORDER BY InternalEmail.Timestamp DESC");
    $stmt->execute([$user_id, $user_id]);
    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($emails as $email) {
        echo "From: " . htmlspecialchars($email['sender_name']) . "<br>"
           . "Subject: " . htmlspecialchars($email['Subject']) . "<br>"
           . "Received: " . $email['Timestamp'] . "<br>"
           . "Status: " . $email['Status'] . "<br>"
           . "<a href='view_email.php?email_id=" . $email['EmailID'] . "'>View Email</a> | "
           . "<a href='delete_email.php?email_id=" . $email['EmailID'] . "'>Delete</a><br><br>";
    }
} catch (PDOException $e) {
    die("Error retrieving emails: " . $e->getMessage());
}
?>
