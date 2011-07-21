<?php
session_start();
require_once('session.php');
if($login)
{
	require_once('../api/db.php');
	require_once("functions.php");
	$result = mysql_query("SELECT keywords,email FROM user WHERE id=".$uid);
	if (!$result) die("error when get keywords of user");
	$row = mysql_fetch_row($result);
	$keywords = $row[0];
	$email = $row[1];
	$dom = new DOMDocument();
	if(!$dom->load('http://127.0.0.1/api/search/search.php?n=10&query=' . str_replace(' ','+',$keywords)))
	{
		echo 'load xml failed';
		return;
	}
	$related_authors = array();
}
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
				<?php
				if($login){
				?>
				<div id="toolbar">
					<span>Hi <?php echo $email; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<?php } ?>
				<div id="logo">PaperLens</div>
				<form action="search.php">
					<input class="search_box" type="text" name="query" value=""/>
					<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
					<input class="search_button" type="submit" value="Search!" />
				</form>
			</div>
			<?php
			if($login==FALSE){
			?>
			<div class="login">
				<form action="signup.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="password" name="password" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Research Area&nbsp;</span><input type="text" name="keywords" class="textinput"/></div>
					<input type="submit" value="SignUp" class="button" />
				</form>
			</div>
			
			<div class="login">
				<form action="login.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="password" name="password" class="textinput"/></div>
					<input type="submit" value="Login" class="button" />
				</form>
			</div>
			<?php
			}
			else
			{
			?>
			<div id="main">
			<h2>Paper Recommendations : </h2>
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
			<?php } ?>
		</div>
	</body>
</html>