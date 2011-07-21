<?php
require_once('../api/db.php');
$behavior = $_GET['behavior'];
$uid = $_GET['uid'];
$paper_id = $_GET['paper'];
$weight = $_GET['w'];
$query = "insert into user_paper_behavior (user_id, paper_id, behavior, created_at, weight) values (".$uid.", ".$paper_id.", ".$behavior.", '". date("Y-m-d H:i:s"). "', ".$weight.")";
mysql_query($query);
?>