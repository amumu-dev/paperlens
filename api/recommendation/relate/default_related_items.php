<?php
require_once("../../db.php");

function GetRelatedItems($item, $topN)
{
	$ret = array();
	$title_result = mysql_query('select title from paper where id='.$item);
	if (!$title_result) return $ret;
	$title = '';
	while ($row = mysql_fetch_row($title_result))
	{
		$title = $row[0];
	}
	
	$result = mysql_query('select id,weight from sphinx  where query=\'@title \"' . str_replace(' ', '+', $title) . '\";mode=any;sort=relevance;limit=10;index=idx1\';');
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}
	while ($row = mysql_fetch_row($result))
	{
		$id = $row[0];
		$weight = $row[1];
		$ret[$id] = $weight;
	}
	arsort($ret);
	return array_slice($ret, 0, $topN, TRUE);
}

$id = $_GET['id'];
$related_items = GetRelatedItems($id, 10);
header('Content-Type: text/xml');
echo '<relate>';
foreach($related_items as $id => $weight)
{
	//echo $id . ',' . $weight . '    ';
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $id) ;
}
echo '</relate>';
?>