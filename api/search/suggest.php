<?
/**
*       paperlens search api via sphinx_api
*       higuojin@gmail.com
*       07/26 2011
*/
require( "sphinx_api/sphinxapi.php" );

require_once('../db.php');
/**get suggest text by id*/
function getSuggestText($id){
        if(!$id){
                return '';
        }
        $res = array();
        $result = mysql_query('select sug_text from suggest where id='.$id);
        if (!$result) {
                return '';
        }
        $res;
        //only get the first one
        while ($row = mysql_fetch_row($result))
        {
                $res = $row[0];
                break;
        }
        mysql_free_result($result);
        return $res;
}


if(!isset($_GET['key'])){
        die('');
}
$key = $_GET['key'];

$scl = new SphinxClient();
$scl->SetServer('127.0.0.1',3312);
$scl->SetMatchMode(SPH_MATCH_ALL);
$scl->SetWeights(array(100,1));
$scl->SetLimits(0,10);
$index_name='idx_suggest';

$res = $scl->Query ( $key.'*', $index_name );

//print_r($res);
if(!$res || $res['total']==0){
        die('');
}

$match = $res['matches'];
$jres=array();
foreach ($match as $k=>$v){
        //$jres[$i++]=file_get_contents('http://127.0.0.1/test/api/sug.php?id='.$k);
        //$jres[] = getSuggestText($k);
        //echo  getSuggestText($k)."\n";
        $jres[]  = array(
                "id" => $k,
                "data" => getSuggestText($k),
                "thumbnail" => '',
                "description" => ''
        );
}
echo json_encode($jres);

?>
