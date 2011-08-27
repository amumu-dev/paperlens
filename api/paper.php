<?php

header('Content-Type: text/xml');

require_once('db.php');
require_once('paper_func.php');
$id = $_GET['id'];
$has_recuser = 1;
if(isset($_GET['recuser']))  $has_recuser = $_GET['recuser'];
$has_author = 1;
if(isset($_GET['author']))  $has_author = $_GET['author'];
/*
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
*/
$xml = "";
if(false)//isset($xml))
{
	echo $xml;
}
else
{
	$paper_info = getPaperInfo($id);
	if($has_recuser == 1) $rec_users = getRecommendedUsers($id);
	$download_link = getDownLoadLink($id);
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
		$xml .=  "<abstract>" . htmlspecialchars($paper_info['abstract']) . "</abstract>";
		if($has_author == 1)
		{
			foreach($paper_info['author'] as $author_id => $author_name)
			{
				$xml .= "<author><id>" . $author_id. "</id><name>".$author_name."</name></author>\n";
			}
		}
		if($has_recuser == 1) 
		{
			$xml .= "<rec>\n";
			foreach($rec_users as $user_id => $user_name)
			{
				$xml .= "<user id=\"$user_id\">$user_name</user>\n";
			}
			$xml .= "</rec>\n";
		}
		$xml .= "<download>$download_link</download>";
		$xml .= "</paper>\n";
	}
	echo $xml;
	
	//$paper_key->setValue($xml);
}
?>

