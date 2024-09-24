<?php
$host = 'localhost';
$db = 'chat_db'; // Database name
$user = 'root'; // MySQL username
$pass = ''; // MySQL password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

function encrypt($data) {
    return openssl_encrypt($data, 'aes-256-cbc', 'your_secret_key', 0, 'your_iv');
}

function decrypt($data) {
    return openssl_decrypt($data, 'aes-256-cbc', 'your_secret_key', 0, 'your_iv');
}
function make_links_clickable($text) {
    return preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<a href="$1" target="_blank">$1</a>',
        $text
    );
}
?>