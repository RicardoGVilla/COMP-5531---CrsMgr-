<?php
session_start();
require_once '../../database.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_SESSION['UserID']; 
    $recipients = explode(',', $_POST['recipients']);
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    try {
        $pdo->beginTransaction();

        // Insert email into InternalEmail table
        $stmt = $pdo->prepare("INSERT INTO InternalEmail (SenderID, Subject, Body) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $subject, $body]);
        $email_id = $pdo->lastInsertId();

        // Insert recipients into EmailRecipient table
        $stmt = $pdo->prepare("INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES (?, ?)");
        foreach ($recipients as $recipient_id) {
            $stmt->execute([$email_id, trim($recipient_id)]);
        }

        $pdo->commit();
        echo "Email sent successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error sending email: " . $e->getMessage());
    }
}
?>
