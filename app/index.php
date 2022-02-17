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

$userid = $_POST['userid'];
$pass = $_POST['password'];
$security = $userid.$pass;

$data = $mysqli->query("SELECT * FROM systems WHERE user = '$userid'");

if(!$data)
{
	echo "データなし";
}

if($userid AND $pass)
{
	$row = mysqli_fetch_array($data, MYSQLI_ASSOC);

	if(password_verify($security, $row['pass']))
	{
		$_SESSION['accountid'] = $row['accountid'];
		$_SESSION['username'] = $row['username'];
		$_SESSION['Developer'] = $row['lv'];

		header('Location:http://172.16.1.245:8080/php/users.php');
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
	<h1 class="title">掲示板ログイン</h1>
	<br>
	<form action="index.php" method="post">
		<h2>ユーザーID</h2>
		<input name="userid" class="userids" value="<?php echo $userid; ?>" required>

		<h2>パスワード</h2>
		<input name="password" type="password" value="<?php echo $pass;?>" class="passwords" required><br><br>

		<button class="button1" type="submit">ログイン</button><br>
		<p style="color: red"><?php echo $msg; ?></p>
	</form>
	<br><hr style="height: 2px; background-color: black;"><br>
	<button class="button1" onclick="location.href='php/signup.php'">アカウント申請</button>
	<button class="button1" onclick="location.href='php/guests.php'">ゲストとして使用</button>
</body>
</html>

