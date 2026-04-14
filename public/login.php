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

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $account);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success = 0;
$msg = '';
if ($user && $user['password'] === $password) {
	$_SESSION['username'] = $account;
	$success = 1;
	$msg = '登入成功';
	echo "<script>alert('登入成功');location.href='index.html';</script>";
} else {
	$msg = '帳號或密碼錯誤';
	echo "<script>alert('帳號或密碼錯誤');location.href='login.html';</script>";
}

// 寫入登入紀錄
$stmt2 = $conn->prepare("INSERT INTO dblog (username, login_time, success, message) VALUES (?, NOW(), ?, ?)");
$stmt2->bind_param("sis", $account, $success, $msg);
$stmt2->execute();

$stmt->close();
$stmt2->close();
$conn->close();
?>