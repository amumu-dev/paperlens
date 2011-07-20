<?php

function strTruncate($buf, $maxLength)
{
	$tks = split(" ", $buf);
	$ret = "";
	$all = FALSE;
	foreach($tks as $word)
	{
		if(strlen($ret) + strlen($word) >= $maxLength)
			break;
		$ret .= $word . " ";
		$all = TRUE;
	}
	if(!$all) $ret .= "...";
	return $ret;
}

?>