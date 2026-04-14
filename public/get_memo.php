<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => '未登入']);
    exit();
}
$username = $_SESSION['username'];

// 資料庫連線參數
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
    $stmt = $pdo->prepare('SELECT id, title, content, created_at FROM dbmemo WHERE username = ? ORDER BY created_at DESC');
    $stmt->execute([$username]);
    $memos = $stmt->fetchAll();
    echo json_encode($memos);
} catch (Exception $e) {
    echo json_encode(['error' => '資料庫錯誤']);
}
