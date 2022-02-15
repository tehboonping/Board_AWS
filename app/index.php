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

$userid = $_GET['userid'];
$pass = $_GET['password'];

$data = $mysqli->query("SELECT * FROM systems WHERE user = '$userid'");

if(!$data)
{
	echo "データなし";
}

if($userid AND $pass)
{
	$row = mysqli_fetch_array($data, MYSQLI_ASSOC);

	$hash = password_hash($row['pass'], PASSWORD_DEFAULT);

	if(password_verify($pass, $hash))
	{
		$_SESSION['username'] = $row['username'];

		header('Location:http://172.16.2.24:8080/php/users.php');
		exit;
	}
	else
	{
		$msg = 'ユーザーIDもしくはパスワードが間違っています。';
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>掲示板ログイン</title>
	<link href="php/style.css" rel="stylesheet">
</head>
<body>
	<center>
	<h1>掲示板ログイン</h1>
	<br>
	<form action="index.php" method="get">
		<h2>ユーザーID</h2>
		<input name="userid" class="userids" required>

		<h2>パスワード</h2>
		<input name="password" type="password" class="passwords" required><br><br>

		<button type="submit">ログイン</button><br>
		<p><?php echo $msg; ?></p>
	</form>
	<hr>
	<button onclick="location.href='php/signup.php'">アカウント申請</button>
	<button onclick="location.href='php/guests.php'">ゲストとして使用</button>
</body>
</html>

