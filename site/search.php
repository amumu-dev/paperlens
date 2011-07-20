<?php
require_once("functions.php");
$query = $_GET["query"];
$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/search/search.php?n=20&query=' . str_replace(' ','+',$query)))
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
				<div id="logo">PaperLens</div>
				<form action="search.php">
					<input class="search_box" type="text" name="query" value=<?php echo "\"" . $query . "\"" ?>/>
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
						echo "<span class=\"title\">" . strTruncate($title->item(0)->nodeValue, 48) . "</span><br />";
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
						echo "</div>";
					}
					?>
					<h2>Authors About : <?php echo "\"" . $query . "\"" ?></h2>
					<div class="related_author">
					<?php
					arsort($related_authors);
					array_slice($related_authors, 0, 8);
					foreach($related_authors as $author=>$weight)
					{
						if($weight < 2) continue;
						echo "<span class=\"author\"><a href=/site/author.php>" . $author . "</a></span>";
					}
					?>
					</div>
					</span>
				</div>
			</div>
		</div>
	<br/>
	</body>
</html>