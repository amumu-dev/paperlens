<?php

if($_SERVER['REQUEST_METHOD'] == "GET")//show the signup page
{
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

    function checkreg(){
        //check empty and the format things
        if($usernameok == false){
            alert("username invalid");
            return false;
        }
        if($emailok == false){
            alert("email invalid");
            return false;
        }
        return true;
    }

//check the valid of the name and email while inputing?
function checkUserName(){
    $.getJSON("/site/user/checkusernameused.php?username="+$("#id_username").attr("value"),checkUserNameResult);
}

function checkUserNameResult(data){
    //$("#id_user_hidden").attr("hidden","false");
    if(data.status == "good"){
        //$("#id_user_hidden").innerHTML = "username ok";
        $("#id_user_hidden").html("username used");
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
        $("#id_email_hidden").html("email invalid");
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
    //$("#id_username").onkeydown(checkUserName);
    //$("#id_email").onkeydown(checkEmail);
    //$("#id_username").keydown(checkUserName);
    //$("#id_email").keydown(checkEmail);
    $("#id_username").keyup(checkUserName);
    $("#id_email").keyup(checkEmail);
    checkUserName();
    checkEmail();
}

$(initevent);

</script>
</head>

<body>

<div id="content-main">
<form action="/site/signup.php" method="post"> 
    <table>
        <tr>
            <td><label for="id_username">Username:</label> </td>
            <td><input type="text" name="username" id="id_username"></td>
            <td><label id="id_user_hidden"></label></td>
        </tr>
        <tr>
            <td><label for="id_email">Email:</label> </td>
            <td><input type="text" name="email" id="id_email"></td>
            <td><label id="id_email_hidden"></label></td>
        </tr>

        <tr>
            <td><label for="id_password">Password:</label> </td>
            <td><input type="password" name="password" id="id_password"></td>
        </tr>
        <tr>
            <td><input type="submit" value="Register" onclick="return checkreg()"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>使用其他方式登陆：</td>
            <td><a href="/site/doubancon.php">连接豆瓣</a></td>
            <td><a href="/site/weibocon.php">连接新浪</a></td>
        </tr>
    </table>
</form>
</body>

<?php
}
else//post reqeust
{
    require_once('../api/db.php');
    $username = ($_POST['username']);
    $password = md5($_POST["password"]);
    $email = $_POST["email"];
//$keywords = $_POST["keywords"];
//print $keywords;
//return;

if(strpos($email, "@") === false)
{
	echo "<h2>Email address is invalid!</h2>";
	return;
}

/*
if(strlen($keywords) == 0)
{
	echo "<h2>You must input research areas, seprated by comma</h2>";
	return;
}
 */

if(strlen($_POST["password"]) < 6)
{
	echo "<h2>Password must exceed 6 characters</h2>";
	return;
}

mysql_query("replace into user (username,email,passwd,keywords) values ('" . $username . "' , '" . $email . "', '".$password."', '" . $keywords . "');");

echo "SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'";
$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
if ($result) 
{
	$row = mysql_fetch_row($result);
	$uid = $row[0];
	session_start();
	$_SESSION["admin"] = true;
	$_SESSION["uid"] = $uid;
	Header("Location: index.php?uid=" . $uid);
}
}
?>
