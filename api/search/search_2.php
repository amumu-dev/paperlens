<?php
/**
* paperlens search api via sphinx_api
* see also@/api/search/sphinx_api/sphinxapi.php
*       higuojin@gmail.com
*       07/26 2011
*
*/
require_once( 'sphinx_server.php' );
require_once('../db.php');

function getAuthorName($author_id)
{
	$result = mysql_query('select name from author where id='.$author_id);
	if (!$result) {
	    return '';
	}

	while ($row = mysql_fetch_row($result))
	{
		$name = $row[0];
	}
	mysql_free_result($result);
	return htmlspecialchars($name);
}

function cleanQuery(&$query)
{
	$query = str_replace('-', ' ', $query);
	$query = str_replace('\'', ' ', $query);
	$query = str_replace(':', ' ', $query);
}

header('Content-Type: text/xml');
$_DEFAULT_OFFSET=0;
$_DEFAULT_LIMIT=15;

$offset=$_DEFAULT_OFFSET;
$limit=$_DEFAULT_LIMIT;
if(!isset($_GET['query'])){
        die('');
}
if(isset($_GET['offset'])){
    $offset=intval($_GET['offset']);
	if(!is_int($offset)){
		$offset=$_DEFAULT_OFFSET;
	}
}

if(isset($_GET['limit'])){
    $limit=intval($_GET['limit']);
	if(!is_int($limit)){
		$limit=$_DEFAULT_LIMIT;
	}
}
$query = $_GET['query'];

$scl = getSphinxServer($offset, $limit);
if($scl->IsConnectError()){
	die('search server down!');
}

$scl->setFieldWeights(array('title'=>2000));
$scl->SetMatchMode(SPH_MATCH_EXTENDED2);
#$scl->SetRankingMode(SPH_RANK_BM25);
$scl->SetSortMode ( SPH_SORT_EXPR,"@weight*log2(3 + search_rank/1000) /(2030 - year)");
$index_name='idx1';
$res = $scl->Query ($query, $index_name );

//no results
echo "<result>";
if(!$res || $res['total']==0){
	//if no results, giving some suggestions here;
	echo "<noresults>null</noresults>";
}
else{
	//highlight options
	$opts = array(
	'before_match' => '<span class="high">',
	'after_match'  => '</span>',
    'around' => 80,
	//'limit' => 256, default 256
	//'chunk_separator' => '..',default '...'
	);
	
	$pids = $data = $title = array();
	foreach($res['matches'] as $k=>$v){
		$pids[] = $k;
	}
	$i1 = 0;
	if(strlen($query) > 10)
	{
		foreach($pids as $src_id)
		{
			if(++$i1 > 10) break;
			$i2 = 0;
			foreach($pids as $dst_id)
			{
				if(++$i2 > 10) break;
				if($src_id == $dst_id) continue;
				mysql_query("insert into papersim_search (src_id, dst_id, weight) values ($src_id, $dst_id, 1) on duplicate key update weight = weight + 1");
			}
		}
	}
	$pid = implode(',',$pids);
	$result = mysql_query("select id, title, booktitle,year,journal,abstract,type from paper where id in ($pid)");
	while ($row = mysql_fetch_row($result))
	{
		$tmp_row=array();
		if("www" == $row[6]){continue;}
		$tmp_row['id'] = $row[0];
		$tmp_row['title'] = $row[1];
		$tmp_row['booktitle'] = $row[2];
		$tmp_row['year'] = $row[3];
		$tmp_row['journal'] = $row[4];
		$tmp_row['abstract'] = $row[5];
		//get author
		$res_auth = mysql_query('select author_id from paper_author where paper_id='.$row[0].' order by rank');
		if (!$res_auth) {
			die('Query failed: ' . mysql_error());
		}

		while ($r = mysql_fetch_row($res_auth))
		{
			$tmp_row['author'][$r[0]] = getAuthorName($r[0]);
		}
		mysql_free_result($res_auth);
		
		$data[]=$tmp_row;
		$title[]=$row[1];
	}
	
	//output global results info
    echo "<global>";
    echo "<hits>".$res['total']."</hits>";
    echo "</global>";
	
	//replace with highlights and output results
	$title = $scl->BuildExcerpts($title,$index_name,$query,$opts);
	foreach($data as $k=>$v){
		echo "<paper>";
		echo "<id>" . $data[$k]['id'] . "</id>";
		echo "<title>" . htmlspecialchars($data[$k]['title']) . "</title>";
		echo "<hightitle>" . htmlspecialchars($title[$k]). "</hightitle>";
		if(strlen( $data[$k]['booktitle'] ) > 0)
			echo "<booktitle>" . htmlspecialchars($data[$k]['booktitle']) . "</booktitle>";
		else echo "<booktitle>" . htmlspecialchars($data[$k]['journal']) . "</booktitle>";
		echo "<year>" . htmlspecialchars($data[$k]['year']) . "</year>";
		echo "<abstract>" . htmlspecialchars($data[$k]['abstract'] ) . "</abstract>";
		foreach($data[$k]['author'] as $author_id => $author_name)
		{
			echo "<author><id>" . $author_id. "</id><name>".$author_name."</name></author>";
		}
		echo "</paper>";
	}
}
echo "</result>";
?>