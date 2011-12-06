<?php
header("Content-type: text/xml");
require_once('db.php');
$id = $_GET['id'];
$result = mysql_query("select name, link, latest_article_title, latest_article_link from feeds where id=$id");
while($row=mysql_fetch_array($result))
{
	$name = $row[0];
	$link = $row[1];
	$article = $row[2];
	$article_link = $row[3];
	echo "<item><name><![CDATA[$name]]></name><link>$link</link><article><![CDATA[$article]]></article><article_link>$article_link</article_link></item>";
}
?>
