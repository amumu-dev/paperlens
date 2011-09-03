<?php

function ranking($recommendations)
{
	$used_reasons = array();
	foreach($recommendations[1] as $paper_id => $reasons)
	{
		arsort($reasons);
		foreach($reasons as $reason_id => $weight)
		{
			if(!array_key_exists($reason_id, $used_reasons))
			{
				$used_reasons[$reason_id] = 1;
			}
			else $used_reasons[$reason_id]++;
			
			$used_count = $used_reasons[$reason_id];
			if($used_count > 1)
			{
				$recommendations[0][$paper_id] /= 100 + $used_count * $used_count;
			}
			break;
		}
	}
}
?>