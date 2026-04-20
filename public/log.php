<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
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
    $stmt = $pdo->prepare('SELECT login_time, success, message FROM dblog WHERE username = ? ORDER BY login_time DESC');
    $stmt->execute([$username]);
    $logs = $stmt->fetchAll();
} catch (Exception $e) {
    $logs = [];
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>登入紀錄</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .log-box { border: 1px solid #4CAF50; border-radius: 6px; padding: 16px; margin-bottom: 16px; background: #f9fff9; }
        .success { color: #388E3C; font-weight: bold; }
        .fail { color: #d32f2f; font-weight: bold; }
        .msg { color: #888; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>登入紀錄</h2>
        <?php if (empty($logs)): ?>
            <div style="color:#888">尚無登入紀錄</div>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <div class="log-box">
                    <div><b>時間：</b><?= htmlspecialchars($log['login_time']) ?></div>
                    <div>
                        <b>狀態：</b>
                        <?php if ($log['success']): ?>
                            <span class="success">成功</span>
                        <?php else: ?>
                            <span class="fail">失敗</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($log['message'])): ?>
                        <div class="msg">訊息：<?= htmlspecialchars($log['message']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div style="text-align:center; margin-top:24px;">
            <a href="home.html" style="color:#4CAF50; text-decoration:none;">回主頁</a>
        </div>
    </div>
</body>
</html>
