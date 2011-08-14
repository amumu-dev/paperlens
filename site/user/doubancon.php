<?php
require_once("userconfig.php");
require_once($root_path.'api/douban_oauth/DoubanOAuth.php');

$myclient = new DoubanOAuthClient($d_consumer_key,$d_consumer_secret);
$rt_arr = $myclient->get_request_token();

$key = $rt_arr['oauth_token'];
$sec = $rt_arr['oauth_token_secret'];

$callbackurl="http://".$_SERVER['HTTP_HOST']."/site/user/doubanconcallback.php";
//$callbackurl = $callbackurl."?tkey=$key&tsec=$sec";

$auth_url = $myclient->get_authorization_url($rt_arr['oauth_token'],$rt_arr['oauth_token_secret'],$callbackurl);

Header("Location: $auth_url");

?>

