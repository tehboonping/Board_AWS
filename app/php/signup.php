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

$userid = $_GET["accid"];
$pass = $_GET['accpass'];
$name = $_GET['accname'];

$data = $mysqli->query("SELECT * FROM systems WHERE user = '$userid'");

if(!$data)
{
	echo "データなし";
}

$row = mysqli_fetch_array($data,MYSQLI_ASSOC);

if($userid AND $pass And $name)
{
	if($userid === $row['user'])
	{
		$msg = '既存のユーザーIDが存在します。別のIDを入力してください。';	
	}
	else
	{
		$db = $mysqli->query("INSERT INTO systems(user,pass,username) VALUES('$userid','$pass','$name')");
		$msg ='アカウント申請完了！ログイン画面に戻って、ログインして見ましょう。';
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>掲示板アカウント申請</title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<center>
	<h1>掲示板アカウント申請</h1>
	<br>
	<form action="signup.php" method="get">
		<h2>ユーザーID</h2>
		<input name="accid" class="userids" required>

		<h2>パスワード</h2>
		<input name="accpass" type="password" class="passwords" required>

		<h2>名前</h2>
		<input name="accname" class="userids" required>
		<br><br>
		<button type="submit">申請</button><br>
		<p><?php echo $msg; ?></p>
	</form>
	<hr>
	<button onclick="location.href='../index.php'">ログイン画面に戻る</button>
</body>
</html>