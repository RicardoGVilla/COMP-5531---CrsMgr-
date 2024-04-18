<?php
session_start();
require_once '../../database.php'; // Adjust the path as needed

// Utility Functions
function sendEmail($pdo, $sender_id, $recipient_emails, $subject, $body) {
    try {
        $pdo->beginTransaction();

        // Insert email into InternalEmail table
        $stmt = $pdo->prepare("INSERT INTO InternalEmail (SenderID, Subject, Body) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $subject, $body]);
        $email_id = $pdo->lastInsertId();

        // Convert email addresses to user IDs
        $recipient_ids = [];
        foreach (explode(',', $recipient_emails) as $recipient_email) {
            // Remove whitespace and ensure lowercase for accurate comparison
            $recipient_email = strtolower(trim($recipient_email));

            // Find the user ID associated with the email address
            $userStmt = $pdo->prepare("SELECT UserID FROM `User` WHERE EmailAddress = ?");
            $userStmt->execute([$recipient_email]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $recipient_ids[] = $user['UserID'];
            } else {
                // Handle the case where the email address does not correspond to a user
                throw new Exception("No user found for email address: " . $recipient_email);
            }
        }

        // Insert recipient user IDs into EmailRecipient table
        $recipientStmt = $pdo->prepare("INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES (?, ?)");
        foreach ($recipient_ids as $recipient_id) {
            $recipientStmt->execute([$email_id, $recipient_id]);
        }

        $pdo->commit();
        return "Email sent successfully!";
    } catch (Exception $e) {
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
            // Basic validation to ensure required fields are not empty
            if (!empty($_POST['recipients']) && !empty($_POST['subject']) && !empty($_POST['body'])) {
                // Call the sendEmail function with sanitized inputs
                $message = sendEmail(
                    $pdo, 
                    $_SESSION['UserID'], 
                    filter_var($_POST['recipients'], FILTER_SANITIZE_EMAIL), // Though this is not ideal for a list, further processing is assumed inside the function
                    filter_var($_POST['subject'], FILTER_SANITIZE_STRING),
                    filter_var($_POST['body'], FILTER_SANITIZE_STRING)
                );
                echo $message;
            } else {
                echo "Error: All fields are required!";
            }
        }
        break;
    case 'view_inbox':
        $emails = fetchInbox($pdo, $_SESSION['UserID']);
        foreach ($emails as $email) {
            echo "<div class='email-item'>From: " . htmlspecialchars($email['SenderName']) .
                 "<br>Subject: " . htmlspecialchars($email['Subject']) .
                 "<br>Received: " . htmlspecialchars($email['Timestamp']) .
                 "<br><a href='view_email.php?email_id=" . $email['EmailID'] . "'>Read Email</a></div><br>";
        }
        break;
    case 'view_sent':
        $sent_emails = fetchSentEmails($pdo, $_SESSION['UserID']);
        foreach ($sent_emails as $email) {
            echo "<div class='email-item'>To: " . htmlspecialchars($email['RecipientNames']) .
                 "<br>Subject: " . htmlspecialchars($email['Subject']) .
                 "<br>Sent: " . htmlspecialchars($email['Timestamp']) .
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
                        <label for="recipients">Recipients (Seperate email addresses with a comma):</label>
                        <input type="email" id="recipients" name="recipients" required multiple pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
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


