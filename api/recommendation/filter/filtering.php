<?php

function filtering($behaviors, $recommendations)
{
	foreach($behaviors as $item=>$weight)
	{
		if(in_array($item, $recommendations[0]))
		{
			unset($recommendations[0][$item]);
		}
	}
}

?>