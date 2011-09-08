<?php

header('Content-Type: text/xml');

require_once('../db.php');
$uid = $_GET['uid'];

$result = mysql_query("select b.email, a.message,created_at from recommend a, user b where a.user_id=b.id order by created_at desc limit 20");
if ($result) {
	echo "<result>\n";
	while ($row = mysql_fetch_row($result))
	{
		$email = $row[0];
		$message = $row[1];
		$created_at = date("Y-M-D H:i:s", strtotime($row[2]));
		echo "<email>$email</email><message>$message</message><date>$created_at</date>";
	}
	echo "</result>\n";
}
?>