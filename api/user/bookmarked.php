<?php

header('Content-Type: text/xml');

require_once('../db.php');
$uid = $_GET['uid'];

$result = mysql_query("select paper_id from bookmark where user_id=$uid order by created_at desc limit 20");
if (!$result) {
	die('Query failed: ' . mysql_error());
}
echo "<result>\n";
while ($row = mysql_fetch_row($result))
{
	$paper_id = $row[0];
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $paper_id) ;
}
echo "</result>\n";
?>