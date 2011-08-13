<?php
require_once("recsys.php");
$uid = $_GET['uid'];
$relatedTables = array("papersim_author" => 1, "cite_citeseer"=>0.2, "default" => 0.1);
/*$recommendations = makingRecommendation($uid, $relatedTables);
header('Content-Type: text/xml');
arsort($recommendations[0]);
$n = 0;
echo "<result>";
foreach($recommendations[0] as $paper_id => $weight)
{
	if(++$n > 10) break;
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $paper_id) ;
}
echo "</result>";*/
?>