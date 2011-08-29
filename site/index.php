<?php
session_start();
require_once('session.php');
require_once('config.php');
if($login)
{
	require_once('../api/db.php');
	require_once("functions.php");
	
        $email = $row[1];
	$dom = new DOMDocument();
	$home_type = 'recommendation';
	if(isset($_GET['type'])) $home_type = $_GET['type'];
	if($home_type == 'recommendation')
		$dom->load("http://127.0.0.1/api/recommendation/recsys_reason_xml.php?uid=" .$_SESSION['uid']);
	else
		$dom->load("http://127.0.0.1/api/user/" . $home_type . ".php?uid=" .$_SESSION['uid']);
	$related_authors = array();
	$related_users = array();
	$papers = $dom->getElementsByTagName('paper');
	if($home_type == 'recommendation')
	{
		if($papers->length == 0 && strlen($keywords) > 0)
		{
			$result = mysql_query("SELECT keywords,email FROM user WHERE id=".$_SESSION['uid']);
			if (!$result) die("error when get keywords of user");
			$row = mysql_fetch_row($result);
			$keywords = $row[0];
			$keywords = trim($keywords, " ,.;");
			$keywords = str_replace(',', '|', $keywords);
			$dom->load('http://127.0.0.1/api/search/search.php?n=10&query=' . str_replace(' ','+',$keywords));
			$papers = $dom->getElementsByTagName('paper');
		}
	}
}
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
		<div id="content">
			<div id="header">
				<?php
				if($login){
				?>
				<div id="toolbar">
					<span>Hi <?php echo $email; ?></span>&nbsp;&nbsp;
					<span><a href="./index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="./logout.php">Log out</a></span>
				</div>
				<?php } else echo "&nbsp;<br>"; ?>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
				<?
				include('./search/search_bar.php');
				?>
			</div>
			<?php
			if($login==FALSE){
			?>
			<div style="width:100%;float:left;clear:both;margin-top:30px;">
				<div id="intro">
					<h3><?php echo $SITE_NAME; ?> is an academic paper recommender system which can : </h3>
					<ul>
						<li>Recommend academic papers by analyzing your historical preference</li>
						<li>Recommend related papers of given paper</li>
					</ul>
				</div>
				<div id="login">
					<?php require_once('./tools/login_section.php'); ?>
				</div>
			</div>
			<?php
			}
			else
			{
				
				if($papers->length > 0)
				{
			?>
				<div id="main">
				<span id="home_type">
					<a href="index.php?type=bookmarked">Your Bookmarks</a>
					<a href="index.php?type=recommended">Recommended by You</a>
					<a href="index.php?type=recommendation">Recommendation for You</a>
				</span>
				<?php
					if($home_type == 'recommendation') renderRecommendationPapers($papers, $related_authors, $related_users);
					else renderPapers($papers, $related_authors, $related_users, $paper);
				?>
				</div>
				<div id="side">
					<h2>Admin</h2>
					<div class="related_author">
						<span><a href="./user/edit.php">Edit My Info</a></span>
					</div>
					<?php renderRelatedUsers($related_users); ?>
					<h2>Related Authors</h2>
					<div class="related_author">
					<?php
					renderRelatedAuthors($related_authors);
					?>
					</div>
				</div>
			<?php }
				else
				{
				?>
				<div id="main">
				&nbsp;<br/>&nbsp;<br/>
				<span>As a new user, we need more information to make recommendations for you.</span>
				<span style="color:#647B0F;">Could you please input some tags <font color=red>(seprated by comma)</font> which can bestly describe your interest:</span>
				<form style="width:100%;float:left;" action="coldstart.php" method="post">
				<input style="width:80%;float:left;height:26px;line-height:26px;" type="text" name="keywords" value=""/>
				<input style="width:15%;float:left;height:26px;line-height:26px;" type="submit" value="Submit"/>
				</form>
				<span style="color:#1F81CD;width:100%;float:left;">Or you can use search engine now to find papers you like.</span>
				</div>
				<?php
				}
			}
			?>
		</div>
		<div id="foot">&copy; <?php echo $SITE_NAME; ?> 2011</div>
		<div id="feedbackcode"></div>
		<?php require_once('ga.php'); ?>
	</body>
</html>