<?php
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

// Retrieve messages for the current user
$userID = $_SESSION["user"]["UserID"];
$sql = "SELECT * FROM Message WHERE RecipientID = ?";
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
            <h1>Internal Email</h1>
        </header>
        
        <div class="sidebar">
            <button onclick="location.href='create_user.php'">Manage Users</button>
            <button onclick="location.href='manage_user.php'">Manage Roles</button>
            <button onclick="location.href='manage_courses.php'">Manage Courses</button>
            <button onclick="location.href='manage_sections.php'">Manage Sections</button>
            <button onclick="location.href='manage_groups.php'">Manage Groups</button>
            <button onclick="location.href='manage_announcements.php'">Course Announcements</button>
            <button onclick="location.href='manage_faqs.php'">FAQ Management</button>
            <button onclick="location.href='enrolling_students.php'">Course Enrollment</button>
            <button onclick="location.href='logs.php'">User Logs</button>
            <button class="is-selected" onclick="location.href='internal_email.php'">Internal Communication</button>
        </div>

        <main class="main">
            <h2>Send Message</h2>
            <form method="post">
                <label for="recipient_id">Recipient User ID:</label>
                <input type="text" id="recipient_id" name="recipient_id" required>
                
                <label for="message_content">Message:</label>
                <textarea id="message_content" name="message_content" rows="4" cols="50" required></textarea>
                
                <button type="submit" name="send_message">Send Message</button>
            </form>
        
            <h2>Received Messages</h2>
            <?php if (empty($receivedMessages)): ?>
                <p>No messages received.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($receivedMessages as $message): ?>
                        <li>
                            <strong>From:</strong> <?php echo $message['SenderID']; ?><br>
                            <strong>Message:</strong> <?php echo $message['MessageContent']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
