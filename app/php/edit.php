<?php

$host = "mysql";
$user = "root";
$password = "pass";
$database = "boarddata";

$mysqli = new mysqli($host, $user, $password, $database);
if($mysqli->connect_errno)
{
	echo "DB接続失敗". $mysqli->connect_error;
}

if(!$_GET["getid"])
{
	echo "IDが存在しません";
}
$id = $_GET["getid"];

$data = $mysqli->query("SELECT * FROM datas WHERE id = $id");

if(!$data)
{
	echo "データが存在しません";
}

$name = $_GET["name"];
$message = $_GET["message"];

$data = $mysqli->query("UPDATE datas SET name='$name',message='$message' WHERE id = $id");
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>掲示板編集</title>
	<link href="style.css" rel="stylesheet">
</head>
<center>
<h1 class="title">掲示板</h1>
<section>
    <h2>編集完了</h2>
    <button class="button1" onclick="location.href='users.php'">戻る</button>
</section>
</center>
