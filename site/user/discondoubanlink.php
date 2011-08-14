<?php
session_start();
require_once("../session.php");

if(!$login){
?>
Not Login!!! Illegal Disconnection!
<a href="/site/user/login.php"></a>
<?php
}
else{
    //update the database
    require_once("../../api/db.php");

    $thesql = "update user set doubanid=NULL,dname=NULL,dackey=NULL,dacsec=NULL where id=$uid";
    $result = mysql_query($thesql);

    if(!$result) die("update table error".mysql_error());

    Header("Location: /site/user/constatus.php");    
}
?>

