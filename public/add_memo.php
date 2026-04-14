<?php
session_start();
// 請根據你的資料庫設定修改下方資訊
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
} catch (PDOException $e) {
    die('連線失敗: ' . $e->getMessage());
}

// 假設登入後 user_id 存在 session
if (!isset($_SESSION['user_id'])) {
    die('請先登入');
}
$user_id = $_SESSION['user_id'];

$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$image_path = null;

// 處理圖片上傳
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target = $upload_dir . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_path = $target;
    }
}

$sql = "INSERT INTO dbmemo (memo_id, user_id, title, content, image_path) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$memo_id, $user_id, $title, $content, $image_path]);

header('Location: home.html'); // 新增成功後導回首頁
exit;