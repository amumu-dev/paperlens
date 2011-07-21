<?php
session_start();
require_once('session.php');
if(!$login) Header("Location: index.php");
require_once("functions.php");
$query = $_GET["query"];
$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/search/search.php?n=10&query=' . str_replace(' ','+',$query)))
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
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="/site/">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<div id="logo">PaperLens</div>
				<form action="search.php">
					<input class="search_box" type="text" name="query" value=<?php echo "\"" . $query . "\"" ?>/>
					<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
					<input class="search_button" type="submit" value="Search!" />
				</form>
			</div>
			
			<div id="main">
				<div id="searchret">
					<h2>Papers Discussing : <?php echo "\"" . $query . "\"" ?></h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					foreach($papers as $paper)
					{
						echo "<div class=\"paper\">";
						$title = $paper->getElementsByTagName('title');
						echo "<span class=\"title\"><a href=/site/paper.php>" . strTruncate($title->item(0)->nodeValue, 85) . "</a></span><br />";
						$booktitle = $paper->getElementsByTagName('booktitle');
						$year = $paper->getElementsByTagName('year');
						if(strlen($booktitle->item(0)->nodeValue) > 0)
							echo "<span class=\"info\"><a href=/site/paper.php>" . $booktitle->item(0)->nodeValue . "</a>&nbsp;" .$year->item(0)->nodeValue. "</span><br />";
						$authors = $paper->getElementsByTagName('author');
						$k = 0;
						echo "<span class=\"author\">by&nbsp;";
						while($author = $authors->item($k++) )
						{
							echo "<a href=/site/author.php>" . $author->nodeValue . "</a>&nbsp;";
							if(!array_key_exists($author->nodeValue, $related_authors))
							{
								$related_authors[$author->nodeValue] = 1;
							}
							else
							{
								$related_authors[$author->nodeValue]++;
							}
						}
						echo "</span><br />";
						echo "<span class=feedback><font color=#647B0F>&#9679;&nbsp;</font><a>Recommend</a>&nbsp;"
							. "<font color=#FFCC00>&#9679;&nbsp;</font><a>Like</a>&nbsp;"
							. "<font color=#BE1A21>&#9679;&nbsp;</font><a>Dislike</a>&nbsp;</span>";
						echo "</div>";
					}
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