<?php
require_once("../db.php");
function GetBehavior($uid)
{
	$ret = array();
	$result = mysql_query('select paper_id,weight from user_paper_behavior where user_id='.$uid);
	if (!$result) {
	    return $ret;
	}

	while ($row = mysql_fetch_row($result))
	{
		$paper_id = $row[0];
		$weight = $row[1];
		if(!array_key_exists($paper_id, $ret)) $ret[$paper_id] = $weight;
		else $ret[$paper_id] += $weight;
	}
	return $ret;
}

?>