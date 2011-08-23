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
	$result = mysql_query('select title, booktitle,year,journal,abstract,type from paper where id='.$paper_id);
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}

	while ($row = mysql_fetch_row($result))
	{
		if($row[5] == "www") return array();
		$ret['title'] = $row[0];
		$ret['booktitle'] = $row[1];
		$ret['year'] = $row[2];
		$ret['journal'] = $row[3];
		$ret['abstract'] = $row[4];
	}
	mysql_free_result($result);
	
	//get author
	$ret['author'] = array();
	$result = mysql_query('select author_id from paper_author where paper_id='.$paper_id.' order by rank');
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}

	while ($row = mysql_fetch_row($result))
	{
		$ret['author'][$row[0]] = getAuthorName($row[0]);
	}
	mysql_free_result($result);
	return $ret;
}

function getRecommendedUsers($paper_id)
{
	$ret = array();
	$result = mysql_query("select a.user_id, b.email from user_paper_behavior a, user b where a.user_id=b.id and a.behavior=1 and a.paper_id=$paper_id order by a.created_at desc limit 100;");
	if(!$result) return $ret;
	
	while($row = mysql_fetch_row($result))
	{
		$tks = explode('@', $row[1]);
		$user_name = $tks[0];
		$ret[$row[0]] = $user_name;
	}
	return $ret;
}

$options = array(
    'namespace' => 'paper_',
    'servers'   => array(
       array('host' => '127.0.0.1', 'port' => 6379)
    )
);

require_once './lib/Rediska/library/Rediska.php';
$rediska = new Rediska($options);
$paper_key = new Rediska_Key($id);

$xml = $paper_key->getValue();
if(isset($xml))
{
	echo $xml;
}
else
{
	$paper_info = getPaperInfo($id);
	$rec_users = getRecommendedUsers($id);
	$xml = '';
	if(count($paper_info) > 0)
	{
		$xml .= "<paper>\n";
		$xml .= "<id>" . $id . "</id>\n";
		$xml .= "<title>" . htmlspecialchars($paper_info['title']) . "</title>\n";
		if(strlen($paper_info['booktitle']) > 0)
			$xml .= "<booktitle>" . htmlspecialchars($paper_info['booktitle']) . "</booktitle>\n";
		else $xml .= "<booktitle>" . htmlspecialchars($paper_info['journal']) . "</booktitle>\n";
		$xml .= "<year>" . htmlspecialchars($paper_info['year']) . "</year>\n";
		echo "<abstract>" . htmlspecialchars($paper_info['abstract']) . "</abstract>";
		foreach($paper_info['author'] as $author_id => $author_name)
		{
			$xml .= "<author><id>" . $author_id. "</id><name>".$author_name."</name></author>\n";
		}
		$xml .= "<rec>\n";
		foreach($rec_users as $user_id => $user_name)
		{
			$xml .= "<user id=\"$user_id\">$user_name</user>\n";
		}
		$xml .= "</rec>\n";
		$xml .= "</paper>\n";
	}
	echo $xml;
	
	$paper_key->setValue($xml);
}
?>

