<?php

require_once("userfunc.php");

session_start();
require_once('../session.php');

if(!$login){
    /*
    print $login;
    foreach($_SESSION as $k=>$v){
        print $k.":".$v;
    }*/
    print "not login";
?>
<a href="/site/user/login.php">Login</a>
<?php
    return;
}
else{
?>
<div>
hello,<?php print getUserNamebyId($uid);?>
<a href="/site/user/logout.php">Logout</a>
</div>
<?php
    if(checkSinaLinkedbyId($uid)){
?>
<!--sina is linked-->
<p><a href="/site/user/disconsinalink.php">Disconnect Sina Account</a></p>
<?php
    }else{
?>
<!--sina is not linked-->
<p><a href="/site/user/weibocon.php">Connect Sina</a></p>
<?php
}

if(checkDoubanLinkedbyId($uid)){
?>
<p><a href="/site/user/discondoubanlink.php">Disconnect Douban Account</a></p>
<?php
}
else{
?>
<p><a href="/site/user/doubancon.php">Connect Douban</a></p>
<?php
}

}

?>
