<?php
require_once('../../api/db.php');
if(!isset($_POST['username']))
{
	echo "You must enter user name";
	return;
}
$username = ($_POST['username']);
if(!isset($_POST['password']))
{
	echo "You must enter password";
	return;
}
$password = md5($_POST["password"]);
if(!isset($_POST['email']))
{
	echo "You must enter email";
	return;
}
$email = $_POST["email"];
if(!isset($_POST['keywords']))
{
	echo "You must enter keywords";
	return;
}
$keywords = $_POST["keywords"];

if(!isset($_POST['connect_type']))
{
	mysql_query("insert into user (username,email,passwd,keywords) values ('" . $username . "' , '" . $email . "', '".$password."', '" . $keywords . "');");
}
else
{
	$connect_type = $_POST['connect_type'];
	if($connect_type == "douban")
	{
		$douban_uid=$_POST['douban_uid'];
		$douban_name=$_POST['douban_name'];
		$douban_token=$_POST['douban_token'];
		$douban_token_secret=$_POST['douban_token_secret'];
		mysql_query("insert into user (username,email,passwd,keywords,doubanid,dname,dackey, dacsec) values ('" 
			. $username . "' , '" . $email . "', '".$password."', '" . $keywords . "', '".$douban_uid.", '".$douban_name.", '".$douban_token.", '".$douban_token_secret.");");
	}
}

echo "SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'";
$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
if ($result) 
{
	$row = mysql_fetch_row($result);
	$uid = $row[0];
	session_start();
	$_SESSION["admin"] = true;
	$_SESSION["uid"] = $uid;
	Header("Location: /site/index.php?uid=" . $uid);
}
?>
