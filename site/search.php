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
if(!$dom->load('http://127.0.0.1/api/search/search_2.php?query=' . str_replace(' ','+',$query). '&offset=' . $offset. '&limit=' .$limit))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
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
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
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
					$related_authors = renderSearchPapers($papers, $query);
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