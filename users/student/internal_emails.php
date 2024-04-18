<?php
session_start();
require_once '../../database.php'; // Adjust the path as needed

function sendEmail($pdo, $sender_id, $recipient_emails, $subject, $body) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO InternalEmail (SenderID, Subject, Body) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $subject, $body]);
        $email_id = $pdo->lastInsertId();

        $recipient_ids = [];
        foreach (explode(',', $recipient_emails) as $recipient_email) {
            $recipient_email = strtolower(trim($recipient_email));
            $userStmt = $pdo->prepare("SELECT UserID FROM `User` WHERE EmailAddress = ?");
            $userStmt->execute([$recipient_email]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $recipient_ids[] = $user['UserID'];
            } else {
                throw new Exception("No user found for email address: " . $recipient_email);
            }
        }

        $recipientStmt = $pdo->prepare("INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES (?, ?)");
        foreach ($recipient_ids as $recipient_id) {
            $recipientStmt->execute([$email_id, $recipient_id]);
        }

        $pdo->commit();
        return "Email sent successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error sending email: " . $e->getMessage();
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

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$messages = [];
switch ($action) {
    case 'send_email':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $messages[] = sendEmail($pdo, $_SESSION['UserID'], $_POST['recipients'], $_POST['subject'], $_POST['body']);
        }
        break;
    case 'view_inbox':
        $messages = fetchInbox($pdo, $_SESSION['UserID']);
        break;
    case 'view_sent':
        $messages = fetchSentEmails($pdo, $_SESSION['UserID']);
        break;
}

// Continue with the HTML below to show form and messages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email System - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
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
        .email-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($_SESSION['userName']); ?></h1>
        </header>
        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button onclick="location.href='manage-files.php'"> Manage Files </button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>
        <main class="main">
            <div class="email-system">
                <div class="email-form">
                    <h2>Send an Email</h2>
                    <form action="?action=send_email" method="post">
                        <input type="hidden" name="action" value="send_email">
                        <label for="recipients">Recipients (Separate email addresses with a comma):</label>
                        <input type="email" id="recipients" name="recipients" required pattern="([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,},\s*)*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                        <label for="body">Body:</label>
                        <textarea id="body" name="body" rows="4" required></textarea>
                        <button type="submit">Send Email</button>
                    </form>
                </div>
                <div class="email-navigation">
                    <form action="?action=view_inbox" method="get">
                        <input type="hidden" name="action" value="view_inbox">
                        <button type="submit">View Inbox</button>
                    </form>
                    <form action="?action=view_sent" method="get">
                        <input type="hidden" name="action" value="view_sent">
                        <button type="submit">View Sent Emails</button>
                    </form>
                </div>
                <!-- Display area for messages or emails -->
                <?php if (!empty($messages)) : ?>
                    <div class="email-messages">
                        <?php foreach ($messages as $message) {
                            if (is_array($message)) {
                                echo "<div class='email-item'>";
                                echo "From: " . htmlspecialchars($message['SenderName']) . "<br>";
                                echo "Subject: " . htmlspecialchars($message['Subject']) . "<br>";
                                echo "Received: " . htmlspecialchars($message['Timestamp']) . "<br>";
                                echo "<a href='view_email.php?email_id=" . $message['EmailID'] . "'>Read Email</a>";
                                echo "</div><br>";
                            } else {
                                echo "<p>" . htmlspecialchars($message) . "</p>";
                            }
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
