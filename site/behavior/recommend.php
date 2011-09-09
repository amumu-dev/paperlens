<?php
require_once("../../api/db.php");
$paper_id = $_POST['paper_id'];
$user_id = $_POST['user_id'];
$message = $_POST['message'];
mysql_query("insert into recommend (user_id, paper_id, created_at) values ($user_id, $paper_id, '" . date("Y-m-d H:i:s") . "');");

$tags = explode(',', $message);

foreach($tags as $tag)
{
	mysql_query("insert into tagging (user_id, paper_id, tag_name, created_at) values ($user_id, $paper_id, '" . trim($tag) . "','" . date("Y-m-d H:i:s") . "');");
}

Header("Location: " . $_POST['callback']);
?>