<?php
require_once('../../api/db.php');
include_once("userconfig.php");
include_once("userfunc.php");

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

if(isset($_POST['connect_type']))
{
	$connect_type = $_POST['connect_type'];
	if($connect_type == "douban")
	{
		$douban_uid=$_POST['douban_uid'];
		$douban_name=$_POST['douban_name'];
		$douban_token=$_POST['douban_token'];
		$douban_token_secret=$_POST['douban_token_secret'];
		if(!checkDoubanIdExist($douban_user_id))
		{
			mysql_query("insert into user (username,email,passwd,keywords,doubanid,dname,dackey, dacsec) values ('" 
			. $username . "' , '" . $email . "', '".$password."', '" . $keywords . "', '".$douban_uid.", '".$douban_name.", '".$douban_token.", '".$douban_token_secret.");");
		}
		else
		{
			if(!checkDoubanUserLinked($douban_user_id))
			{
				$user_id = getUidbyDuid($douban_user_id);
				mysql_query("update user set username='$username', email='$email', passwd='$password', keywords='$keywords',"
					. "doubanid='$douban_uid', dname='$douban_name', dackey='$douban_token', dacsec='$douban_token_secret' "
					. "where id=$user_id");
			}
		}
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
