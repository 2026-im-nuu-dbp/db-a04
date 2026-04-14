<?php
session_start();
// 假設登入時已將 username 存入 session
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$nickname = '';

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
?>
<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <title>個人主頁</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        input,
        select,
        textarea,
        button {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin: 8px 0;
        }

        button {
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #388E3C;
        }

        .link {
            text-align: right;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <header style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px;">
        <h1 style="margin: 0 auto;">你好，<?php echo htmlspecialchars($nickname); ?>！</h1>
        <a href="login_records.html" style="margin-left: auto; text-decoration: none; color: #4CAF50; font-size: 16px;">登入紀錄</a>
    </header>
    <div class="container">
        <button onclick="location.href='add_memo.html'">新增圖文記事</button>
        <div id="memo-list" style="margin-top:24px; max-height:300px; overflow-y:auto;"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('get_memo.php')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('memo-list');
                    if (data.error) {
                        list.innerHTML = '<div style="color:red">' + data.error + '</div>';
                        return;
                    }
                    if (data.length === 0) {
                        list.innerHTML = '<div style="color:#888">尚無記事</div>';
                        return;
                    }
                    list.innerHTML = data.map(memo =>
                        `<div style="border-bottom:1px solid #eee; padding:8px 0;">
                        <div style="font-weight:bold;">${memo.title || '(無標題)'}</div>
                        <div style="white-space:pre-line; color:#333; margin:4px 0;">${memo.content || ''}</div>
                        <div style="font-size:12px; color:#888;">${memo.created_at || ''}</div>
                    </div>`
                    ).join('');
                })
                .catch(() => {
                    document.getElementById('memo-list').innerHTML = '<div style="color:red">無法取得記事</div>';
                });
        });
    </script>
</body>

</html>