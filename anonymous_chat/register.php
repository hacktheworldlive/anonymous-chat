<?php
include 'config.php';

$invitation_link = $_GET['invitation'];

$stmt = $pdo->prepare("SELECT * FROM invitations WHERE invitation_link = :invitation_link");
$stmt->execute(['invitation_link' => $invitation_link]);
$invitation = $stmt->fetch();

if (!$invitation) {
    echo "Invalid invitation link.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = bin2hex(random_bytes(5)); // Generate a random password
    $encrypted_password = encrypt($password);

    $chat_hash = bin2hex(random_bytes(16)); // Generate a unique hash for the chat

    $stmt = $pdo->prepare("INSERT INTO chat_rooms (chat_hash, password) VALUES (:chat_hash, :password)");
    $stmt->execute(['chat_hash' => $chat_hash, 'password' => $encrypted_password]);

    echo "Registration successful. Your password is: " . $password;
    echo "<br><a href='login.php'>Login</a>";

    // Delete the used invitation link if it is single-use
    if ($invitation['usage_limit'] == 1) {
        $stmt = $pdo->prepare("DELETE FROM invitations WHERE id = :id");
        $stmt->execute(['id' => $invitation['id']]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <form method="post" action="">
        <button type="submit">Register</button>
    </form>
</body>
</html>
