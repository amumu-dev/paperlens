<?php

header('Content-Type: text/xml');

require_once('../db.php');
$id = $_GET['id'];

$result = mysql_query("select b.email, a.message,created_at from recommend a, user b where a.user_id=b.id and a.paper_id=$id order by created_at desc limit 10");
if ($result) {
	echo "<result>\n";
	while ($row = mysql_fetch_row($result))
	{
		$email = $row[0];
		$message = $row[1];
		$created_at = date("Y-n-j H:i:s", strtotime($row[2]));
		echo "<email>$email</email><message>$message</message><date>$created_at</date>";
	}
	echo "</result>\n";
}
?>