<?php
$query = $_GET["query"];

$dom = new DOMDocument();
if(!$dom->load('http://127.0.0.1/api/search/search.php?query=' . str_replace(' ','+',$query)))
{
	echo 'load xml failed';
	return;
}

?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel=¡±stylesheet¡± type=¡±text/css¡± href=¡±./css/main.css¡±>
	</head>
	
	<body>
		<div id="content">
			<div id="header">
			</div>
			
			<div id="main">
				<div id="searchret">
					<?php
					$papers = $dom->getElementsByTagName('paper');
					foreach($papers as $paper)
					{
						$title = $paper->getElementsByTagName('title');
						echo '<span>' . $title->item(0)->nodeValue . '</span><br />';
						$author = $paper->getElementsByTagName('author');
						$k = 0;
					
					}
					?>
				</div>
			</div>
		</div>
	</body>
</html>