<?php

header('Content-Type: text/xml');

require_once('../db.php');
$uid = $_GET['uid'];

$result = mysql_query("select paper_id from user_paper_behavior where user_id=$uid and behavior=1 order by created_at desc limit 20");
if (!$result) {
	die('Query failed: ' . mysql_error());
}
while ($row = mysql_fetch_row($result))
{
	$paper_id = $row[0];
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $id) ;
}
?>