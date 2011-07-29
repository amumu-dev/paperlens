<?php

require_once('./DoubanOauth.php');
require_once('../db.php');
echo "key and sec";

$tk =  $_GET["tkey"];
$ts =  $_GET["tsecret"];
echo $tk.":";
echo $ts;


$myclient = new DoubanOAuthClient($my_consumer_key,$my_consumer_secret);

$at_arr = $myclient->get_access_token($tk,$ts);

foreach($at_arr as $k=>$v){
    echo $k.":".$v."<br>";
}

//here execute the mysql sentence
/*
 *
 *
 *
 *
 */

?>
