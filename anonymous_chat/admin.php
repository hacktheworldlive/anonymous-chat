<?php
include 'config.php';
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Manejar las acciones del administrador
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_message'])) {
        $message_id = $_POST['message_id'];
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->execute(['id' => $message_id]);
    } elseif (isset($_POST['delete_messages_by_hash'])) {
        $chat_hash = $_POST['chat_hash'];
        $stmt = $pdo->prepare("DELETE FROM messages WHERE chat_hash = :chat_hash");
        $stmt->execute(['chat_hash' => $chat_hash]);
    } elseif (isset($_POST['delete_account'])) {
        $chat_hash = $_POST['chat_hash'];
        $stmt = $pdo->prepare("DELETE FROM chat_rooms WHERE chat_hash = :chat_hash");
        $stmt->execute(['chat_hash' => $chat_hash]);
        $stmt = $pdo->prepare("DELETE FROM messages WHERE chat_hash = :chat_hash");
        $stmt->execute(['chat_hash' => $chat_hash]);
    } elseif (isset($_POST['clear_chat'])) {
        $stmt = $pdo->prepare("DELETE FROM messages");
        $stmt->execute();
    }
}

// Recuperar todos los mensajes
$stmt = $pdo->prepare("SELECT * FROM messages ORDER BY timestamp ASC");
$stmt->execute();
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="logout">
        <form method="post" action="admin_logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
    
    <h1>Admin Panel</h1>
    
<nav>
        <ul>
            <li><a href="admin.php" class="thisli">Manage Chat</a></li>
            <li><a href="announcements.php">Manage Announcements</a></li>
            <li><a href="invitations.php">Manage Invitations</a></li>  
            <li><a href="manage_accounts.php">Manage Accounts</a></li>  
        </ul>
    </nav>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Timestamp</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $message) { 
                $decrypted_message = htmlspecialchars(decrypt($message['message']));
            ?>
                <tr>
                    <td><?php echo $message['id']; ?></td>
                    <td><?php echo $decrypted_message; ?></td>
                    <td><?php echo $message['timestamp']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                            <button type="submit" name="delete_message">Delete Message</button>
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="chat_hash" value="<?php echo $message['chat_hash']; ?>">
                            <button type="submit" name="delete_messages_by_hash">Delete All Messages with Same Hash</button>
                        </form>
                        <form method="post" action="">
                            <input type="hidden" name="chat_hash" value="<?php echo $message['chat_hash']; ?>">
                            <button type="submit" name="delete_account">Delete Account Associated with Message</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <form method="post" action="">
        <button type="submit" name="clear_chat">Clear Chat</button>
    </form>
</body>
</html>