<?php
session_start();
require_once('session.php');
if(!$login) Header("Location: index.php");
require_once("functions.php");
$author = $_GET["author"];
$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/search/search_author.php?n=10&query=' . str_replace(' ','+',$author)))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
?>
<html>
	<head>
		<title><?php echo $author; ?> Author Publications - PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script src="./js/main.js" type="text/javascript"></script>
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
				<form action="search.php">
					<input class="search_box" type="text" name="query" value=""/>
					<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
					<input class="search_button" type="submit" value="Search!" />
				</form>
			</div>
			
			<div id="main">
				<div id="searchret">
					<h2>Publications of <?php echo $author; ?></h2>
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
				arsort($related_authors);
				$related_authors = array_slice($related_authors, 0, 16);
				foreach($related_authors as $author=>$weight)
				{
					echo "<span class=\"author\"><a href=/site/author.php>" . $author . "</a></span><br>";
				}
				?>
				</div>
			</div>
		</div>
		<div id="foot">&copy; PaperLens 2011</div>
	</body>
</html>