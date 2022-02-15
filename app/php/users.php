<?php
session_start();

$host = "mysql";
$user = "root";
$password = "pass";
$database = "boarddata";

$mysqli = new mysqli($host, $user, $password, $database);
if($mysqli->connect_errno)
{
	echo "DB接続失敗". $mysqli->connect_error;
}			

$name = $_SESSION['username'];
$comment = $_POST["comment"];
date_default_timezone_set("Asia/Tokyo");
$posttime = date("Y-m-d H:i:s");

if(!empty($name) && !empty($comment))
{
	$data = $mysqli->query("INSERT INTO datas(name,message,posttime) VALUES('$name','$comment','$posttime')");
}

$data = $mysqli->query("SELECT * FROM datas order by posttime desc");

if(!$data)
{
	echo "データテーブルが存在しない。";
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	header('Location:http://172.16.2.24:8080/php/users.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>掲示板</title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<button onclick="location.href='../index.php'" class="return">←ログイン画面に戻る</button>
	<center>
	<h1>掲示板</h1>
	<section>
		<h2>投稿</h2>
		<form action="users.php" method="post">
			<p name="name" class="namebox" id="names">名前：<?php echo $_SESSION['username']?></p><br>
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
					<div class="display">
					<form action="editing.php" method="get" class="from">
						<input type="hidden" name="editid" value="<?php echo $row['id']?>">
						<button type="submit" class="button1">編集</button>
					</form>
					<form action="delete.php" method="get" class="from">
						<input type="hidden" name="deleteid" value="<?php echo $row['id']?>">
						<button type="submit" class="button1">削除</button>
					</form>
				</div>
				</div>
			<?php endforeach;?>
	</section>

</body>
</html>