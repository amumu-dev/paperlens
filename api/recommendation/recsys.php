<?php
require_once("../db.php");

//extrac user behaviors
require_once("./behavior/behavior.php");

//get related items of a given item
//require_once("./relate/related_items.php");
require_once("./relate/default_related_items.php");

//generate raw recommendations from a set of source items
require_once("./core/recommendation_core.php");

require_once("./explanation/explanation.php");
require_once("./rank/ranking.php");
require_once("./filter/filtering.php");

function combineArray(&$A, $B, $w)
{
	foreach($B as $key => $value)
	{
		if(!array_key_exists($key, $A))
		{
			$A[$key] = 0;
		}
		$A[$key] += $value * $w;
	}
}

function makingRecommendation($uid, $relatedTables)
{
	$recommendations = array();
	$behaviors = GetBehavior($uid);
	$features = $behaviors;
	foreach($relatedTables as $table_name => $table_weight)
	{
		combineArray($recommendations, recommendationCore($features, $table_name, 10), $table_weight);
	}
	combineArray($recommendations, recommendationCore($features, $table_name, 10), $table_weight);
	selectExplanation($recommendations);
	filtering($recommendations);
	ranking($recommendations);
	return $recommendations;
}
$uid = $_GET['uid'];
$relatedTables = array("default" => 1);
$recommendations = makingRecommendation($uid, $relatedTables);
header('Content-Type: text/xml');
arsort($recommendations);
$n = 0;
echo "<result>";
foreach($recommendations as $paper_id => $weight)
{
	if(++$n > 10) break;
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $paper_id) ;
}
echo "</result>";
?>