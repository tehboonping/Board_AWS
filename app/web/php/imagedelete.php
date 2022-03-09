<?php
session_start();

if($_SESSION['enable'])
{
	return;
}

$host = "mysql";
$user = "root";
$password = "pass";
$database = "boarddata";

$mysqli = new mysqli($host, $user, $password, $database);
if($mysqli->connect_errno)
{
	echo "DB接続失敗". $mysqli->connect_error;
}

$id = $_GET["imageid"];
if(!$id)
{
	echo "IDが存在しません";
}

$redis = new Redis();
$redis->connect('redis',6379);

for($i = 1;$i <= $redis->dbsize(); $i++)
{
	$redisdata = $redis->hGetALL('datas'.$i);
	if($redisdata['id'] === $id)
	{
		$redis->hSet('datas'.$i,'image',NULL);
		break;
	}
}

$image = $mysqli->query("SELECT * FROM datas WHERE id=$id");

foreach($image as $row)
{
	$uploaddir = "../images/";
	$filename = $row['image'];
	$filepath = $uploaddir.$filename;
}

if($filename && file_exists($filepath))
{
	if(!unlink($filepath)){ echo "削除失敗"; }
}

$data = $mysqli->query("UPDATE datas SET image=NULL WHERE id = $id");

?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>掲示板画像削除</title>
	<link href="style.css" rel="stylesheet">
</head>
<center>
<h1 class="title">掲示板</h1>
<section>
    <h2>画像削除完了</h2>
    <button class="button1" onclick="location.href='users.php'">戻る</button>
</section>
</center>