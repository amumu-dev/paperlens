<?php

header('Content-Type: text/xml');

require_once('db.php');
$id = $_GET['id'];

function getAuthorName($author_id)
{
	$result = mysql_query('select name from author where id='.$author_id);
	if (!$result) {
	    return '';
	}

	while ($row = mysql_fetch_row($result))
	{
		$name = $row[0];
	}
	mysql_free_result($result);
	return str_replace('&', '', $name);
}

function getPaperInfo($paper_id)
{
	$ret = array();
	//get title and book title
	$result = mysql_query('select title, booktitle from paper where id='.$paper_id);
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}

	while ($row = mysql_fetch_row($result))
	{
		$ret['title'] = $row[0];
		$ret['booktitle'] = $row[1];
	}
	mysql_free_result($result);
	
	//get author
	$ret['author'] = array();
	$result = mysql_query('select author_id from paper_author where paper_id='.$paper_id);
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}

	while ($row = mysql_fetch_row($result))
	{
		array_push($ret['author'], getAuthorName($row[0]));
	}
	mysql_free_result($result);
	return $ret;
}

$paper_info = getPaperInfo($id);

echo "<paper>";
echo "<title>" . $paper_info['title'] . "</title>";
echo "<booktitle>" . $paper_info['booktitle'] . "</booktitle>";
foreach($paper_info['author'] as $author)
{
	echo "<author>" . $author. "</author>";
}
echo "</paper>";

?>
