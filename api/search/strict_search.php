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
require_once('../paper_func.php');

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
cleanQuery($query);

$scl = getSphinxServer($offset, $limit);
if($scl->IsConnectError()){
	die('search server down!');
}

$user_id = 0;
if(isset($_GET['user'])) $user_id = $_GET['user'];

$scl->setFieldWeights(array('title'=>2000));
$scl->SetMatchMode(SPH_MATCH_ALL);
$scl->SetSortMode ( SPH_SORT_EXPR,"@weight*log2(3 + search_rank/1000) /(2030 - year)");
$index_name='idx1';
$res = $scl->Query ($query, $index_name );

if($res){
	$pids = $data = $title = array();
	foreach($res['matches'] as $k=>$v){
		echo $k . "," . $v . " ";
	}
}
?>