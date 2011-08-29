<?php
header('Content-Type: text/xml');

require_once('db.php');

$author = $_GET['author'];
$topN = $_GET['n'];
$user_id = 0;
if(isset($_GET['user'])) $user_id = $_GET['user'];

//$result = mysql_query("select * from sphinx  where query='@title \"" . $query . "\" | @name \"" .$query . "\";mode=any;sort=relevance;limit=".$topN. ";index=idx1';");
$result = mysql_query("select a.paper_id from paper_author a,paper b where a.paper_id=b.id and length(b.title)>10 and a.author_id=" . $author . " order by a.rank limit " . $topN);
if (!$result) {
    die('Query failed: ' . mysql_error());
}

echo "<result>";

while ($row = mysql_fetch_row($result))
{
	$id = $row[0];
	echo file_get_contents("http://127.0.0.1/api/paper.php?id=$id&user=$user_id") ;
}

echo "</result>";

?>