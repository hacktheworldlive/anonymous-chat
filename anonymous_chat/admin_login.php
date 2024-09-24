<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    if ($password == 'admin_password') { // Change this to your actual admin password
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        echo "Invalid password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <form method="post" action="">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
