<?php

header('Content-Type: text/xml');

require_once('db.php');

echo "<urlset>";
$result = mysql_query("select paper_id from user_paper_behavior group by paper_id;");
	
while($row = mysql_fetch_row($result))
{
	$paper_id = $row[0];
	echo "<url><loc>http://www.reculike.com/site/paper.php?id=$paper_id</loc><lastmod>". date("Y-m-d") . "</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>";
}
echo "</urlset>";

	$paper_info = getPaperInfo($id);
	$rec_users = getRecommendedUsers($id);
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
		$xml .= "<download>$download_link</download>";
		$xml .= "</paper>\n";
	}
	echo $xml;
	
	//$paper_key->setValue($xml);
}
?>

