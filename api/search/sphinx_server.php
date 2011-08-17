<?
/**
*               sphinx_server.php
*       return sphinx server api via sphinx_api
*       higuojin@gmail.com
*       08/07 2011
*/
require( "sphinx_api/sphinxapi.php" );
function getSphinxServer($offset=0, $limit=15){
        $scl = new SphinxClient();
        $scl->SetServer('127.0.0.1',3312);

        //default mode; SPH_MATCH_ALL;
        //see also@http://sphinxsearch.com/wiki/doku.php?id=sphinx_manual_chinese#?È«ÎÄËÑË÷ÉèÖÃ
        $scl->SetMatchMode(SPH_MATCH_ALL);
        $scl->SetLimits($offset,$limit);
        return $scl;
}
?>
