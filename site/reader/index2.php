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
		<title>RSSԴ�Ƽ�</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="reader.js" type="text/javascript"></script>
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;line-height:26px;}
			#main{width:1000px; margin:0 auto; margin-top:20px;padding-left:5px;}
			#head{width:1000px; margin:0 auto; font-size:40px;margin-top:30px; font-weight:bold;}
			.item {width:100%;text-align:left;clear:both;}
			.feed {width:30%; float:left; }
			.article {width:55%; float:left; }
			.like{display:block;width:40px;float:left;background:#000;height:18px;line-height:18px;cursor:pointer;margin-top:3px;font-size:12px;text-align:center;color:#FFF;}
			.subscribe {width:15%; float:left;vertical-align:bottom; }
			a {font-size:13px; color: #1D5261;}
			a:hover {color: #5697A3;}
			/*.feedtitle {height:18px;line-height:18px; display:block;float:left;width:95%;}*/
			img {border:none;}
		</style>
	<head>
	<body>
		<div id="head">
			<a href="http://www.reculike.com/site/reader/" style="background:#EEE;color:#888;text-decoration:none;display:block;float:left;margin-right:10px;width:120px;height:28px;text-align:center;font-size:14px;line-height:28px;">
				ˢ��
			</a>&nbsp;
			<a onclick="deleteHistory();" href="http://www.reculike.com/site/reader/" style="background:#EEE;color:#888;text-decoration:none;display:block;float:left;margin-right:10px;width:120px;height:28px;text-align:center;font-size:14px;line-height:28px;">
				����
			</a>
		</div>
		<div id="main">
			<div class="item">
				<span class="feed" style="font-size:16px;line-height:36px;font-weight:bold;">RSSԴ</span>
				<span class="article" style="font-size:16px;line-height:36px;font-weight:bold;">��������</span>
				<span class="subscribe" style="font-size:16px;line-height:36px;font-weight:bold;">����</span>
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
			$articles = array();
			foreach($history as $src_id)
			{
				if(strlen($src_id) == 0) continue;
				$result = mysql_query("select dst_id,weight from feedsim where src_id=$src_id");
				while($row=mysql_fetch_array($result))
				{
					$dst_id = $row[0];
					$weight = $row[1];
					if(in_array($dst_id, $history)) continue;
					if(in_array($dst_id, $load_history)) $weight *= 0.1;
					if(!array_key_exists($dst_id, $rank)) $rank[$dst_id] = $weight;
					else $rank[$dst_id] += $weight;
				}
			}
			
			$minvalue = 10000;
			foreach($rank as $id => $w)
			{
				if($minvalue > $w) $minvalue = $w;
			}
			$result = mysql_query("select id from feeds order by popularity desc limit 64");
			while($row=mysql_fetch_array($result))
			{
				$id = $row[0];
				if(array_key_exists($id, $rank)) continue;
				if(in_array($id, $history)) continue;
				if(in_array($id, $load_history)) $rank[$id] = $minvalue * 0.05;
				else $rank[$id] = $minvalue * 0.95;
				$minvalue *= 0.95;
			}
			
			arsort($rank);
			$ids = '';
			$n = 0;
			foreach($rank as $id => $w)
			{
				$ids .= $id . ',';
				if(++$n > 100) break;
			}
			$ids .= '0';
			$result = mysql_query("select id, modify_at from feeds where id in ($ids) and modify_at>0 order by modify_at desc");
			while($row=mysql_fetch_array($result))
			{
				$id = $row[0];
				$pubdate = $row[1];
				if(!array_key_exists($id, $rank)) continue;
				$rank[$id] /= (1 + 0.1 * (time() - $pubdate));
			}
			$n = 0;
			arsort($rank);
			foreach($rank as $id => $w)
			{
				$result = mysql_query("select a.name, a.link, c.title, c.link  from feeds a, feed_articles b, articles c where a.id=$id and a.id=b.feed_id and b.article_id=c.id order by c.pub_at desc");
				while($row=mysql_fetch_array($result))
				{
					$name = $row[0];
					if(in_array($name, $names)) continue;
					array_push($names, $name);
					$link = $row[1];
					$encode_link = urlencode($link);
					$article = $row[2];
					if(in_array($article, $articles)) continue;
					array_push($articles, $article);
					$article_link = $row[3];
					//$id = $row[4];
					$pubdate = $row[5];
					if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
					if(!IsChinese($article)) continue;
					if(++$n > 24) break;
					$onclick_str = "onclick=\"addHistory($id);\"";
					$like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str>ϲ��</a>";
					if(in_array($id, $history)) $like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str style=\"background:#AAA;\">лл</a>";

					echo "<div class=\"item\"><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span>"
					     . "<span class=\"article\"><a href=\"$article_link\" target=_blank>$article</a></span>"
					     . "<span class=\"subscribe\">$like_str</span>"
					     . "</div>";
					     echo "<script type=\"text/javascript\">addLoadHistory($id)</script>";
				}
			}
			?>
		</div>
	</body>
</html>