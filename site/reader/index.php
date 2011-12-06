<?php
require_once('db.php');

function IsChinese($buf)
{
	for($i = 0; $i < strlen($buf); ++$i)
	{
		if(ord($buf[$i]) > 127) return true;
	}
	return false;
}
?>

<html>
	<head>
		<title>RSS源推荐</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="reader.js" type="text/javascript"></script>
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;line-height:26px;}
			#main{width:960px; margin:0 auto; margin-top:20px;padding-left:5px;}
			#head{width:960px; margin:0 auto; font-size:40px;margin-top:30px; font-weight:bold;}
			.item {width:100%;text-align:left;clear:both;}
			.feed {width:30%; float:left; }
			.article {width:40%; float:left; }
			.like{display:block;width:40px;float:left;background:#000;height:18px;line-height:18px;cursor:pointer;margin-top:3px;font-size:12px;text-align:center;color:#FFF;}
			.subscribe {width:30%; float:left;vertical-align:bottom; }
			a {font-size:13px; color: #1D5261;}
			a:hover {color: #5697A3;}
			/*.feedtitle {height:18px;line-height:18px; display:block;float:left;width:95%;}*/
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
			$history = explode("_", $_COOKIE["his"]);
			$n = 0;
			$k = 0;
			$result = mysql_query("select name, link, latest_article_title, latest_article_link, id from feeds order by popularity desc limit 100");
			while($row=mysql_fetch_array($result))
			{
				if(date("i") % 3 == (++$k) % 3) continue;
				$name = $row[0];
				$link = $row[1];
				$encode_link = urlencode($link);
				$article = $row[2];
				$article_link = $row[3];
				$id = $row[4];
				if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
				if(!IsChinese($article)) continue;
				if(++$n > 16) break;
				$onclick_str = "onclick=\"addHistory($id);\"";
				$like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str>喜欢</a>";
				if(in_array($id, $history)) $like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str style=\"background:#AAA;\">谢谢</a>";
				echo "<div class=\"item\"><span class=\"feed\">$like_str &nbsp;<a href=\"$link\" target=_blank>$name</a></span>"
					. "<span class=\"article\"><a href=\"$article_link\" target=_blank>$article</a></span>"
					. "<span class=\"subscribe\"><a $onclick_str href=\"http://fusion.google.com/add?feedurl=$encode_link\" target=_blank><img src=\"http://gmodules.com/ig/images/plus_google.gif\" /></a>&nbsp;"
					. "<a $onclick_str href=\"http://9.douban.com/reader/subscribe?url=$encode_link\" target=\"_blank\"><img src=\"http://www.douban.com/pics/newnine/feedbutton1.gif\"/></a>&nbsp;"
					. "<a $onclick_str target=\"_blank\" href=\"http://xianguo.com/subscribe?url=$encode_link\"><img src=\"http://xgres.com/static/images/sub/sub_XianGuo_09.gif\" /></a>"
					. "</div>";
			}
			?>
			<div style="width:100%;margin-top:50px;">
				<a href="http://www.reculike/site/reader/" style="background:#000;color:#FFF;text-decoration:none;display:block;margin:0 auto;width:160px;height:32px;font-size:32px;line-height:32px;">
					刷&nbsp;新
				</a>
			</div>
		</div>
	</body>
</html>