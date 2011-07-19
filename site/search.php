<?php
$query = $_GET["query"];

$dom = new DOMDocument();
if(!$dom=>load('http://127.0.0.1/api/search/search.php?query=' . str_replace(' ','+',$query)))
{
	echo 'load xml failed';
	return;
}

$papers = $dom->getElementsByTagName('paper');
foreach($papers as $paper)
{
	$title = $paper->getElementsByTagName('title');
	echo $title->item(0)->nodeValue;
}

?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	
	<body>
		<div id="header">
		</div>
		
		<div id="main">
			<div id="searchbox">
				<form action="search.php" method="get">
					<input type="text" name="query" />
					<input type="submit" value="Search!" />
				</form>
			</div>
		</div>
	</body>
</html>