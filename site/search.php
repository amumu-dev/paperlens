<?php
session_start();
require_once('session.php');
require_once('config.php');
require_once("functions.php");
$query = $_GET["query"];
$page = isset($_GET['page']) && intval($_GET['page'])>0 ? intval($_GET['page']) : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/search/search_3.php?user=' . $_SESSION['uid']. '&query=' . str_replace(' ','+',$query). '&offset=' . $offset. '&limit=' .$limit))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
$related_users = array();
?>
<html>
	<head>
		<title><?php echo $SITE_NAME; ?> : Open Source Academic Recommender System</title>
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
				echo "<img src=\"/site/behavior/behavior.php?uid=".$_SESSION["uid"]. "&query=".$query."\" width=0 height=0 />";
			else
				echo "<img src=\"/site/behavior/behavior.php?uid=0&query=".$query."\" width=0 height=0 />";
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
				?>
			</div>
			
			<div id="main">
				<?php  if(!$login) require_once('./tools/login_section.php'); ?>
				<div id="searchret">
					<h2>Papers Discussing : <?php echo "\"" . $query . "\"" ?></h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					renderSearchPapers($papers, urlencode($query), $related_authors, $related_users);
					?>
				</div>
				<?php
                    $global=$dom->getElementsByTagName('global');
                    $hits=0;
                    foreach($global as $g){
                        $hits=$g->getElementsByTagName('hits')->item(0)->nodeValue;
                    }
					$pageCount=ceil($hits/$limit);
                    renderSearchPage($page,$pageCount,$query);
                ?>
			</div>
			<div id="side">
				<?php renderRelatedUsers($related_users); ?>
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