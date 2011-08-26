<?php
session_start();
require_once('session.php');
require_once('config.php');
if(!$login) Header("Location: index.php");
require_once("functions.php");
$author = $_GET["author"];
$author_name = $_GET["name"];
$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/author.php?n=10&author=' . $author))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
$related_users = array();
?>
<html>
	<head>
		<title><?php echo $author_name; ?> Author Publications - <?php echo $SITE_NAME; ?> : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script src="./js/main.js" type="text/javascript"></script>
		<?
			include('./search/sug_js.php');
			include('./search/sug_css.php');
		?>
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
				<?
				include('./search/search_bar.php');
				?>
			</div>
			
			<div id="main">
				<div id="searchret">
					<h2>Publications of <font color=#647B0F><?php echo $author_name; ?></font></h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					renderPapers($papers, $related_authors, $related_users);
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
		<?php require_once('ga.php'); ?>
	</body>
</html>