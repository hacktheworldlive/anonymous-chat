<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM announcements WHERE id = ?');
$stmt->execute([$id]);
$announcement = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare('UPDATE announcements SET title = ?, content = ? WHERE id = ?');
    $stmt->execute([$title, encrypt($content), $id]);
    header('Location: announcements.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement</title>
</head>
<body>
    <h1>Edit Announcement</h1>
    <form method="post" action="edit_announcement.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required><br/>
        <textarea name="content" required><?php echo htmlspecialchars(decrypt($announcement['content'])); ?></textarea><br/>
        <button type="submit">Save Changes</button>
    </form>
    <a href="admin.php">Back to Admin Panel</a>
</body>
</html>
