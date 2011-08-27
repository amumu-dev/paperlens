<?php
require_once("recsys.php");
$uid = $_GET['uid'];
$relatedTables = array("papersim_author" => 1, "cite_citeseer"=>0.2, "default"=>1);
makingRecommendation($uid, $relatedTables);
$recommendations = makingRecommendation($uid, $relatedTables);
header('Content-Type: text/xml');

arsort($recommendations[0]);
$n = 0;
echo "<recommendation>";
foreach($recommendations[0] as $paper_id => $weight)
{
	if(++$n > 10) break;
	arsort($recommendations[1][$paper_id]);
	$xml = file_get_contents('http://127.0.0.1/api/paper.php?id=' . $paper_id) ;
	$p1 = strrpos($xml, "</paper>");
	$xml = substr($xml, 0, $p1);
	$xml .= "<reason>";
	foreach($recommendations[1][$paper_id] as $reason_id =>$reason_weight)
	{
		$reason_xml = file_get_contents('http://127.0.0.1/api/paper.php?recuser=0&author=0&id=' . $reason_id) ;
		$reason_xml = str_replace("<paper>", "", $reason_xml);
		$reason_xml = str_replace("</paper>", "", $reason_xml);
		$xml .=  $reason_xml;
		break;
	}
	$xml .=  "</reason></paper>";
	echo $xml;
}
echo "</recommendation>";
?>