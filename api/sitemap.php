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

?>

