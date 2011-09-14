<?php

header('Content-Type: text/xml');

require_once('../../api/db.php');

echo "<urlset>";
$result = mysql_query("select a.paper_id,b.title,count(*) as c from user_paper_behavior a, paper b where a.paper_id=b.id group by a.paper_id order by c desc limit 10;");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
?>

<rss version="2.0">
<channel>
        <title>Popular papers in RecULike</title>
        <description>RecULike : OpenSource Academic Recommender System</description>
        <link>http://www.reculike.com/</link>
        <pubDate><?php echo date('Y-m-j G:i:s'); ?></pubDate>
	<?php
	while($row = mysql_fetch_row($result))
	{
		$paper_id = $row[0];
		$title = $row[1];
		echo "<item><title>$title</title><link>http://www.reculike.com/site/paper.php?id=$paper_id</link><pubDate>".date('Y-m-j G:i:s')."</pubDate></item>";
	}
	?>
</channel>
</rss>