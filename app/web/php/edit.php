<?php
session_start();

if($_SESSION['enable'])
{
	header('Location: ../index.php');
	exit;
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

$id = $_POST["getid"];
if(!$id)
{
	echo "IDが存在しません";
}

$data = $mysqli->query("SELECT * FROM datas WHERE id = $id");
if(!$data)
{
	echo "データが存在しません";
}

$name = $_POST['name'];
$message = $_POST['message'];
$delete = $_POST['delete'];
$image = $_FILES['image']['name'];

$redis = new Redis();
$redis->connect('boardcache-001.67kw0i.0001.apne1.cache.amazonaws.com',6379);

$uploaddir = "../images/";
$managerdir = "http://35.77.47.198:85/images/"

if($image)
{
	$deleteimage = $mysqli->query("SELECT * FROM datas WHERE id=$id");
	foreach($deleteimage as $row)
	{
		$filename = $row['image'];
		$filepath = $uploaddir.$filename;

		if($filename && file_exists($filepath))
		{
			if(!unlink($filepath)) { echo "削除失敗"; }
		}
	}

	$filepath = $uploaddir.$image;
	$managerpath = $managerdir.$filename;

	if(file_exists($filepath))
	{
		list($file_name, $file_type) = explode(".", $image);
		$ran = (string)random_int(0, 99999);
		$dateformat = date("Ymdhis");
		$hash = $name.$dateformat.$ran;
		$special = hash('sha1', $hash);

		$filepath = "$uploaddir$special.$file_type";
		$managerpath = "$managerdir$special.$file_type";

		$image = "$special.$file_type";
	}

	if(!move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) { echo "(編集)アップロード失敗"; }
	if(!move_uploaded_file($_FILES['image']['tmp_name'], $managerpath)) { echo "マネージャーアップロード失敗"; }

	for($i = 1;$i <= $redis->dbsize(); $i++)
	{
		$redisdata = $redis->hGetALL('datas'.$i);
		if($redisdata['id'] === $id)
		{
			$redis->hSet('datas'.$i,'name',$name);
			$redis->hSet('datas'.$i,'message',$message);
			$redis->hSet('datas'.$i,'image',$image);
			break;
		}
	}

	$data = $mysqli->query("UPDATE datas SET name='$name',message='$message',image='$image' WHERE id = $id");
}
else if($delete)
{
	$deleteimage = $mysqli->query("SELECT * FROM datas WHERE id=$id");
	foreach($deleteimage as $row)
	{
		$filename = $row['image'];
		$filepath = $uploaddir.$filename;
		
		if($filename && file_exists($filepath))
		{
			if(!unlink($filepath)) { echo "削除失敗"; }
		}
	}

	for($i = 1;$i <= $redis->dbsize(); $i++)
	{
		$redisdata = $redis->hGetALL('datas'.$i);
		if($redisdata['id'] === $id)
		{
			$redis->hSet('datas'.$i,'name',$name);
			$redis->hSet('datas'.$i,'message',$message);
			$redis->hSet('datas'.$i,'image',NULL);
			break;
		}
	}

	$data = $mysqli->query("UPDATE datas SET name='$name',message='$message',image=NULL WHERE id = $id");
}
else
{
	for($i = 1;$i <= $redis->dbsize(); $i++)
	{
		$redisdata = $redis->hGetALL('datas'.$i);
		if($redisdata['id'] === $id)
		{
			$redis->hSet('datas'.$i,'name',$name);
			$redis->hSet('datas'.$i,'message',$message);
			break;
		}
	}

	$data = $mysqli->query("UPDATE datas SET name='$name',message='$message' WHERE id = $id");
}
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
