<?php
require_once('../../api/db.php');
$uid = $_GET['uid'];
$paper_id = $_GET['paper'];
$search_query = $_GET['search_query'];
$query = "replace into bookmark (user_id, paper_id, query, created_at) values (".$uid.", ".$paper_id.", '$search_query', '". date("Y-m-d H:i:s"). "')";
mysql_query($query);
?>