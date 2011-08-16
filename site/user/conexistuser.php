<?php
/*
this page get uid from get parameter

let user input the email and password

if email and pwd match then link to this user uid2

link mean write the uid1 info to uid2
delete uid2 info then*/

include_once("userconfig.php");
include_once("userfunc.php");

session_start();
require_once("../session.php");

$email = "";
if($login){
    //getEmailbyId
    $email = getEmailbyUid($uid);
}

$conuid = $_GET['uid'];

if($_SERVER['REQUEST_METHOD'] == "GET"){
?>
<!--the html to link-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="Author" content="wangxing">
    <script type="text/javascript" src=""></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
<!--
//this block check the exist username and pwd valid

$userexist = false;

function checkuser(){
    if($userexist == false){
        alert("user not exist");
        return false;
    } 
    return true;
}

function checkEmail(){
     $.getJSON("/site/user/checkemailused?email="+$("#email").attr("value"),checkEmailResult);
}

function checkEmailResult(data){
    //$("#id_user_hidden").attr("hidden","false");
    if(data.status == "good"){
        //$("#id_user_hidden").innerHTML = "username ok";
        $("#id_email_hidden").html("");
        $userexist = true;
    }
    else if(data.status == "bad"){
        $("#id_email_hidden").html("email address not signup");
        $userexist = false;
    }
    else{
        alert("status not right:"+data.status);
        $userexist = false;
    }
}


function initevent(){
    $("#email").keyup(checkEmail);
    checkEmail();
}

$(initevent);

-->
</script>
</head>

<body>
<form action="/site/user/conexistuser.php" method="post">
    <input type="hidden" name="uid" value="<?php echo $_GET['uid']; ?>"/> 
    <div>
        <label>email:</label>
        <input type="text" id="email" name="email" value="<?php print $email;?>">
        <label id="id_email_hidden"></label>
    </div>
    <div>
        <label>password:</label>
        <input type="password" id="pwd" name="pwd">
        <label id="id_pwd_hidden"></label>
    </div>
    <div><input type="submit" value="Connect" onclick="return checkuser()"></div>
    <div><a href="/site/user/connewuser?uid=<?php print $conuid; ?>">Connect a new User</a>
    </form>

    <div id="errorinfo">
    </div>
</body>
<?php
}
else{
    //handle the linked post request
    //delete this conuid and fill the info into the uid line

    //get the linking account info
    
    require_once($root_path."api/db.php");

    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $uid = getUidbyEmailPwd($email,$pwd);
    if($uid == NULL){
        echo "email pwd not match <a href='/site/user/conexistuser.php?uid=$conuid'>Re Connect</a>";
        return;
    }
    
    
    //what link type? sina or douban
    $conuid = $_POST['uid'];
    
    $linktype = getLinkingType($conuid);

    if($linktype == "error"){
        echo "get linking type error";
        return;
    }
    elseif($linktype == "sina"){
        //sina part
        if(checkSinaLinkedbyId($uid)){
            loginUser($uid);
            echo "alread linked to a sina id<a href='/site/user/constatus.php'>link status</a>";
            return;
        }

        //update
        $sinainfo = getSinaInfobyId($conuid);
        $thesql = "update user set sinaid='".$sinainfo['sinaid']."',sinaname='".$sinainfo['sinaname']."',sinaackey='".$sinainfo['sinaackey']."',sinaacsec='".$sinainfo['sinaacsec']."' where id=$uid;";
        $result = mysql_query($thesql);
        if(!$result){
            throw New Exception("update sina info failed ".mysql_error());
        }

        //delete
        $result = mysql_query("delete from user where id='$conuid';");
        if(!$result){
            throw New Exception("delete sina info failed ".mysql_error());
        }
        Header("Location: /site/user/constatus.php"); 
    }
    elseif($linktype == "douban"){
        //douban part
        if(checkDoubanLinkedbyId($uid)){
            echo "alread linked to a douban id<a href='/site/user/constatus.php'>link status</a>";
            return;
        }

        //update
        $doubaninfo = getDoubanInfobyId($conuid);
        $did = $doubaninfo['doubanid'];
        $dname = $doubaninfo['dname'];
        $dackey = $doubaninfo['dackey'];
        $dacsec = $doubaninfo['dacsec'];
        $thesql = "update user set doubanid='$did',dname='$dname',dackey='$dackey',dacsec='$dacsec' where id=$uid;";
        $result = mysql_query($thesql);
        if(!$result){
            throw New Exception("update douban info failed".mysql_error());
        }

        //delete
        $result = mysql_query("delete from user where id='$conuid';");
        if(!$result){
            throw New Exception("delete sina info failed ".mysql_error());
        }
        Header("Location: /site/user/constatus.php"); 
    }
    
   
}

?>
