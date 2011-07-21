<?php
require_once('/api/db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];
$keywords = $_POST["keywords"];
echo $password . " " . $email . " " . $keywords;
echo "insert into user (email,passwd,keywords) values ('" . $email . "', '".$password."', '" . $keywords . "');";
mysql_query("insert into user (email,passwd,keywords) values ('" . $email . "', '".$password."', '" . $keywords . "');");

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