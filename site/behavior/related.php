<?php
require_once('../../api/db.php');
$uid = $_GET['uid'];
$src_id = $_GET['src'];
$dst_id = $_GET['dst'];
$weight = $_GET['w'];

$query = "insert into papersim_feedback (src_id, dst_id, weight) values (".$src_id.", ".$dst_id.", ".$weight.") on duplicate key update weight = weight + 1";
mysql_query($query);

?>