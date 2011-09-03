<?php
require_once("../db.php");
function GetBehavior($uid)
{
	$ret = array();
	$result = mysql_query("select paper_id,weight from user_paper_behavior where behavior=3 and user_id=$uid order by created_at desc limit 30");
	if (!$result) {
		while ($row = mysql_fetch_row($result))
		{
			$paper_id = $row[0];
			$weight = 0.01;
			if(!array_key_exists($paper_id, $ret)) $ret[$paper_id] = $weight;
		}
	}
	mysql_free_result($result);
	
	$result = mysql_query("select paper_id from recommend where user_id=$uid order by created_at desc");
	if ($result) {
		$k = 0;
		while ($row = mysql_fetch_row($result))
		{
			$paper_id = $row[0];
			$weight = 2 / (1 + 0.03*$k);
			++$k;
			if(!array_key_exists($paper_id, $ret)) $ret[$paper_id] = $weight;
		}
	}
	mysql_free_result($result);
	
	$result = mysql_query("select paper_id from bookmark where user_id=$uid order by created_at desc");
	if ($result) {
		$k = 0;
		while ($row = mysql_fetch_row($result))
		{
			$paper_id = $row[0];
			$weight = 1 / (1 + 0.03*$k);
			++$k;
			if(!array_key_exists($paper_id, $ret)) $ret[$paper_id] = $weight;
		}
	}
	mysql_free_result($result);
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