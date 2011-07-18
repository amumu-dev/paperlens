<?php
require_once("../db.php");

require_once("behavior.php");
require_once("related_items.php");
require_once("recommendation_core.php");

function combineArray(&$A, $B, $w)
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
	$recommendations = s
	$behaviors = GetBehavior($uid);
	$features = $behaviors;
	
	foreach($relatedTables as $table_name => $table_weight)
	{
		combineArray(&$recommendations, recommendationCore($features, $table_name), $table_weight);
	}
	
	selectExplanation(&$recommendations);
	filtering(&$recommendations);
	ranking(&$recommendations);
	
}

$relatedTables = array("papersim_cite" => 1);
makeingRecommendation(0);
?>