<?php
// require 'config.php';

$stmt = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC');
$announcements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements</title>
</head>
<body>
    <h2>Announcements</h2>
    <?php foreach ($announcements as $announcement): ?>
        <div class="new">
            <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
            <p><?php echo make_links_clickable(htmlspecialchars(decrypt($announcement['content']))); ?></p>
            <p><small>Posted on <?php echo $announcement['created_at']; ?></small></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
