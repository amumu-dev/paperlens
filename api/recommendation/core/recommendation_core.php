<?php

function recommendationCore($features, $table_name, $topN)
{
	$ret_weight = array();
	$ret_reason = array();
	foreach($features as $item=>$preference)
	{
		$related_items = GetRelatedItems($item, $table_name, $topN);
		$max_sim = max(array_values($related_items));
		foreach($related_items as $related_item=>$similarity)
		{
			if(!array_key_exists($related_item, $ret_weight))
			{
				$ret_weight[$related_item] = 0;
				$ret_reason[$related_item] = array();
			}
			$ret_weight[$related_item] += $preference * $similarity / $max_sim;
			if(!array_key_exists($item, $ret_reason[$related_item]))
			{
				$ret_reason[$related_item][$item] = 0;
			}
			$ret_reason[$related_item][$item] += $preference * $similarity / $max_sim;
		}
	}
	$ret = array();
	$ret['weight'] = $ret_weight;
	$ret['reason'] = $ret_reason;
	return $ret;
}

?>