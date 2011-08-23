<?php
require_once("../db.php");
function GetBehavior($uid)
{
	$ret = array();
	$result = mysql_query("select paper_id,behavior,weight from user_paper_behavior where user_id=$uid order by created_at desc");
	if (!$result) {
	    return $ret;
	}

	while ($row = mysql_fetch_row($result))
	{
		$paper_id = $row[0];
		$behavior = $row[1];
		$weight = 1;
		if($behavior < 3) $weight = 2;
		if(!array_key_exists($paper_id, $ret)) $ret[$paper_id] = $weight;
		//else $ret[$paper_id] += $weight;
	}
	arsort($ret);
	return $ret;
}


function GetSearchQuery($uid)
{
	$ret = array();
	$result = mysql_query("select query from user_search_log where user_id = $uid order by created_at desc limit 50");
	if (!$result) {
	    return $ret;
	}

	while ($row = mysql_fetch_row($result))
	{
		$query = $row[0];
		if(!array_key_exists($query, $ret)) $ret[$query] = 1;
		//else $ret[$query] += 1;
	}
	arsort($ret);
	return array_slice($ret, 0, 10, TRUE);
}
?>