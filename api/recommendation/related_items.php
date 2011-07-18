<?php
require_once("../db.php");
function GetRelatedItems($item, $table_name, $topN)
{
	$ret = array();
	$result = mysql_query("select dst_id,weight from " . $table_name . " where src_id=" . $item . " order by weight desc limit " . $topN);
	if (!$result)
	{
		return $ret;
	}
	while ($row = mysql_fetch_row($result))
	{
		$dst_id = $row[0];
		$weight = $row[1];
		$ret[$dst_id] = $weight;
	}
	return $ret;
}

?>