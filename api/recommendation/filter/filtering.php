<?php

function filtering($behaviors, $recommendations)
{
	foreach($behaviors as $item=>$value)
	{
		if(array_key_exists($item, $recommendations[0]))
			unset($recommendations[0][$item]);
		if(array_key_exists($item, $recommendations[1]))
			unset($recommendations[1][$item]);
	}
}

?>