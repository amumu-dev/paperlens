<?php
include_once('userconfig.php');

include_once($root_path.'api/weibo_oauth/config.php');
include_once($root_path.'api/weibo_oauth/weibooauth.php');
include_once('userfunc.php');

$tkey = $_GET['tkey'];
$tsec = $_GET['tsec'];
$verify = $_GET['oauth_verifier'];

$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $tkey,$tsec);

$last_key = $o->getAccessToken($verify);
$ackey = $last_key['oauth_token'];
$acsec = $last_key['oauth_token_secret'];

//get the uid
//get the uname
$c = new WeiboClient(WB_AKEY,WB_SKEY,$ackey,$acsec);
$curuser = $c->verify_credentials();
$suid = $curuser['id'];
$uname = $curuser['screen_name'];

//redirect page,with the uid in the parameter
$con_new_user_url = "http://".$_SERVER['HTTP_HOST']."/site/user/conexistuser.php";
$login_redirect_url = "http://".$_SERVER['HTTP_HOST'].'/site/index.php';
if(checkSinaIdExist($suid)){
    //print "exist";
    //return;
    $uid = getUidbySuid($suid);
    $con_new_user_url = $con_new_user_url."?uid=".$uid;
    //check link info,if already link to an account , login
    if(checkSinaUserLinked($suid)){
        //here update the linked info ackey and acsec
        //throw new Exception("update the ackey info");
        //Header("Location: $login_redirect_url");
        updateSinaAC($uid,$ackey,$acsec);
        loginUser($uid);
	Header("Location: $login_redirect_url");
    }
    else{
        //if not linked,redirect to the connewuser.php
        Header("Location: $con_new_user_url");
    }
}
else{
    #$thesql = "insert into user (sinaid,sinaname,sinaackey,sinaacsec) values ('".$suid."','".$uname."','".$ackey."','".$acsec.");";
    #print $thesql;

    #return;
    //insert a line, redirect to the connewuser php
    $uid = initSinaUser($suid,$uname,$ackey,$acsec);
    $con_new_user_url = $con_new_user_url."?uid=".$uid;
    Header("Location: $con_new_user_url");
}

/*
$ver = $c->verify_credentials();

foreach($ver as $k=>$v)
{
    print $k.":".$v."<br/>";
}
 */


?>
