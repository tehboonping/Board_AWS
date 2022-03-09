<?php
session_start();

if($_SESSION['enable'])
{
	return;
}

$host = "boarddatabase.cchpc7kznfed.ap-northeast-1.rds.amazonaws.com";
$user = "root";
$password = "password";
$database = "boarddata";

$mysqli = new mysqli($host, $user, $password, $database);
if($mysqli->connect_errno)
{
	echo "DB接続失敗". $mysqli->connect_error;
}

$id = $_GET["deleteid"];
if(!$id)
{
	echo "IDが存在しません";
}

$redis = new RedisCluster(NULL,['boardredis.67kw0i.clustercfg.apne1.cache.amazonaws.com:6379']);

for($i = 1;$i <= $redis->dbsize(); $i++)
{
	$redisdata = $redis->hGetALL('datas'.$i);
	if($redisdata['id'] === $id)
	{
		$redis->del('datas'.$i);
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

$data = $mysqli->prepare("DELETE FROM datas WHERE id = ?");
$data->bind_param('i', $id);
$data->execute();

?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>掲示板削除</title>
	<link href="style.css" rel="stylesheet">
</head>
<center>
<h1 class="title">掲示板</h1>
<section>
    <h2>削除完了</h2>
    <button class="button1" onclick="location.href='users.php'">戻る</button>
</section>
</center>