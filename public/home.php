<?php
session_start();
header('Content-Type: application/json');
$nickname = '';
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $host = 'localhost';
    $db   = 'db-a04';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $stmt = $pdo->prepare('SELECT nickname FROM dbusers WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if ($row) {
            $nickname = $row['nickname'];
        } else {
            $nickname = $username;
        }
    } catch (Exception $e) {
        $nickname = $username;
    }
}
echo json_encode(['nickname' => $nickname]);
