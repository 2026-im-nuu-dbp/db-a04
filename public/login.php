<?php
session_start();
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "db-a04";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
	die("連線失敗: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM dbusers WHERE username = ?");
$stmt->bind_param("s", $account);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success = 0;
$msg = '';
if ($user && $user['password'] === $password) {
	$_SESSION['username'] = $account;
	$_SESSION['user_id'] = $user['id'];
	$success = 1;
	$msg = '登入成功';
	echo "<script>alert('登入成功');location.href='home.php';</script>";
} else {
	$msg = '帳號或密碼錯誤';
	echo "<script>alert('帳號或密碼錯誤');location.href='login.html';</script>";
}

// 寫入登入紀錄
$stmt2 = $conn->prepare("INSERT INTO dblog (username, login_time, success) VALUES (?, NOW(), ?)");
$stmt2->bind_param("si", $account, $success);
$stmt2->execute();

$stmt->close();
$stmt2->close();
$conn->close();
?>