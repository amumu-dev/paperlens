<?php
require_once('db.php');
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;}
			#main{width:900px; margin:0 auto;}
			.feed {width:30%; float:left; }
			.article {width:70%; float:left; }
			a {font-size:13px; color: #031221; }
			a:hover {font-size:13px; color: #0E2D3D; }
		</style>
	<head>
	<body>
		<div id="main">
			<div><span class="feed">RSS源</span><span class="article">最新文章</span></div>
			<?php

			$result = mysql_query("select name, link, latest_article_title, latest_article_link from feeds order by popularity desc limit 20");
			while($row=mysql_fetch_array($result))
			{
				$name = $row[0];
				$link = $row[1];
				$article = $row[2];
				$article_link = $row[3];
				if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
				echo "<div><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span><span class=\"article\"><a href=\"$article_link\">$article</a></span></div>";
			}
			?>
		</div>
	</body>
</html>