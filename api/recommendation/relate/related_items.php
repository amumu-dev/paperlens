<?php
function GetRelatedItems($item, $table_name, $topN)
{
	if($table_name == "default") return GetDefaultRelatedItems($item, "default", $topN);
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

function GetRelatedItemsBySearch($item, $topN)
{
	$ret = array();
	/*
	$result = mysql_query("select id,weight from sphinx  where query='@title \"" . str_replace(' ', '+', $item) . "\";mode=any;sort=expr:@weight*log2(3 + citations/1000) /(2030 - year);limit=".$topN. ";index=idx1';");
	if (!$result) {
	    return $ret;
	}
	
	while ($row = mysql_fetch_row($result))
	{
		$id = $row[0];
		if($id == $item) continue;
		$weight = $row[1];
		$ret[$id] = $weight;
	}
	*/
	return $ret;
}

function GetDefaultRelatedItems($item, $table_name, $topN)
{
	$ret = array();
	$title_result = mysql_query('select title from paper where id='.$item);
	if (!$title_result) return $ret;
	$title = '';
	while ($row = mysql_fetch_row($title_result))
	{
		$title = $row[0];
	}
	
	$result = mysql_query("select id,weight from sphinx  where query='@title \"" . str_replace(' ', '+', $title) . "\";mode=any;sort=expr:@weight*log2(3 + citations/1000) /(2030 - year);limit=".$topN. ";index=idx1';");
	if (!$result) {
	    return $ret;
	}
	while ($row = mysql_fetch_row($result))
	{
		$id = $row[0];
		if($id == $item) continue;
		$weight = $row[1];
		$ret[$id] = $weight;
	}
	
	arsort($ret);
	return array_slice($ret, 0, $topN, TRUE);
}

function GetRelatedItemsFromMultiTables($item, $tables, $topN)
{
	$ret = array();
	foreach($tables as $table_name=>$table_weight)
	{
		$related_items = GetRelatedItems($item, $table_name, $topN);
		foreach($related_items as $dst_id => $weight)
		{
			if(!array_key_exists($dst_id, $ret)) $ret[$dst_id] = 0;
			$ret[$dst_id] += $weight * $table_weight;
		}
	}
	arsort($ret);
	return array_slice($ret, 0, $topN, TRUE);
}

?>