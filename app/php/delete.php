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

if(!$_GET["deleteid"])
{
	echo "IDが存在しません";
}

$id = $_GET["deleteid"];

$data = $mysqli->prepare("DELETE FROM datas WHERE id = ?");
$data->bind_param('i', $id);
$data->execute();

?>

<!DOCTYPE html>
<meta charset="UTF-8">
<title>掲示板</title>
<center>
<h1>掲示板</h1>
<section>
    <h2>削除完了</h2>
    <button onclick="location.href='users.php'">戻る</button>
</section>
</center>