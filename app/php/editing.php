<?php
session_start();

$developerid = '1';

$host = "mysql";
$user = "root";
$password = "pass";
$database = "boarddata";

$mysqli = new mysqli($host, $user, $password, $database);
if($mysqli->connect_errno)
{
	echo "DB接続失敗". $mysqli->connect_error;
}

if(!$_GET["editid"])
{
	echo "IDが存在しません";
}
$id = $_GET["editid"];

$data = $mysqli->query("SELECT * FROM datas WHERE id = $id");

if(!$data)
{
	echo "データが存在しません";
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<head>
	<meta charset="UTF-8">
	<title>掲示板</title>
	<link href="style.css" rel="stylesheet">
</head>
<h1>掲示板</h1>
<section>
    <h2>投稿の編集</h2>
    <form action="edit.php" method="get">
    	<h3>名前</h3>
    	<?php foreach($data as $row):?>
    		<input type="hidden" name="getid" value="<?php echo $id?>">
    		<?php if($_SESSION['Developer'] === $developerid) {?>
    			<input name="name" class="namebox" value="<?php echo $row['name']?>">
    		<?php } else {?>
    			<input name="name" class="namebox" value="<?php echo $row['name']?>" readonly>
    		<?php }?>
    	<h3>内容</h3>
    		<textarea name="message" class="textbox"><?php echo $row['message']?></textarea><br>
		<?php endforeach;?>
    	<button type="submit">編集</button>
	</form>

	<button class="buttons" onclick="location.href='users.php'">キャンセル</button>
</section>