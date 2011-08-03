<?php
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

function GetRelatedItemsFromMultiTables($item, $tables, $topN)
{
	$ret = array();
	echo $item . " ";
	foreach($tables as $table_name=>$table_weight)
	{
		echo $table_name . " &nbsp; " . $table_weight;
		echo "select dst_id,weight from " . $table_name . " where src_id=" . $item . " order by weight desc limit " . $topN;
		$result = mysql_query("select dst_id,weight from " . $table_name . " where src_id=" . $item . " order by weight desc limit " . $topN);
		if (!$result)
		{
			return $ret;
		}
		while ($row = mysql_fetch_row($result))
		{
			$dst_id = $row[0];
			$weight = $row[1];
			if(!array_key_exists($dst_id, $ret)) $ret[$dst_id] = 0;
			$ret[$dst_id] += $weight * $table_weight;
		}
	}
	//arsort($ret);
	//array_splice($ret, $topN);
	return $ret;
}

?>