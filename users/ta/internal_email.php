<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881


session_start(); 
require_once '../../database.php';

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if the form is submitted for sending a message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send_message"])) {
    // Get the sender's user ID from the session
    $senderID = $_SESSION["user"]["UserID"];
    
    // Get the recipient's user ID from the form (you'll need to implement this)
    $recipientID = $_POST["recipient_id"]; // Assuming you have a form input with name="recipient_id"
    
    // Get the message content from the form
    $messageContent = $_POST["message_content"]; // Assuming you have a form input with name="message_content"
    
    // Insert the message into the database
    $sql = "INSERT INTO Message (SenderID, RecipientID, MessageContent) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$senderID, $recipientID, $messageContent]);
    
    // Redirect to prevent form resubmission
    header("Location: internal_email.php");
    exit;
}

// Retrieve messages for the current user with sender details
$userID = $_SESSION["user"]["UserID"];
$sql = "SELECT m.*, u.UserID AS SenderUserID, u.Name AS SenderName, u.EmailAddress AS SenderEmail, r.RoleName AS SenderRole 
        FROM Message m 
        INNER JOIN User u ON m.SenderID = u.UserID
        INNER JOIN UserRole ur ON u.UserID = ur.UserID
        INNER JOIN Role r ON ur.RoleID = r.RoleID
        WHERE m.RecipientID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$receivedMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Email</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='manage_courses.php'">Course Details</button>
            <button onclick="location.href='manage_student_groups.php'">Manage Student Groups</button>
            <button onclick="location.href='manage_faqs.php'">Manage FAQs</button>
            <button class="is-selected" onclick="location.href='internal_email.php'">Internal Email Communication </button>
        </div>

        <main class="main">
            <h2>Internal Email</h2>
            <div class="table-wrapper">
                <h2>Send Message</h2>
                <form class="inline-form" method="post">
                    <label for="recipient_id">Recipient User ID:</label>
                    <input type="text" id="recipient_id" name="recipient_id" required>
                    
                    <label for="message_content">Message:</label>
                    <textarea id="message_content" name="message_content" rows="4" cols="50" required></textarea>
                    <div>
                        <button class="button is-primary" type="submit" name="send_message">Send Message</button>
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <h2>Received Messages</h2>
                <?php if (empty($receivedMessages)): ?>
                    <p>No messages received.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($receivedMessages as $message): ?>
                            <li>
                                <strong>From:</strong> <?php echo $message['SenderUserID']; ?> - <?php echo $message['SenderName']; ?> (<?php echo $message['SenderEmail']; ?>) - <?php echo $message['SenderRole']; ?><br>
                                <strong>Message:</strong> <?php echo $message['MessageContent']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='choose_course.php'">Change Course</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
