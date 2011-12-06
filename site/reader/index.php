<?php
require_once('db.php');
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
<body>
<?php

$result = mysql_query("select name, link, latest_article_title, latest_article_link from feeds order by popularity desc limit 20");
while($row=mysql_fetch_array($result))
{
	$name = $row[0];
	$link = $row[1];
	$article = $row[2];
	$article_link = $row[3];
	if(strlen($article) < 10 || strlen($article_link) > 180) continue;
	echo "<div class=\"feed\"><span><a href=\"$link\" target=_blank>$name</a></span><span><a href=\"$article_link\">$article</a></span></div>";
}
?>
</body></html>