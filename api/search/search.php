<?php
header('Content-Type: text/xml');

require_once('../db.php');

$query = $_GET['query'];

$result = mysql_query('select * from sphinx  where query=\'@title \"' . $query . '\";mode=relevance;limit=10;index=idx1\';');
if (!$result) {
    die('Query failed: ' . mysql_error());
}

echo "<result>";

while ($row = mysql_fetch_row($result))
{
	$id = $row[0];
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $id) ;
}

echo "</result>";

?>