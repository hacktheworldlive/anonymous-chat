<?php
include 'config.php';
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Generar un nuevo enlace de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_link'])) {
    $invitation_link = bin2hex(random_bytes(16)); // Genera un enlace único
    $usage_limit = $_POST['usage_limit'];
    $stmt = $pdo->prepare("INSERT INTO invitations (invitation_link, usage_limit) VALUES (:invitation_link, :usage_limit)");
    $stmt->execute(['invitation_link' => $invitation_link, 'usage_limit' => $usage_limit]);
}

// Eliminar un enlace de registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_link'])) {
    $invitation_link_id = $_POST['invitation_link_id'];
    $stmt = $pdo->prepare("DELETE FROM invitations WHERE id = :id");
    $stmt->execute(['id' => $invitation_link_id]);
}

// Recuperar todos los enlaces de invitación
$stmt = $pdo->prepare("SELECT * FROM invitations ORDER BY id DESC");
$stmt->execute();
$invitation_links = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
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
            <li><a href="announcements.php">Manage Announcements</a></li>
            <li><a href="invitations.php" class="thisli">Manage Invitations</a></li>  
            <li><a href="manage_accounts.php">Manage Accounts</a></li>  
        </ul>
    </nav>
    <h2>Generar Nuevo Enlace de Invitación</h2>
    <form method="post" action="">
        <label for="usage_limit">Tipo de Enlace:</label>
        <select name="usage_limit" id="usage_limit">
            <option value="1">Único Uso</option>
            <option value="0">Uso Indefinido</option>
        </select>
        <button type="submit" name="generate_link">Generar Enlace</button>
    </form>
    
    <h2>Enlaces de Invitación Activos</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Enlace de Invitación</th>
                <th>Tipo de Uso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invitation_links as $invitation_link) { ?>
                <tr>
                    <td><?php echo $invitation_link['id']; ?></td>
                    <td><a href="register.php?invitation=<?php echo $invitation_link['invitation_link']; ?>">
                        register.php?invitation=<?php echo $invitation_link['invitation_link']; ?>
                    </a></td>
                    <td><?php echo $invitation_link['usage_limit'] == 1 ? 'Único Uso' : 'Uso Indefinido'; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="invitation_link_id" value="<?php echo $invitation_link['id']; ?>">
                            <button type="submit" name="delete_link">Eliminar Enlace</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
