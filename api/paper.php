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
	return htmlspecialchars($name);
}

function getPaperInfo($paper_id)
{
	$ret = array();
	//get title and book title
	$result = mysql_query('select title, booktitle,year,journal from paper where id='.$paper_id);
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}

	while ($row = mysql_fetch_row($result))
	{
		$ret['title'] = $row[0];
		$ret['booktitle'] = $row[1];
		$ret['year'] = $row[2];
		$ret['journal'] = $row[3];
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
echo "<id>" . $id . "</id>";
echo "<title>" . htmlspecialchars($paper_info['title']) . "</title>";
echo "<booktitle>" . htmlspecialchars($paper_info['booktitle'] . "&nbsp;" . $paper_info['journal']) . "</booktitle>";
echo "<year>" . htmlspecialchars($paper_info['year']) . "</year>";
foreach($paper_info['author'] as $author)
{
	echo "<author>" . $author. "</author>";
}
echo "</paper>";

?>
