<?php
session_start();
require 'config.php';

// If user is already logged in, redirect them to the chat page
if (isset($_SESSION['chat_hash'])) {
   // exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM chat_rooms WHERE password = ?');
    $stmt->execute([encrypt($password)]);
    $chat_room = $stmt->fetch();

    if ($chat_room) {
        $_SESSION['chat_hash'] = $chat_room['chat_hash'];
        header('Location: chat.php');
        exit();
    } else {
        $error = 'Invalid password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php if (isset($_SESSION['chat_hash'])): ?>
        <p><a href="chat.php">Enter the chat</a></p>
        <div class="logout">
        <form method="post" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
    <?php else: ?>
        <h1>Login</h1>
        <form method="post" action="login.php">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>