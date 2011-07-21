<?php
require_once('db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];
$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
if ($result) 
{
	$row = mysql_fetch_row($result);
	$uid = $row[0];
	session_start();
	$_SESSION["admin"] = true;
	$_SESSION["uid"] = $uid;
	$_SESSION["email"] = $email;
	Header("Location: index.php");
} else {
	echo "User name and password error";
}
?>