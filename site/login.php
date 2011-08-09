<?php
require_once('db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];

echo "SELECT id FROM user WHERE email='".$email."'";
$result0 = mysql_query("SELECT id FROM user WHERE email='".$email."'");
if($result0 && mysql_num_rows($result0) > 0)
{
	$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
	if ($result) 
	{
		$row = mysql_fetch_row($result);
		$uid = $row[0];
		session_start();
		$_SESSION["admin"] = true;
		$_SESSION["uid"] = $uid;
		$_SESSION["email"] = $email;
		//Header("Location: index.php");
	} else {
		echo "User name and password error";
	}
}
else
{
	if(strpos($email, "@") === false)
	{
		echo "<h2>Email address is invalid!</h2>";
		return;
	}
	if(strlen($_POST["password"]) < 6)
	{
		echo "<h2>Password must exceed 6 characters</h2>";
		return;
	}
	/*
	mysql_query("insert into user (email,passwd) values ('" . $email . "', '".$password."');");

	$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
	if ($result) 
	{
		$row = mysql_fetch_row($result);
		$uid = $row[0];
		session_start();
		$_SESSION["admin"] = true;
		$_SESSION["uid"] = $uid;
		$_SESSION["email"] = $email;
		Header("Location: index.php?uid=" . $uid);
	}
	*/
}
?>