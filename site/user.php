<?php
session_start();
require_once('session.php');
if(!$login) Header("Location: index.php");
require_once("functions.php");
$uid = $_GET["uid"];
$name = $_GET["name"];
$dom = new DOMDocument();
if(!$dom->load("http://127.0.0.1/api/user/recommended.php?uid=$uid"))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
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
				<div id="logo">PaperLens</div>
				<?
				include('./search/search_bar.php');
				?>
			</div>
			
			<div id="main">
				<div id="searchret">
					<h2>Papers Recommended by : <?php echo "\"" . $name . "\"" ?></h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					$related_authors = renderPapers($papers);
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
		<div id="foot">&copy; PaperLens 2011</div>
		<div id="feedbackcode"></div>
	</body>
</html>