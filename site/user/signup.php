<?php
require_once('../../api/db.php');
$username = ($_POST['username']);
$password = md5($_POST["password"]);
$email = $_POST["email"];
$keywords = $_POST["keywords"];

mysql_query("insert into user (username,email,passwd,keywords) values ('" . $username . "' , '" . $email . "', '".$password."', '" . $keywords . "');");

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
