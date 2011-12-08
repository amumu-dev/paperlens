<?php
header("Content-Type: text/xml; charset=utf-8");
require_once('db.php');
$user_id = $_GET['uid'];
$result = mysql_query("select feed_id from myfeed where user_id=$user_id;");
$feeds = '';
while($row = mysql_fetch_array($result))
{
	$feed_id = $row[0];
	$feeds .= $feed_id . ',';
}
$feeds .= '0';
?>

<rss version="2.0">
  <channel>
    <title>我的阅读列表</title>
    <description>个性化阅读列表</description>
    <link>http://www.feedforall.com/industry-solutions.htm</link>

<?php
$result = mysql_query("select a.article_id from feed_articles a, articles b where a.article_id = b.id and a.feed_id in ($feeds) order by b.pub_at desc limit 20");
while($row = mysql_fetch_array($result))
{
	$article_id = $row[0];
	echo $article_id;
	echo file_get_contents("http://www.reculike.com/site/reader/articles/" . (string)($article_id % 10) . "/" . (string)($article_id));
}
?>
</channel></rss>