<?php
session_start();
include_once 'database.php';

// Utility Functions
function sendEmail($pdo, $sender_id, $recipients, $subject, $body) {
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO InternalEmail (SenderID, Subject, Body) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $subject, $body]);
        $email_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES (?, ?)");
        foreach (explode(',', $recipients) as $recipient_id) {
            $stmt->execute([$email_id, trim($recipient_id)]);
        }

        $pdo->commit();
        return "Email sent successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        return "Error sending email: " . $e->getMessage();
    }
}

function fetchInbox($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT InternalEmail.EmailID, InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, `User`.Name AS SenderName FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON InternalEmail.SenderID = `User`.UserID WHERE EmailRecipient.RecipientID = ? ORDER BY InternalEmail.Timestamp DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchSentEmails($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT InternalEmail.EmailID, InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, GROUP_CONCAT(`User`.Name SEPARATOR ', ') AS RecipientNames FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON EmailRecipient.RecipientID = `User`.UserID WHERE InternalEmail.SenderID = ? GROUP BY InternalEmail.EmailID ORDER BY InternalEmail.Timestamp DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handling Requests
$action = $_POST['action'] ?? $_GET['action'] ?? '';
switch ($action) {
    case 'send_email':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = sendEmail($pdo, $_SESSION['UserID'], $_POST['recipients'], $_POST['subject'], $_POST['body']);
            echo $message;
        }
        break;
    case 'view_inbox':
        $emails = fetchInbox($pdo, $_SESSION['UserID']);
        foreach ($emails as $email) {
            echo "<div class='email-item'>From: " . htmlspecialchars($email['SenderName']) .
                 "<br>Subject: " . htmlspecialchars($email['Subject']) .
                 "<br>Received: " . $email['Timestamp'] .
                 "<br><a href='view_email.php?email_id=" . $email['EmailID'] . "'>Read Email</a></div><br>";
        }
        break;
    case 'view_sent':
        $sent_emails = fetchSentEmails($pdo, $_SESSION['UserID']);
        foreach ($sent_emails as $email) {
            echo "<div class='email-item'>To: " . htmlspecialchars($email['RecipientNames']) .
                 "<br>Subject: " . htmlspecialchars($email['Subject']) .
                 "<br>Sent: " . $email['Timestamp'] .
                 "<br><a href='view_sent_email.php?email_id=" . $email['EmailID'] . "'>View Sent Email</a></div><br>";
        }
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TA Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f4f4f4; }
        h1, h2 { color: #333; }
        .content, form, .sidebar, header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 20px; }
        label, input, textarea, button { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #0056b3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #003975; }
        .email-item { margin-bottom: 20px; padding: 10px; background-color: #fff; border-left: 5px solid #0056b3; }
        .sidebar { width: 200px; float: left; margin-right: 20px; }
        .page { display: flex; justify-content: start; align-items: flex-start; }
        .main { flex-grow: 1; }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome TA <?php echo htmlspecialchars($userName); ?></h1>
            <p>You are signed in as a Teaching Assistant</p>
        </header>
        
        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>

        <div class="main">
            <!-- Form to Send Email -->
            <div class="content">
                <h2>Send an Email</h2>
                <form action="send_email.php" method="post">
                    <input type="hidden" name="action" value="send_email">
                    <label for="recipients">Recipients (comma-separated IDs):</label>
                    <input type="text" id="recipients" name="recipients" required>
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                    <label for="body">Body:</label>
                    <textarea id="body" name="body" rows="6" required></textarea>
                    <button type="submit">Send Email</button>
                </form>
            </div>

            <!-- Buttons for Navigation -->
            <div class="content">
                <h2>Email Navigation</h2>
                <button onclick="window.location.href='email_system.php?action=view_inbox';">View Inbox</button>
                <button onclick="window.location.href='email_system.php?action=view_sent';">View Sent Emails</button>
            </div>
        </div>

        <!-- Container to Display Emails -->
        <div class="content" id="emailDisplay"></div>
    </div>
</body>
</html>


