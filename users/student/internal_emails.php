<?php
session_start();
require_once '../../database.php'; // Adjust the path as needed

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {

    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$loggedUserId = $_SESSION["user"]["UserID"];
function sendEmail($pdo, $senderID, $recipient_emails, $subject, $body) {
    global $loggedUserId;

    try {
        $pdo->beginTransaction();  // Start transaction

        // Insert email into InternalEmail table
        $stmt = $pdo->prepare("INSERT INTO InternalEmail (SenderID, Subject, Body) VALUES (?, ?, ?)");
        $stmt->execute([$loggedUserId, $subject, $body]);
        $email_id = $pdo->lastInsertId();  // Get the ID of the inserted email

        // Convert email addresses to user IDs
        $recipient_ids = [];
        foreach (explode(',', $recipient_emails) as $recipient_email) {
            $recipient_email = strtolower(trim($recipient_email));  // Clean and prepare email address

            // Fetch the corresponding UserID for the email address
            $userStmt = $pdo->prepare("SELECT UserID FROM `User` WHERE EmailAddress = ?");
            $userStmt->execute([$recipient_email]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $recipient_ids[] = $user['UserID'];  // If user found, add UserID to recipient_ids
            } else {
                throw new Exception("No user found for email address: " . $recipient_email);
            }
        }

        // Insert each recipient into EmailRecipient table
        $recipientStmt = $pdo->prepare("INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES (?, ?)");
        foreach ($recipient_ids as $recipient_id) {
            $recipientStmt->execute([$email_id, $recipient_id]);
        }

        $pdo->commit();  // Commit the transaction if all operations succeed
        return "Email sent successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();  // Rollback the transaction in case of an error
        return "Error sending email: " . $e->getMessage();
    }
}

function fetchInbox($pdo, $user_id) {
    global $loggedUserId;
    $stmt = $pdo->prepare("SELECT InternalEmail.EmailID, InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, `User`.Name AS SenderName FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON InternalEmail.SenderID = `User`.UserID WHERE EmailRecipient.RecipientID = ? ORDER BY InternalEmail.Timestamp DESC");
    $stmt->execute([$loggedUserId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchSentEmails($pdo, $user_id) {
    global $loggedUserId;
    $stmt = $pdo->prepare("SELECT InternalEmail.EmailID, InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, GROUP_CONCAT(`User`.Name SEPARATOR ', ') AS RecipientNames FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON EmailRecipient.RecipientID = `User`.UserID WHERE InternalEmail.SenderID = ? GROUP BY InternalEmail.EmailID ORDER BY InternalEmail.Timestamp DESC");
    $stmt->execute([$loggedUserId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
switch ($action) {
    case 'send_email':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo sendEmail($pdo, $_SESSION['UserID'], $_POST['recipients'], $_POST['subject'], $_POST['body']);
        }
        break;
    case 'view_inbox':
        $emails = fetchInbox($pdo, $_SESSION['UserID']);
        if (!empty($emails)) {
            foreach ($emails as $email) {
                echo "<div class='email-item'>From: " . htmlspecialchars($email['SenderName']) .
                     "<br>Subject: " . htmlspecialchars($email['Subject']) .
                     "<br>Received: " . htmlspecialchars($email['Timestamp']) .
                     "<br><a href='view_email.php?email_id=" . $email['EmailID'] . "'>Read Email</a></div><br>";
            }
        } else {
            echo "No emails found.";
        }
        break;
    case 'view_sent':
        $sent_emails = fetchSentEmails($pdo, $_SESSION['UserID']);
        if (!empty($sent_emails)) {
            foreach ($sent_emails as $email) {
                echo "<div class='email-item'>To: " . htmlspecialchars($email['RecipientNames']) .
                     "<br>Subject: " . htmlspecialchars($email['Subject']) .
                     "<br>Sent: " . htmlspecialchars($email['Timestamp']) .
                     "<br><a href='view_sent_email.php?email_id=" . $email['EmailID'] . "'>View Sent Email</a></div><br>";
            }
        } else {
            echo "No sent emails found.";
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
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($_SESSION['userName']); ?></h1>
        </header>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar buttons -->
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button onclick="location.href='manage-files.php'"> Manage Files </button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>
        <!-- Main content area -->
        <main class="main">
            <div class="email-system">
                <!-- Email form -->
                <div class="email-form">
                    <h2>Send an Email</h2>
                    <form action="?action=send_email" method="post">
                        <input type="hidden" name="action" value="send_email">
                        <label for="recipients">Recipients (Separate email addresses with a comma):</label>
                        <input type="email" id="recipients" name="recipients" required pattern="([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,},\s*)*[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                        <label for="body">Body:</label>
                        <textarea id="body" name="body" rows="4" required></textarea>
                        <button type="submit">Send Email</button>
                    </form>
                </div>
                <!-- Email navigation buttons -->
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

