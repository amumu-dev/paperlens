<?php

require_once('DoubanOAuth.php');
require_once('../db.php');
echo "key and sec";

$tk =  $_GET["tkey"];
$ts =  $_GET["tsecret"];
echo $tk.":";
echo $ts;


$myclient = new DoubanOAuthClient($my_consumer_key,$my_consumer_secret);

$at_arr = $myclient->get_access_token($tk,$ts);
$douban_token_secret = $at_arr["oauth_token_secret"];
$douban_token = $at_arr["oauth_token"];
$douban_user_id= $at_arr["douban_user_id"];

foreach($at_arr as $k=>$v){
    echo $k.":".$v."<br>";
}

$result = mysql_query("insert into user (douban_token_secret, douban_token, douban_user_id) values ('$douban_token_secret', '$douban_token', $douban_user_id)");
if ($result) echo "Insert OK";
?>
