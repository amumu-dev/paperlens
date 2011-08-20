<?php
/*
this page get uid from get parameter

let user input the email and password

if email and pwd match then link to this user uid2

link mean write the uid1 info to uid2
delete uid2 info then*/

include_once("userconfig.php");
include_once("userfunc.php");

$connect_type = $_GET['type'];

if($connect_type == "douban")
{
	//type=douban&douban_uid=$douban_user_id&douban_name=$douban_name&douban_token=$douban_token&douban_token_secret=$douban_token_secret
	$douban_uid=$_GET['douban_uid'];
	$douban_name=$_GET['douban_name'];
	$douban_token=$_GET['douban_token'];
	$douban_token_secret=$_GET['douban_token_secret'];
}
?>
<!--the html to link-->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="Author" content="wangxing">
	</head>

	<body>
		<h3>If you already have an account, please input your email and password to connect with douban:</h3>
		<form action="connect.php" method="post">
			<div>
				<label>email:</label>
				<input type="text" id="email" name="email" value="">
				<?php if($connect_type == "douban") { ?>
				<input type="hidden" name="connect_type" value="douban">
				<input type="hidden" name="douban_uid" value="<?php echo $douban_uid; ?>">
				<input type="hidden" name="douban_name" value="<?php echo $douban_name; ?>">
				<input type="hidden" name="douban_token" value="<?php echo $douban_token; ?>">
				<input type="hidden" name="douban_token_secret" value="<?php echo $douban_token_secret; ?>">
				<?php } ?>
			</div>
			<div>
				<label>password:</label>
				<input type="password" id="pwd" name="pwd">
			</div>
			<div><input type="submit" value="Connect"></div>
		</form>

		<h3>If you are new user, please input your email and password to create a new user:</h3>
		<form action="signup.php" method="post">
			<div>
				<label>username:</label><input type="text" name="username" value="">
				<label>email:</label><input type="text" name="email" value="">
				<?php if($connect_type == "douban") { ?>
				<input type="hidden" name="connect_type" value="douban">
				<input type="hidden" name="douban_uid" value="<?php echo $douban_uid; ?>">
				<input type="hidden" name="douban_name" value="<?php echo $douban_name; ?>">
				<input type="hidden" name="douban_token" value="<?php echo $douban_token; ?>">
				<input type="hidden" name="douban_token_secret" value="<?php echo $douban_token_secret; ?>">
				<?php } ?>
			</div>
			<div>
				<label>password:</label>
				<input type="password" name="pwd">
				<label>keywords:</label><input type="text" name="keywords" value="">
			</div>
			<div><input type="submit" value="Connect"></div>
		</form>
	</body>
</html>