<?php
require_once("userconfig.php");
require_once($root_path.'api/douban_oauth/DoubanOAuth.php');
require_once("userfunc.php");

function getNamebyId($did){
	$dom = new DOMDocument();
	echo "http://api.douban.com/people/$did";
	if(!$dom->load("http://api.douban.com/people/$did")) echo "load xml error";
	$xpath = new DOMXPath($dom);
	$xpath->registerNamespace('db', "http://www.douban.com/xmlns/");
	$nodeList = $xpath->query("//db:uid");
	echo $nodeList->item(0)->nodeValue;
	return $nodeList->item(0)->nodeValue;
}

function getNamebyIdJson($did){
    $DOUBAN_PEOPLE_URL = "http://api.douban.com/people/".$did."?alt=json";
    $contents = file_get_contents($DOUBAN_PEOPLE_URL);
    print $contents;
    $jsonobj = json_decode($contents);
    //return $jsonobj['db:uid'];
    return $jsonobj->{'db:uid'}->{'\$t'};
}

$tk =  $_GET["tkey"];
$ts =  $_GET["tsecret"];

$myclient = new DoubanOAuthClient($d_consumer_key,$d_consumer_secret);

$at_arr = $myclient->get_access_token($tk,$ts);
$douban_token_secret = $at_arr["oauth_token_secret"];
$douban_token = $at_arr["oauth_token"];

$douban_user_id= $at_arr["douban_user_id"];
$douban_name = getNamebyId($douban_user_id);

$con_new_user_url = "http://".$_SERVER['HTTP_HOST']."/site/user/conexistuser.php";
$login_redirect_url = "http://".$_SERVER['HTTP_HOST'].'/site/index.php';

if(checkDoubanIdExist($douban_user_id) && checkDoubanUserLinked($douban_user_id))
{
	//updateDoubanAC($uid,$douban_token,$douban_token_secret);
        loginUser($uid);
        Header("Location: /site/index.php");
}
else{
    //$uid = initDoubanUser($douban_user_id,$douban_name,$douban_token,$douban_token_secret);

    $con_new_user_url = $con_new_user_url . "?type=douban&douban_uid=$douban_user_id&douban_name=$douban_name&douban_token=$douban_token&douban_token_secret=$douban_token_secret";
    Header("Location: $con_new_user_url");
}

?>

