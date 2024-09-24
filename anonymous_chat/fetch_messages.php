<?php
include 'config.php';

$stmt = $pdo->prepare("SELECT * FROM messages ORDER BY timestamp ASC");
$stmt->execute();
$messages = $stmt->fetchAll();

foreach ($messages as $message) {
    $decrypted_message = htmlspecialchars(decrypt($message['message']));
	echo "<div class='message'><strong>►</strong> {$decrypted_message}</div>";
    // echo "<div class='message'><strong>{$message['timestamp']}:</strong> {$decrypted_message}</div>";
}
header('refresh:0.1; fetch_messages.php#fin')
?>
<div id="fin"></div>