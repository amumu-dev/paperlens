<?php
session_start();
require_once('../session.php');
require_once('../../api/db.php');
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

$user_id = $_SESSION['uid'];
echo $user_id;
mysql_query("update user set doubanid='$douban_user_id', dname='$douban_name', dackey='$douban_token', dacsec='$douban_token_secret' where id=$user_id");

Header("Location: /site/index.php");
?>

