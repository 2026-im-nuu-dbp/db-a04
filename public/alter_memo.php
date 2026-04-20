<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => '未登入']);
    exit();
}
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
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    if ($action === 'delete' && $id) {
        // 先查出 image_path
        $stmt = $pdo->prepare('SELECT image_path FROM dbmemo WHERE memo_id = ? AND username = ?');
        $stmt->execute([$id, $username]);
        $row = $stmt->fetch();
        if ($row && !empty($row['image_path'])) {
            $img = $row['image_path'];
            if (file_exists($img)) {
                @unlink($img);
            }
        }
        // 再刪除資料
        $stmt = $pdo->prepare('DELETE FROM dbmemo WHERE memo_id = ? AND username = ?');
        $stmt->execute([$id, $username]);
        echo json_encode(['success' => true]);
        exit;
    } elseif ($action === 'edit' && $id) {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $stmt = $pdo->prepare('UPDATE dbmemo SET title = ?, content = ? WHERE memo_id = ? AND username = ?');
        $stmt->execute([$title, $content, $id, $username]);
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['error' => '參數錯誤', 'debug' => $_POST]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['error' => '資料庫錯誤: ' . $e->getMessage()]);
}
