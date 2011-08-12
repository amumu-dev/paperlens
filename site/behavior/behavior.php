<?php
require_once('../../api/db.php');
$uid = $_GET['uid'];
if(isset($_GET['paper']) && isset($_GET['behavior']))
{
	$behavior = $_GET['behavior'];
	$paper_id = $_GET['paper'];
	$weight = $_GET['w'];
	$query = "replace into user_paper_behavior (user_id, paper_id, behavior, created_at, weight) values (".$uid.", ".$paper_id.", ".$behavior.", '". date("Y-m-d H:i:s"). "', ".$weight.")";
	mysql_query($query);
}

if(isset($_GET['query']))
{
	$search_query = $_GET['query'];
	$query = "replace into user_search_log (user_id, query, created_at) values (".$uid.", '".$search_query."', '". date("Y-m-d H:i:s"). "')";
	echo $query;
	mysql_query($query);
}
?>