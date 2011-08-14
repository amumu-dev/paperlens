<?php

include_once("userconfig.php");

include_once( $root_path.'api/weibo_oauth/config.php' );
include_once( $root_path.'api/weibo_oauth/weibooauth.php' );

$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();

//$uid;
//$uname;


$callback = "http://".$_SERVER['HTTP_HOST']."/site/user/weiboconcallback.php";
$callback = $callback."?tkey=".$keys['oauth_token']."&tsec=".$keys['oauth_token_secret'];

$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );

//return;
//here redirect to the page
Header("Location: $aurl");

return;

?>

<a href="<?php print $aurl; ?>">Use Oauth to login</a>

