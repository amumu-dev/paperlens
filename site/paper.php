<?php
session_start();
require_once('session.php');
require_once('config.php');
require_once("functions.php");
$paper = $_GET["id"];

$paper_dom = new DOMDocument();
if(!$paper_dom->load('http://127.0.0.1/api/paper.php?abstract=1&user=' . $_SESSION['uid']. '&id=' . $paper))
{
	echo 'load xml failed';
	return;
}
$paper_title = getPaperTitle($paper_dom);
$dom = new DOMDocument();
//if(!$dom->load('http://50.18.105.189/api/recommendation/relate/default_related_items_xml.php?id=' . $paper))
if(!$dom->load('http://127.0.0.1/api/recommendation/relate/related_items_xml.php?tables=papersim_author-1|cite_citeseer-0.3|default-0.1&id=' . $paper))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
$related_users = array();
?>
<html>
	<head>
		<title><?php echo $paper_title . " - " . $SITE_NAME; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script src="./js/main.js" type="text/javascript"></script>
		<?
			include('./search/sug_js.php');
			include('./search/sug_css.php');
		?>
	</head>
	
	<body>
		<?php 
			if(isset($_SESSION["uid"]))
				echo "<img src=\"/site/behavior/behavior.php?uid=" . $_SESSION["uid"] . "&paper=$paper&behavior=3&w=1\"  width=0 height=0 />";
			else echo "<img src=\"/site/behavior/behavior.php?uid=0&paper=$paper&behavior=3&w=1\"  width=0 height=0 />";
		?>
		<div id="content">
			<div id="header">
				<?php if($login){ ?>
				<div style="width:100%;float:left;">
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="./index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="./logout.php">Log out</a></span>&nbsp;&nbsp;
				</div>
				<div id="share">
					<span>
						<script type="text/javascript" charset="utf-8">
						(function(){
						  var _w = 106 , _h = 24;
						  var param = {url:location.href,type:'6',count:'', appkey:'',title:'',pic:'',ralateUid:'2363867140',rnd:new Date().valueOf()}
						  var temp = [];
						  for( var p in param ){
						    temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
						  }
						  document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')
						})()
						</script>
					</span>
					<span>
						<a href="javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})()"><img src="http://img2.douban.com/pics/fw2douban1.png" alt="ÍÆ¼öµ½¶¹°ê" /></a>
					</span>
					<!--<span><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>-->
				</div>
				</div>
				<?php } else echo "&nbsp;<br>"; ?>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
				<?
				include('./search/search_bar.php');
				$paper_title = renderFirstPaper($paper_dom);
				$has_recommend_dom = $paper_dom->getElementsByTagName("has_recommend");
				$has_recommend = 0;
				if($has_recommend_dom->length > 0)
				{
					if($has_recommend_dom->item(0)->nodeValue == 0)
					{
						$has_recommend = 1;
				?>
				<span class="feedback">
					<font color=#F2583E>&#9679;&nbsp;</font>
					<a style="font-size:12px;" onclick="document.getElementById('recommend_box').style.display='block';">Recommend</a>&nbsp;
					<font color=#77BED2>&#9679;&nbsp;</font>
					<a style="font-size:12px;" href="http://www.google.com/search?hl=en&q=<?php echo $paper_title; ?>" target=_blank>Google It</a>&nbsp;
					
				</span>
				<div style="display:none;width:100%;float:left;" id="recommend_box">
					<span>Please input tags (seprated by comma)</span><br>
					<form method="post" action="./behavior/recommend.php" style="width:100%;float:left;">
						<input name="message" style="width:95%;float:left;height=64px;line-height:24px;height:24px;" />
						<input type="hidden" name="paper_id" value="<?php echo $paper; ?>" />
						<input type="hidden" name="user_id" value="<?php echo $_SESSION['uid']; ?>" />
						<input type="hidden" name="callback" value="<?php echo "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
						<input type="submit" value="recommend" />
					</form>
				</div>
				<?php
					}
				}if($has_recommend == 0){
				?>
				<span class="feedback">
					<font color=#F2583E>&#9679;&nbsp;</font>
					<a style="font-size:12px;">You have recommended the paper</a>&nbsp;
					<font color=#77BED2>&#9679;&nbsp;</font>
					<a style="font-size:12px;" href="http://www.google.com/search?hl=en&q=<?php echo $paper_title; ?>" target=_blank>Google It</a>
				</span>
				<?php } ?>
			</div>
			
			<div id="main">
				<?php  if(!$login) require_once('./tools/login_section.php'); ?>
				
				<div id="searchret">
					<h2>Related Articles</h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					renderPapers($papers, $related_authors, $related_users, $paper);
					?>
				</div>
			</div>
			<div id="side">
				<h2>Related Authors</h2>
				<div class="related_author">
				<?php
				renderRelatedAuthors($related_authors);
				?>
				</div>
			</div>
		</div>
		<div id="foot">&copy; <?php echo $SITE_NAME; ?> 2011</div>
		<div id="feedbackcode"></div>
		<?php require_once('ga.php'); ?>
	</body>
</html>