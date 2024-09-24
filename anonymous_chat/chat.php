<?php
include 'config.php';
session_start();

if (!isset($_SESSION['chat_hash'])) {
    header("Location: login.php");
    exit();
}

$chat_hash = $_SESSION['chat_hash'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $encrypted_message = encrypt($message);
    $stmt = $pdo->prepare("INSERT INTO messages (chat_hash, message) VALUES (:chat_hash, :message)");
    $stmt->execute(['chat_hash' => $chat_hash, 'message' => $encrypted_message]);
    header("Location: chat.php");  // Redirect after message is sent to avoid resubmission on refresh
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Room</title>
    <link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>
    <h1>Chat Room</h1>
<div id="login">
<p><a href="index.php">Home</a></p>
<div class="logout">
        <form method="post" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
    <div id="chat-box">
        <?php
        echo '<iframe class="iframe" src="fetch_messages.php#end" width="100%" height="100%"></iframe>';
        /* $stmt = $pdo->prepare("SELECT * FROM messages ORDER BY timestamp ASC");
        $stmt->execute();
        $messages = $stmt->fetchAll();
        
        foreach ($messages as $message) {
            $decrypted_message = htmlspecialchars(decrypt($message['message']));
            echo "<div class='message'><strong>►</strong> {$decrypted_message}</div>";
            // echo "<div class='message'><strong>{$message['timestamp']}:</strong> {$decrypted_message}</div>";
        } */
        ?>
    </div>
    
    <div id="message-form">
        <form method="post" action="">
            <input type="text" name="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
