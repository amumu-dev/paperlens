<?php
require_once('db.php');
$id = $_GET['id'];
$result = mysql_query('select title from paper where id='.$id);
if (!$result) {
    die('Query failed: ' . mysql_error());
}

while ($row = mysql_fetch_row($result))
{
	echo $row[0];
}
mysql_free_result($result);

?>