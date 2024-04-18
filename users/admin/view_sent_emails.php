<?php
session_start();
require_once '../../database.php'; // Adjust path as needed

if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['email_id'])) {
    $email_id = $_GET['email_id'];
    $stmt = $pdo->prepare("SELECT InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, GROUP_CONCAT(`User`.Name SEPARATOR ', ') AS Recipients FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON EmailRecipient.RecipientID = `User`.UserID WHERE InternalEmail.EmailID = ? GROUP BY InternalEmail.EmailID");
    $stmt->execute([$email_id]);
    $email = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sent Email</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="email-content">
        <?php if (isset($email)): ?>
            <h1><?php echo htmlspecialchars($email['Subject']); ?></h1>
            <p><strong>To:</strong> <?php echo htmlspecialchars($email['Recipients']); ?></p>
            <p><strong>Sent:</strong> <?php echo $email['Timestamp']; ?></p>
            <p><?php echo nl2br(htmlspecialchars($email['Body'])); ?></p>
        <?php else: ?>
            <p>Email not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
