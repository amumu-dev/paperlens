<?php
header('Content-Type: text/xml');

require_once('db.php');

$author = $_GET['author'];
$topN = $_GET['n'];

//$result = mysql_query("select * from sphinx  where query='@title \"" . $query . "\" | @name \"" .$query . "\";mode=any;sort=relevance;limit=".$topN. ";index=idx1';");
$result = mysql_query("select paper_id from paper_author where author_id='" . $author . "' limit " . $topN);
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