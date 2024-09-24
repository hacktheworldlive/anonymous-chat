<?php
include 'config.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $chat_hash = $_POST['chat_hash'];
    $stmt = $pdo->prepare("DELETE FROM chat_rooms WHERE chat_hash = :chat_hash");
    $stmt->execute(['chat_hash' => $chat_hash]);
    $stmt = $pdo->prepare("DELETE FROM messages WHERE chat_hash = :chat_hash");
    $stmt->execute(['chat_hash' => $chat_hash]);
}

// Retrieve all accounts with message count
$stmt = $pdo->prepare("
    SELECT cr.chat_hash, COUNT(m.id) AS message_count
    FROM chat_rooms cr
    LEFT JOIN messages m ON cr.chat_hash = m.chat_hash
    GROUP BY cr.chat_hash
    ORDER BY cr.chat_hash ASC
");
$stmt->execute();
$accounts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Accounts</title>
    <link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="logout">
        <form method="post" action="admin_logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
    
    <h1>Manage Accounts</h1>
    
    <nav>
        <ul>
            <li><a href="admin.php">Manage Chat</a></li>
            <li><a href="announcements.php">Manage Announcements</a></li>
            <li><a href="invitations.php">Manage Invitations</a></li>
            <li><a href="manage_accounts.php" class="thisli">Manage Accounts</a></li>
        </ul>
    </nav>
    <table>
        <thead>
            <tr>
                <th>Account Hash</th>
                <th>Message Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $account) { ?>
                <tr>
                    <td><?php echo $account['chat_hash']; ?></td>
                    <td><?php echo $account['message_count']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="chat_hash" value="<?php echo $account['chat_hash']; ?>">
                            <button type="submit" name="delete_account">Delete Account</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
