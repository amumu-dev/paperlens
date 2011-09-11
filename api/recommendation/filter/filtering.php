<?php

function filtering($behaviors, &$recommendations, $debug = FALSE)
{
	foreach($behaviors as $item=>$weight)
	{
		if($debug) echo $item . "\n";
		if(array_key_exists($item, $recommendations[0]))
		{
			if($debug) echo $item . "\n";
			unset($recommendations[0][$item]);
			unset($recommendations[1][$item]);
		}
		if($debug) echo "\n";
	}
}

?>