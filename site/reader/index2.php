<?php
require_once('db.php');
$result = mysql_query("select name, link from feeds order by popularity desc limit 20");
while($row=mysql_fetch_array($result))
{
	$name = $row[0];
	$link = $row[1];
	echo "<a href=\"$link\">$name</a>";
}
?>
