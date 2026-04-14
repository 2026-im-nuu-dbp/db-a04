<?php
$usrename = $_POST['username'];
$nickname = $_POST['nickname'];
$password = $_POST['password'];
$gender = $_POST['gender'];
$interest = $_POST['interest'];

$servername="localhost";
$dbusername="root";
$dbpassword="";
$dbname="db-a04";

$conn=new mysqli($servername,$dbusername,$dbpassword,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}

$sql = "INSERT INTO users (username, nickname, password, gender, interest) VALUES ('$usrename', '$nickname', '$password', '$gender', '$interest')";
if ($conn->query($sql) === TRUE) {
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>