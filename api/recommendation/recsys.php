<?php
require_once("../db.php");

//extrac user behaviors
require_once("./behavior/behavior.php");

//get related items of a given item
//require_once("./relate/related_items.php");
//require_once("./relate/related_items.php");
require_once("./relate/redis_cache_related_items.php");

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

function combineRecommendations(&$A, $B, $w)
{
	
	combineArray($A[0], $B[0], $w);
	foreach($B[1] as $key=>$value)
	{
		if(!array_key_exists($key, $A[1]))
		{
			$A[1][$key] = $value;
		}
		else
		{
			combineArray($A[1][$key], $B[1][$key], $w);
		}
	}
}

function makingRecommendation($uid, $relatedTables)
{
	$recommendations = array();
	array_push($recommendations, array());
	array_push($recommendations, array());
	
	$behaviors = GetBehavior($uid);
	$features = array_slice($behaviors, 0, 10, TRUE);
	foreach($relatedTables as $table_name => $table_weight)
	{
		$one_recommendations = recommendationCore($features, $table_name, 5);
		combineRecommendations($recommendations,$one_recommendations , $table_weight);
	}
	/*
	if(count($recommendations[0]) < 10)
	{
		$querys = GetSearchQuery($uid);
		$query_based_recommendations = recommendationCore($features, $table_name, 5, 'query');
		combineRecommendations($recommendations,$query_based_recommendations , 0.1);
	}
	*/
	/*selectExplanation($recommendations);*/
	filtering($behaviors, $recommendations);
	ranking($recommendations);
	return $recommendations;
}
/*
$uid = $_GET['uid'];
$relatedTables = array("papersim_author" => 1);
$recommendations = makingRecommendation($uid, $relatedTables);
header('Content-Type: text/xml');
arsort($recommendations[0]);
$n = 0;
echo "<result>";
foreach($recommendations[0] as $paper_id => $weight)
{
	if(++$n > 10) break;
	arsort($recommendations[1][$paper_id]);
	echo "<recommendation>";
	echo "<item>";
	echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $paper_id) ;
	echo "</item>";
	echo "<reason>";
	foreach($recommendations[1][$paper_id] as $reason_id =>$reason_weight)
	{
		echo file_get_contents('http://127.0.0.1/api/paper.php?id=' . $reason_id) ;
		break;
	}
	echo "</reason>";
	echo "</recommendation>";
}
echo "</result>";
*/
?>