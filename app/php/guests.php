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

$name = $_POST["name"];
$comment = $_POST["comment"];
date_default_timezone_set("Asia/Tokyo");
$posttime = date("Y-m-d H:i:s");

if(!empty($name) && !empty($comment))
{
	$data = $mysqli->query("INSERT INTO datas(name,message,posttime) VALUES('(G)$name','$comment','$posttime')");
}

$data = $mysqli->query("SELECT * FROM datas order by posttime desc");

if(!$data)
{
	echo "データテーブルが存在しない。";
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	header('Location:http://172.16.1.245:8080/php/guests.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>掲示板(ゲスト)</title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<button onclick="location.href='../index.php'" class="return">←ログイン画面に戻る</button>
	<center>
	<h1>掲示板(ゲスト)</h1>
	<section>
		<h2>投稿</h2>
		<form action="guests.php" method="post">
			名前：<input type="text" name="name" class="namebox" id="names" required><br>
			投稿内容：<button type="submit" id="send">投稿</button><br>
			<textarea type="text" name="comment" class="textbox" id="messages" required></textarea><br>
		</form>
	</section>
	</center>
		<hr>
	<section>
		<h2 align="center">投稿内容一覧</h2>
			<?php foreach($data as $row):?>
				<div class="comment">
					<p class="commentname">名前 : <?php echo $row['name']?></p>
					<p class="commenttime">時刻 : <?php echo $row['posttime']?></p>
					<p class="info">投稿内容 : <br><?php echo $row['message']?></p>
				</div>
			<?php endforeach;?>
	</section>

</body>
</html>