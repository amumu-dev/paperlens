<?php
require_once('../api/db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];
$keywords = $_POST["keywords"];
mysql_query("replace into user (email,passwd,keywords) values ('" . $email . "', '".$password."', '" . $keywords . "');");

echo "SELECT id FROM user WHERE email='".$email."' and password = '" . $password . "'";
$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and password = '" . $password . "'");
if ($result) 
{
	$row = mysql_fetch_row($result);
	$uid = $row[0];
	session_start();
	$_SESSION["admin"] = true;
	Header("Location: index.php?uid=" . $uid);
}
?>