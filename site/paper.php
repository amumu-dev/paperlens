<?php
session_start();
require_once('session.php');
require_once('config.php');
require_once("functions.php");
$paper = $_GET["id"];

$paper_dom = new DOMDocument();
if(!$paper_dom->load('http://50.18.105.189/api/paper.php?abstract=1&id=' . $paper))
{
	echo 'load xml failed';
	return;
}

$dom = new DOMDocument();
//if(!$dom->load('http://50.18.105.189/api/recommendation/relate/default_related_items_xml.php?id=' . $paper))
if(!$dom->load('http://50.18.105.189/api/recommendation/relate/related_items_xml.php?tables=papersim_author-1|cite_citeseer-0.3|default-0.5&id=' . $paper))
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
				echo "<img src=\"/site/behavior/behavior.php?uid=" . $_SESSION["uid"] . "&paper=$paper&behavior=3&w=1\"  width=0 height=0 />";
			else echo "<img src=\"/site/behavior/behavior.php?uid=0&paper=$paper&behavior=3&w=1\"  width=0 height=0 />";
		?>
		<div id="content">
			<div id="header">
				<?php if($login){ ?>
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<?php } else echo "&nbsp;<br>"; ?>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
				<?
				include('./search/search_bar.php');
				renderFirstPaper($paper_dom);
				?>
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
	</body>
</html>