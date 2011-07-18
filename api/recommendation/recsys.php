<?php
require_once("../db.php");

require_once("behavior.php");
require_once("related_items.php");
require_once("recommendation_core.php");
require_once("explanation.php");
require_once("ranking.php");
require_once("filtering.php");

function combineArray($A, $B, $w)
{
	foreach($B as $key => $value)
	{
		if(!array_key_exists($A, $key))
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
	
	selectExplanation($recommendations);
	filtering($recommendations);
	ranking($recommendations);
	
}

$relatedTables = array("papersim_cite" => 1);
makingRecommendation(0, $relatedTables);
?>