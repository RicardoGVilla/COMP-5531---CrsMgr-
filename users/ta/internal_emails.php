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
    <title>Email System - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css"> <!-- Ensure this path is correct -->
    <style>
        /* Additional styles specific to the email system */
        .email-system {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            padding: 20px;
        }
        .email-form h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .email-form label {
            font-weight: 600;
            color: #555;
        }
        .email-form input,
        .email-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        .email-form button {
            width: auto;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .email-form button:hover {
            background-color: #003975;
        }
        .email-navigation {
            text-align: center;
            padding-top: 20px;
        }
        .email-navigation button {
            margin: 0 10px;
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        .email-navigation button:hover {
            background-color: #003975;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <header class="header">
            <h1>Welcome TA <?php echo htmlspecialchars($userName); ?></h1>
        </header>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar buttons -->
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>

        <!-- Main content area -->
        <main class="main">
            <div class="email-system">
                <!-- Email form -->
                <div class="email-form">
                    <h2>Send an Email</h2>
                    <form action="send_email.php" method="post">
                        <input type="hidden" name="action" value="send_email">
                        <label for="recipients">Recipients (comma-separated IDs):</label>
                        <input type="text" id="recipients" name="recipients" required>
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                        <label for="body">Body:</label>
                        <textarea id="body" name="body" rows="4" required></textarea>
                        <button type="submit">Send Email</button>
                    </form>
                </div>

                <!-- Email navigation buttons -->
                <div class="email-navigation">
                    <button onclick="location.href='internal_emails.php?action=view_inbox';">View Inbox</button>
                    <button onclick="location.href='internal_emails.php?action=view_sent';">View Sent Emails</button>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>


