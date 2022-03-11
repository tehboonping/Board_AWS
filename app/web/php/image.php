<?php
session_start();

if($_SESSION['enable'])
{
	return;
}

$id = $_GET["id"];
if(!$id)
{
	echo "IDが存在しません";
}

$redis = new Redis();
$redis->connect('boardcache-001.67kw0i.0001.apne1.cache.amazonaws.com',6379);

for($i = 1;$i <= $redis->dbsize(); $i++)
{
	$redisdata = $redis->hGetALL('datas'.$i);
	if($redisdata['id'] === $id)
	{
		list($file_name, $file_type) = explode(".", $redisdata['imgname']);
		header('Content-type:image/'.$file_type);
		echo $redisdata['image'];
		exit();
	}
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

$data = $mysqli->query("SELECT FROM datas WHERE id = '$id'");
$image = $data->fetch();

list($file_name, $file_type) = explode(".", $image['imgname']);
header('Content-type:image/'.$file_type);
echo $image['image'];
exit();
?>