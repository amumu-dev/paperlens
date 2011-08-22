<?php
session_start();
require_once('../session.php');
require_once('../config.php');
require_once('../../api/db.php');
if(!$login) header("location : /site/index.php");

$result = mysql_query("SELECT keywords,email,passwd,username FROM user WHERE id=".$uid);
$row = mysql_fetch_row($result);
$keywords = $row[0];
$email = $row[1];
$passwd = $row[2];
$username = $row[3];
	
if(isset($_POST['username']))
{
	if(md5($_POST['passwd']) != $passwd && strlen($_POST['passwd']) > 0)
	{
		echo "<h2>Please input right old password to edit the info</h2>";
		return;
	}
	if(isset($_POST['passwd_new'])) $passwd = $_POST['passwd_new'];
	if(isset($_POST['username'])) $username = $_POST['username'];
	if(isset($_POST['email'])) $email = $_POST['email'];
	if(isset($_POST['keywords'])) $keywords = $_POST['keywords'];
	if(strlen($username) < 6)
	{
		echo "<h2>Nick name must have more than 6 characters</h2>";
		return;
	}
	if(strlen($passwd) < 6)
	{
		echo "<h2>Password must have more than 6 characters</h2>";
		return;
	}
	mysql_query("update user set keywords='" . $keywords . "', email='" . $email . "', passwd='" . $passwd . "', username='" . $username . "' where id=" . $_SESSION['uid']);
}
?>
<html>
	<head>
		<title><?php echo $SITE_NAME; ?> : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/main.css" />
	<head>
	<body>
		<div id="content">
			<div id="header">
				<div id="toolbar">
					<span>Hi <?php echo $email; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
			</div>
			<div id="main">
				<form action="edit.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Nick Name&nbsp;</span><input type="text" name="username" class="textinput" value="<?php echo $username; ?>"/></div>
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput" value="<?php echo $email; ?>"/></div>
					<div style="float:left;width:100%;"><span>Old Password&nbsp;</span><input type="password" name="passwd" class="textinput" value=""/></div>
					<div style="float:left;width:100%;"><span>New Password&nbsp;</span><input type="password" name="passwd_new" class="textinput" value=""/></div>
					<div style="float:left;width:100%;"><span>Interest Keywords&nbsp;</span><input type="text" name="keywords" class="textinput" value="<?php echo $keywords; ?>"/></div>
					<input type="submit" value="Login/Signup" class="button" />
				</form>
			</div>
		</div>
	</body>
</html>