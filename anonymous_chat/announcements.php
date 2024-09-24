<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Process to add a new announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $pdo->prepare('INSERT INTO announcements (title, content) VALUES (?, ?)');
    $stmt->execute([$title, encrypt($content)]);
    header('Location: announcements.php');
    exit();
}

// Process to delete an announcement
if (isset($_GET['delete_announcement'])) {
    $id = $_GET['delete_announcement'];
    $stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: announcements.php');
    exit();
}

// Process to edit an announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_announcement'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare('UPDATE announcements SET title = ?, content = ? WHERE id = ?');
    $stmt->execute([$title, encrypt($content), $id]);
    header('Location: announcements.php');
    exit();
}

$stmt = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC');
$announcements = $stmt->fetchAll();
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
            <li><a href="admin.php">Manage Chat</a></li>
            <li><a href="announcements.php" class="thisli">Manage Announcements</a></li>
            <li><a href="invitations.php">Manage Invitations</a></li>  
            <li><a href="manage_accounts.php">Manage Accounts</a></li>  
        </ul>
    </nav>
    <h2>Announcements</h2>
    <form method="post" action="announcements.php">
        <input type="hidden" name="add_announcement" value="1">
        <input type="text" name="title" placeholder="Title" required><br/>
        <textarea name="content" placeholder="Content" required></textarea><br/>
        <button type="submit">Post Announcement</button>
    </form>

    <h3>Existing Announcements</h3>
       <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($announcements as $announcement): ?>
    	<tr>
                    <td><?php echo htmlspecialchars($announcement['title']); ?></td>
                    <td><?php echo make_links_clickable(htmlspecialchars(decrypt($announcement['content']))); ?></td>
                    <td><small>Posted on <?php echo $announcement['created_at']; ?></small></td>
                    <td><a href="announcements.php?delete_announcement=<?php echo $announcement['id']; ?>" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
            <a href="edit_announcement.php?id=<?php echo $announcement['id']; ?>">Edit</a></td>
                </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
</body>
</html>
