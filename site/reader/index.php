<?php
require_once('db.php');
?>

<html>
	<head>
		<title>RSS源推荐</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;line-height:26px;}
			#main{width:960px; margin:0 auto; margin-top:20px;}
			#head{width:960px; margin:0 auto; font-size:40px;margin-top:30px; font-weight:bold;}
			.item {width:100%;text-align:left;clear:both;}
			.feed {width:30%; float:left; }
			.article {width:40%; float:left; }
			.subscribe {width:30%; float:left;vertical-align:bottom; }
			a {font-size:13px; color: #1D5261; }
			a:hover {font-size:13px; color: #5697A3; }
			img {border:none;}
		</style>
	<head>
	<body>
		<div id="head">RSS源推荐</div>
		<div id="main">
			<div class="item">
				<span class="feed" style="font-size:16px;line-height:36px;font-weight:bold;">RSS源</span>
				<span class="article" style="font-size:16px;line-height:36px;font-weight:bold;">最新文章</span>
				<span class="subscribe" style="font-size:16px;line-height:36px;font-weight:bold;">订阅</span>
			</div>
			<?php
			$n = 0;
			$result = mysql_query("select name, link, latest_article_title, latest_article_link from feeds order by popularity desc limit 32");
			while($row=mysql_fetch_array($result))
			{
				$name = $row[0];
				$link = $row[1];
				$encode_link = urlencode($link);
				$article = $row[2];
				$article_link = $row[3];
				if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
				if(++$n > 16) break;
				echo "<div class=\"item\"><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span>"
					. "<span class=\"article\"><a href=\"$article_link\" target=_blank>$article</a></span>"
					. "<span class=\"subscribe\"><a href=\"http://fusion.google.com/add?feedurl=$encode_link\" target=_blank><img src=\"http://buttons.googlesyndication.com/fusion/add.gif\" /></a>&nbsp;"
					. "<a href=\"http://9.douban.com/reader/subscribe?url=$encode_link\" target=\"_blank\"><img src=\"http://www.douban.com/pics/newnine/feedbutton1.gif\"/></a>&nbsp;"
					. "<a target=\"_blank\" href=\"http://xianguo.com/subscribe?url=$encode_link\"><img src=\"http://xgres.com/static/images/sub/sub_XianGuo_09.gif\" /></a>"
					. "</div>";
			}
			?>
		</div>
	</body>
</html>