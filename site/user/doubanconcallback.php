<?php

/*
 * wangxing,wangxing.pku@gmail.com
 * 用户同意授权后跳转到此页面
 * 1.使用授权后的request token的key和sec换取access token
 * 2.获取用户id和用户名
 * 3.检查在数据库中是否有此id
 * 3.1如果没有此id，创建一个项，存储douban的id,name,ackey,acsec
 *    跳转到连接一个新的用户的界面
 * 3.2如果有此id
 * 3.2.1如果此id已经连接一个账号，直接登陆
 * 3.2.2如果此id没有连接本网站的账号，跳转到连接一个新用户的页面（connewuser.php）
 *
 * */
require_once("userconfig.php");
require_once($root_path.'api/douban_oauth/DoubanOAuth.php');
require_once("userfunc.php");

function getNamebyId($did){
	$dom = new DOMDocument();
	echo "http://api.douban.com/people/$did";
	if(!$dom->load("http://api.douban.com/people/$did")) echo "load xml error";
	return $dom->getElementsByTagName("db:uid")->item(0)->nodeValue;
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

if(checkDoubanIdExist($douban_user_id))
{
    $uid = geUidbyDuid($douban_user_id);
    $con_new_user_url = $con_new_user_url."?uid=".$uid;
    if(checkDoubanUserLinked($douban_user_id)){
        updateDoubanAC($uid,$douban_token,$douban_token_secret);
        loginUser($uid);
        Header("Location: /site/index.php");
    }
    else{
        Header("Location: $con_new_user_url");
    }
}
else{
    $uid = initDoubanUser($douban_user_id,$douban_name,$douban_token,$douban_token_secret);
    //print $douban_user_id.":".$douban_name.":".$douban_token.":".$douban_token_secret;
    //return;

    $con_new_user_url = $con_new_user_url."/?uid=".$uid;
    Header("Location: $con_new_user_url");
}

?>

