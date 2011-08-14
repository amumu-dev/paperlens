<?php
/*
this page get uid from get parameter

get user name according to the uid 

let user input the email and password

this simple means update the user info ,not create any new item
*/
include_once("userfunc.php");

if($_SERVER['REQUEST_METHOD'] == "GET"){
    $uid = $_GET['uid'];
    $linktype = getLinkingType($uid);
    if($linktype == "error"){
        echo "get linking type error";
        return;
    }
    elseif($linktype == "sina"){
        $sinainfo = getSinaInfobyId($uid);
        $username = $sinainfo['sinaname'];
    }
    elseif($linktype == "douban"){
        $doubaninfo = getDoubanInfobyId($uid);
        $username = $doubaninfo['dname'];
    }
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="Author" contect="wangxing">
    <meta http-equiv="Content-Language" contect="zh-CN">
    <script type="text/javascript" src=""></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    <!--
    //handle the blur of checking username and email
    $usernameok = false;
    $emailok = false;

    function checkvalid(){
        //check empty and the format things
        if($usernameok == false){
            alert("username invalid");
            return false;
        }

        if($emailok == false){
            alert("email invalid");
            return false;
        }

        if($("#id_pwd").attr("value") != $("#id_cpwd").attr("value")){
            aleret("pwd does not match");
            return false;
        }
        return true;
    }

//check the valid of the name and email while inputing?
function checkUserName(){
    $.getJSON("/site/user/checkusernameused?username="+$("#id_username").attr("value"),checkUserNameResult);
}

function checkUserNameResult(data){
    //$("#id_user_hidden").attr("hidden","false");
    if(data.status == "good"){
        //$("#id_user_hidden").innerHTML = "username ok";
        $("#id_user_hidden").html("username invalid");
        $usernameok = false;
    }
    else if(data.status == "bad"){
        $("#id_user_hidden").html("username ok");
        $usernameok = true;
    }
    else{
        alert("status not right:"+data.status);
        $usernameok = false;
    }
}


function checkEmail(){
    $curemail = $("#id_email").attr('value');
    if(!checkEmailFormat($curemail)){
        //alert($curemail+checkEmailFormat($curemail));
        $emailok = false;
        $("#id_email_hidden").html("email format error");
    }
    else{
        $.getJSON("/site/user/checkemailused?email="+$("#id_email").attr("value"),checkEmailResult);
    }
}

function isValidEmailAddress(emailAddress) {
var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
alert( pattern.test(emailAddress) );
};


function checkEmailFormat(email_addr){
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(email_addr);
}

function checkEmailResult(data){
    //$("#id_email_hidden").attr("hidden","false");
    if(data.status == "good"){
        $emailok = false;
        $("#id_email_hidden").html("email invaid");
    }
    else if(data.status == "bad"){
        $emailok = true;
        $("#id_email_hidden").html("email ok");
    }
    else{
        alert("status not right:"+data.status);
    }
}
-->


function initevent(){
    $("#id_username").keyup(checkUserName);
    $("#id_email").keyup(checkEmail);
    checkUserName();
}

$(initevent);
</script>
</head>

<body>
<form action='/site/user/connewuser.php' method="post">
    <input type="hidden" name="uid" value="<?php print $_GET['uid'];?>"/>
      <div>
        <label>username:</label>
        <input type="text" id="id_username" name="username" value="<?php print $username;?>">
        <label id="id_user_hidden"></label>
    </div>
    <div>
        <label>email:</label>
        <input type="text" id="id_email" name="email">
        <label id="id_email_hidden"></label>
    </div>
    <div>password:<input type="password" id="id_pwd" name="pwd"></div>
    <div>confirm:<input type="password" id="id_cpwd" name="cpwd"></div>
    <div><input type="submit" value="Connect" onclick="return checkvalid()"></div>
</form>
</body>
<?php
}
else{
    include_once("userfunc.php");

    $username = $_POST['username'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $uid = $_POST['uid'];

    //insert
    updateUserInfo($uid,$username,$email,$pwd);
    
    //login and redirect
    loginUser($uid);
    Header("Location: /site/index.php");
}

?>
