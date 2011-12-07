<?php
require_once('db.php');
srand(time());

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
			.article {width:50%; float:left; }
			.like{display:block;width:40px;float:left;background:#000;height:18px;line-height:18px;cursor:pointer;margin-top:3px;font-size:12px;text-align:center;color:#FFF;}
			.subscribe {width:20%; float:left;vertical-align:bottom; }
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
			$history = array();
			if(array_key_exists("his", $_COOKIE)) $history = explode("_", $_COOKIE["his"]);
			$load_history = array();
			if(array_key_exists("loadhis", $_COOKIE)) $load_history = explode("_", $_COOKIE["loadhis"]);
			$n = 0;
			$k = 0;
			$rank = array();
			$names = array();
			foreach($history as $src_id)
			{
				if(strlen($src_id) == 0) continue;
				$result = mysql_query("select dst_id,weight from feedsim where src_id=$src_id order by weight desc limit 10");
				while($row=mysql_fetch_array($result))
				{
					$dst_id = $row[0];
					$weight = $row[1];
					if(in_array($dst_id, $history)) continue;
					if(!array_key_exists($dst_id, $rank)) $rank[$dst_id] = $weight;
					else $rank[$dst_id] += $weight;
					echo $src_id . "&nbsp;" . $dst_id . "&nbsp;" . $weight . "<br>";
				}
			}
			$minvalue = 10000;
			foreach($rank as $id => $w)
			{
				if($minvalue > $w) $minvalue = $w;
			}
			$result = mysql_query("select id from feeds order by popularity desc limit 100");
			while($row=mysql_fetch_array($result))
			{
				$id = $row[0];
				if(array_key_exists($id, $rank)) continue;
				$rank[$id] = $minvalue * 0.95;
				$minvalue *= 0.95;
			}
			arsort($rank);
			$ids = '';
			$n = 0;
			foreach($rank as $id => $w)
			{
				$ids .= $id . ',';
				if(++$n > 48) break;
			}
			$ids .= '0';
			$n = 0;
			$result = mysql_query("select name, link, latest_article_title, latest_article_link, modify_at from feeds where id in ($ids) order by modify_at desc");
			while($row=mysql_fetch_array($result))
			{
				$name = $row[0];
				if(in_array($name, $names)) continue;
				array_push($names, $name);
				$link = $row[1];
				$encode_link = urlencode($link);
				$article = $row[2];
				$article_link = $row[3];
				if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
				if(!IsChinese($article)) continue;
				if(++$n > 16) break;
				$onclick_str = "onclick=\"addHistory($id);\"";
				$like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str>喜欢</a>";
				if(in_array($id, $history)) $like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str style=\"background:#AAA;\">谢谢</a>";

				echo "<div class=\"item\"><span class=\"feed\">$like_str &nbsp;<a href=\"$link\" target=_blank>$name</a></span>"
					. "<span class=\"article\"><a href=\"$article_link\" target=_blank>$article</a></span>"
					. "<span class=\"subscribe\"><a $onclick_str href=\"http://fusion.google.com/add?feedurl=$encode_link\" target=_blank><img src=\"http://gmodules.com/ig/images/plus_google.gif\" /></a>&nbsp;"
					. "<a $onclick_str target=\"_blank\" href=\"http://xianguo.com/subscribe?url=$encode_link\"><img src=\"http://xgres.com/static/images/sub/sub_XianGuo_09.gif\" /></a>"
					. "</div>";
				echo "<script type=\"text/javascript\">addLoadHistory($id)</script>";
				//. "<a $onclick_str href=\"http://9.douban.com/reader/subscribe?url=$encode_link\" target=\"_blank\"><img src=\"http://www.douban.com/pics/newnine/feedbutton1.gif\"/></a>&nbsp;"
			}
			?>
			<div style="width:100%;margin-top:50px;">
				<a href="http://www.reculike.com/site/reader/" style="background:#EEE;color:#888;text-decoration:none;display:block;margin:0 auto;width:200px;height:40px;text-align:center;font-size:32px;line-height:40px;">
					刷新结果
				</a>&nbsp;
				<a onclick="deleteHistory();" href="http://www.reculike.com/site/reader/" style="background:#EEE;color:#888;text-decoration:none;display:block;margin:0 auto;width:200px;height:40px;text-align:center;font-size:32px;line-height:40px;">
					重新开始
				</a>
			</div>
		</div>
	</body>
</html>